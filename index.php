<?php
require_once('config.php');
require_once('function.php');


$api_type = 'stock_api';
if (isset($_REQUEST['api_type'])) {
    $api_type = $_REQUEST['api_type'];
}

require_once('test/' . $api_type . '/config.php');

$inputs = make_inputs($api_type);
$html = make_html($inputs);
$input_list = json_encode($html);
$server_ip = $_SERVER['SERVER_ADDR'];
$remote_ip = get_IP();
include 'tpl/unittest_pure.tpl.php';
//include 'tpl/unittest3.tpl.php';
?>