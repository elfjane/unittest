<?php
require_once('config.php');
require_once('function.php');
require_once('function_html.php');


$api_type = 'cms_tools';
if (isset($_REQUEST['api_type'])) {
    $api_type = $_REQUEST['api_type'];
}


    $api_file_json = 'test/' . $api_type . '.json';
    if (file_exists($api_file_json)) {
        $api_json = file_get_contents($api_file_json);
        $apiDecode = json_decode($api_json, JSON_OBJECT_AS_ARRAY);
        $api = $apiDecode['api'];
        $value = $apiDecode['value'];
        $config = $apiDecode['config'];
    }
    $helpList = json_encode($value);
    $urlList = $config['url'];
    $html = array();


    $inputs = make_inputs($api, $value);
    if (isset($_REQUEST['url'])) {
        $url = $_REQUEST['url'];
    } else {
        $url = $config['url'][0];
    }

    $html = make_html($inputs, $url);


$async = getAsync($value);

$api_type_list = get_api_json_type();
//$input_list = json_encode($html);

$tpl = 'unittest_adv_read_log';
if (!empty($_GET['tpl'])) {
    //include 'tpl/unittest_adv.tpl.php';
    //include 'tpl/unittest_' . $_GET['tpl'] . '.tpl.php';
    $tpl = $_GET['tpl'];
}

// 刪除 範例程式碼
$tpl_delete = 0;
if (!empty($_GET['tpl_del'])) {
    $tpl_delete = $_GET['tpl_del'];
}

include 'tpl/'.$tpl.'.tpl.php';

//include 'tpl/unittest3.tpl.php';
?>