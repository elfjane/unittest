<?php
require_once('function.php');
require_once __DIR__.'/vendor/autoload.php';

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

$converter = new AnsiToHtmlConverter();

// 預備變數
$data = [];
$headers = [];
$url = $_POST['i_send_url'] ?? '';
$send_type = $_POST['i_send_type'] ?? 'POST';
$api_type = $_POST['i_api_type'] ?? 'POST';
$i_input_uri = $_POST['i_input_uri'] ?? '';
$i_input_header = $_POST['i_input_header'] ?? '';
$i_color = isset($_POST['i_color']);

// 將 POST 資料過濾進來（排除特殊欄位）
$skip_keys = ['i_send_type', 'i_send_url', 'i_api_type', 'i_input_header', 'i_input_uri', 'i_color'];
foreach ($_POST as $key => $val) {
    if (!in_array($key, $skip_keys) && $val !== '') {
        $data[$key] = $val;
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

// 插入 Header
$header_encode = '<table>';
if (!empty($i_input_header)) {
    foreach (explode(',', $i_input_header) as $key) {
        if (isset($data[$key])) {
            $headers[] = $key . ': ' . $data[$key];
            $header_encode .= '<tr><td>' . htmlspecialchars($key) . '</td><td>=</td><td>' . htmlspecialchars($data[$key]) . '</td></tr>';
            unset($data[$key]);
        }
    }
}
$header_encode .= '</table>';

// 顯示資料（for debug）
$data_encode = '<table>';
foreach ($data as $key => $val) {
    if ($val instanceof CURLFile) {
        $data_encode .= '<tr><td>' . htmlspecialchars($key) . '</td><td>=</td><td>FILE:' . $val->getFilename() . '</td></tr>';
    } else {
        $data_encode .= '<tr><td>' . htmlspecialchars($key) . '</td><td>=</td><td>' . htmlspecialchars($val) . '</td></tr>';
    }
}
$data_encode .= '</table>';

// 送出
$ret = send_file_json($url, $data, $send_type, $headers);

// 可以處理回應，例如
// echo $converter->convert($ret); // 如果回應是 console ANSI 格式的

// 把 ansi 轉成 html
if ($i_color) {
    $ret = $converter->convert($ret);
}
$ret_decode      = json_decode($ret);
$json_print      = json_encode($ret_decode, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$ret_decode_array = json_decode($ret, true);



    $api_file_json = 'test/' . $api_type . '.json';    
    if (file_exists($api_file_json)) {
        $api_json = file_get_contents($api_file_json);
        $apiDecode = json_decode($api_json, JSON_OBJECT_AS_ARRAY);
        $api = $apiDecode['api'];
        $value = $apiDecode['value'];
        $config = $apiDecode['config'];        
    }  
$parameter_list = $apiDecode['value'];


$ret_decode_help = array();
if (is_array($ret_decode_array)) {
    foreach($ret_decode_array as $key => $val) {
        //var_dump($key);
        if (isset($parameter_list[$key])) {
            $ret_decode_help[$key] = $parameter_list[$key];
        }
    }
}

//var_dump($ret_decode_help);
//echo json_encode($ret);



?>
<style>
    html {
      height: 100%;
    }
    body {
      min-height: 100%;
    }
    input {
        width: auto;
    };
    pre {border-style: solid;}
</style>
<table>
<tr>
    <td>url:</td>
    <td><input id="i_url" type="text" name="" size="155" value="<?= $url; ?>"></td>
</tr>
</table>

<p>header data:</p>
<pre style="padding:10px;border:1px #FFAC55 solid;white-space: pre-wrap;">
<?= $header_encode; ?>
</pre>

<p>post data:</p>
<pre style="padding:10px;border:1px #FFAC55 solid;white-space: pre-wrap;">
<?= $data_encode; ?>
</pre>

<p>return data:</p>
<pre style="padding:10px;border:1px #FFAC55 solid;white-space: pre-wrap;background-color: #000;color: white;">
<?= $ret; ?>
</pre>

<p>return json:</p>
<pre style="padding:10px;border:1px #FFAC55 solid;white-space: pre-wrap;">
<?= $json_print; ?>
</pre>

<p>return json help:</p>
<pre style="padding:10px;border:2px #0000FF solid;white-space: pre-wrap;">
<?php
//var_dump($ret_decode_help);
foreach($ret_decode_help as $key => $val) {
    echo '<pre style="padding:10px;border:1px blue solid;white-space: pre-wrap;">';
    echo $key . ':';
    echo $val['help'];
    echo '</pre>';
}
?>
</pre>
