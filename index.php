<?php
require_once('config.php');
require_once('function.php');


$api_type = 'stock_api';
if (isset($_REQUEST['api_type'])) {
    $api_type = $_REQUEST['api_type'];
}

require_once('test/' . $api_type . '/config.php');

$inputs = make_inputs($api_type);
if (isset($_REQUEST['url'])) {
    $url = $_REQUEST['url'];
} else {
    $url = CRON_WEB_URL;
}

$html = make_html($inputs, $url);
//$input_list = json_encode($html);
include 'tpl/unittest_pure.tpl.php';
//include 'tpl/unittest3.tpl.php';
?>