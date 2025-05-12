<?php
require_once('config.php');
require_once('function.php');
require_once('function_db.php');
require_once __DIR__.'/vendor/autoload.php';
$getAt = date(CRON_BASE_DATE);
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

$converter = new AnsiToHtmlConverter();

// 預備變數
$data = [];
$headers = [];
$url = $_POST['i_send_url'] ?? '';
$send_type = $_POST['i_send_type'] ?? 'POST';
$api_type = $_POST['i_api_type'] ?? 'POST';
$i_input_uri = $_POST['i_input_uri'] ?? '';
$i_send_header = $_POST['i_send_header'] ?? '';
$i_color = isset($_POST['i_color']);

$api_file_json = 'test/' . $api_type . '.json';
if (file_exists($api_file_json)) {
    $api_json = file_get_contents($api_file_json);
    $apiDecode = json_decode($api_json, JSON_OBJECT_AS_ARRAY);
    $api = $apiDecode['api'];
    $value = $apiDecode['value'];
    $config = $apiDecode['config'];
}
// 將 POST 資料過濾進來（排除特殊欄位）
$skip_keys = ['i_send_type', 'i_send_url', 'i_api_type', 'i_send_header', 'i_input_uri', 'i_color'];
foreach ($_POST as $key => $val) {
    if (!in_array($key, $skip_keys) && $val !== '') {
        $dataType='';
        if (isset($value[$key]) ) {
            $dataType = $value[$key]['type'];
        }
        switch($dataType)
        {
            case 'json':
            {
                $data[$key] = json_decode($val);
            } break;
            default:
            {
                $data[$key] = $val;
            } break;
        }

    }
}

// 加上 $_FILES 資料
foreach ($_FILES as $key => $file) {
    if (is_array($file['name'])) {
        foreach ($file['name'] as $index => $name) {
            if ($file['error'][$index] === UPLOAD_ERR_OK) {
                if (!isset($data[$key])) {
                    $data[$key] = [];
                }
                $data[$key][] = new CURLFile(
                    $file['tmp_name'][$index],
                    $file['type'][$index],
                    $name
                );
            }
        }
    } else {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $data[$key] = new CURLFile(
                $file['tmp_name'],
                $file['type'],
                $file['name']
            );
        }
    }
}

// 插入 URI 參數
if (!empty($i_input_uri)) {
    $uri_parts = [];
    foreach (explode(',', $i_input_uri) as $key) {
        if (isset($data[$key])) {
            $uri_parts[] = urlencode($data[$key]);
            unset($data[$key]);
        }
    }
    $url .= '/' . implode('/', $uri_parts);
}

// 處理 header
$getHeaders  = getallheaders();
$sendHeaders = [];
foreach ($getHeaders as $headerKey => $val) {
    $key = strtolower($headerKey);
    $sendHeaders[$key] = $val;
}

$inputHeader = [];
if (!empty($i_send_header)) {
    $inputHeader= explode(',', trim($i_send_header));
}

$headers=[];
foreach ($inputHeader as $key) {

    if (isset($sendHeaders[$key])) {
        $headers[] = $key .": " .$sendHeaders[$key];
    }
}
//var_dump($headers);
// 送出
$response = send_file_json($url, $data, $send_type, $headers);
if (isset($response['body'])) {
    $body = $response['body'];
} else {
    $body = [];
}

if (isset($response['contentType'])) {
    $contentType = $response['contentType'];
} else {
    $contentType = 'application/x-www-form-urlencoded';
}



// 可以處理回應，例如
// echo $converter->convert($ret); // 如果回應是 console ANSI 格式的

// 把 ansi 轉成 html
if ($i_color && !empty($body)) {
    $body = $converter->convert($body);
}

if (!empty($body)) {
    $decoded_body = json_decode($body, true);
} else {
    $decoded_body = [];
}


$is_json = false;
if (json_last_error() === JSON_ERROR_NONE) {
    $is_json = true;
    $response['body'] = $decoded_body; // 成功解析才換成陣列
}

$send = [
    "header_encode" => $inputHeader,
    "content_type" => $contentType,
    "data_encode" => $data,
];

$response["send"] = $send;

function wLog($filename, $logData)
{
    date_default_timezone_set(CRON_LOG_TIMEZONE);
    if (is_array($logData)) {
        $data = array_merge(['datetime' => date(CRON_BASE_DATE)], $logData);
    } else {
        $data = [
            'datetime' => date(CRON_BASE_DATE),
            'log' => $logData
        ];
    }

    $logStr = json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    $datefilename = date(CRON_BASE_LOG_DATE);

    $dateFolder = date(CRON_BASE_LOG_DATE); // 建立如 logs/20250507/
    $dateFile   = date(CRON_BASE_LOG_DATE); // 如 20250507_140501
    $dirPath    = CRON_BASE_PATH . "/logs/{$dateFolder}";
    $filePath   = "{$dirPath}/{$filename}_{$dateFile}.txt";

    // 建立資料夾（如果不存在）
    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0777, true); // 第三參數 true 表示遞迴建立多層資料夾
    }
    file_put_contents($filePath, $logStr, FILE_APPEND);
}

wLogToSQLite('send', $response, $url, $getAt);
header('Content-Type: application/json');
echo json_encode($response);
exit;
//$json_print      = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>