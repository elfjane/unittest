<?php
// 如果 getallheaders function 不存在，自動建立一個
if (!function_exists('getallheaders')) {
    function getallheaders()
    {
       $headers = array ();
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_') {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
}

// 取得 真實 IP
function get_IP()
{
    // 判定 HTTP_X_FORWARDED_FOR 是否存在
    if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // 如果不存在 檢查 HTTP_X_REAL_IP 是否存在，如果存在則指定IP
        if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $user_ip = $_SERVER['HTTP_X_REAL_IP'];
        }

        // 如果 user_ip 沒有 IP 則指定 REMOTE_ADDR 為 IP
        if (empty($user_ip)) {
            $user_ip = $_SERVER['REMOTE_ADDR'];
        }
    } else {
        $user_ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $user_ip = $user_ip[0];
    }

    return $user_ip;
}

function make_inputs($api_type)
{
    require_once('test/' . $api_type . '/api.php');
    require_once('test/' . $api_type . '/value.php');

    $item_list = $api;

    $parameter_list = $value;

    $inputs = array();

    foreach($item_list as $key => $val) {

        $inputs[$key]['item_title']     = $val['item_title'];
        $inputs[$key]['item_help']      = $val['item_help'];
        foreach($val['parameter_list'] as $row2) {
            if (isset($parameter_list[$row2])) {
                $inputs[$key]['parameter_list'][$row2] = $parameter_list[$row2];
            } else {
                $inputs[$key]['parameter_list'][$row2] = '';
            }
        }
    }
    return $inputs;

}
function make_html($inputs)
{
        $input_list = array();
        foreach ($inputs as $input => $value) {

            $input_list[$input] = array(
                "name" => $input,
                "src"  => CRON_WEB_URL . $input,
            );

            foreach ($value['parameter_list'] as $key => $val) {
                $val_data = $val;

                $parameter_val = '';
                $parameter_help = '';
                $parameter_type = '';
                if (isset($val_data['val'])) {
                    $parameter_val = $val_data['val'];
                }
                if (isset($val_data['help'])) {
                    $parameter_help = $val_data['help'];
                }
                if (isset($val_data['type'])) {
                    $parameter_type = $val_data['type'];
                }

                $input_list[$input]["item_title"] = $value['item_title'];
                $input_list[$input]["item_help"]  = $value['item_help'];
                $input_list[$input]["key"][$key] = array(
                    "v"     => $parameter_val,
                    "title" => $key,
                    "help"  => $parameter_help,
                    "type"  => $parameter_type,
                );
            }
        }
        return $input_list;
}

// 發送 json
function send_json($url, $data)
{


    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    return $result = curl_exec($ch);
}
/*
// csv load
$file='test/' . $api_type . '/value.csv';
$csv= file_get_contents($file);
$array = array_map("str_getcsv", explode("\n", $csv));
$json = json_encode($array);
print_r($json);
*/
?>