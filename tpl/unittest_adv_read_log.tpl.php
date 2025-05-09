<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Author" content="elfjane">
    <meta name="Keywords" content="unittest">
    <meta name="Description" content="test debug api">
    <title>unittest</title>
<?php include ("style.tpl.php");?>
</head>
<body>

<div class="result-container">
    <div class="html_iframe">
<div class="tab_css">
<?php
$index=10;
$checked = ' checked="checked"';
foreach ($html as $key => $val)
{
    $enctype = "";
    foreach ($val['key'] as $val2)
    {
        if ($val2['type'] == "file" || $val2['type'] == "file_multiple" ) {
            $enctype = 'enctype="multipart/form-data"';
        }
    }
    $index++;
	echo '<input class="i_input" id="tab'.$index. '" type="radio" name="tab"'.$checked .'/>';
    $checked  = '';
    echo '<label class="i_label" for="tab'.$index. '">'.$val['item_title'].'</label>';
	echo '<div class="tab_content">';
    //var_dump($val);
?>
<form  class="form-horizontal f_send" method="post" <?=$enctype;?>   action="post_json.php" id="myframe_<?=$index;?>">
    <input type="hidden" name="i_send_url" value="<?= $val['item_url'] ?>">
    <input type="hidden" name="i_send_type" value="<?= $val['item_type'] ?>">
    <input type="hidden" name="i_api_type" value="<?= $api_type; ?>">
    <input type="hidden" name="i_send_header" value="<?= $val['item_header'] ?>">
    <input type="hidden" name="i_input_uri" value="<?= $val['item_uri'] ?>">
    <div class="pre-toggle"><?=$val['item_help']?></div>
    <div class="table_main">
    <table>
        <tr>
            <th>Parameter Name</th>
            <th>Parameter Title</th>
            <th>Parameter Type</th>
            <th>Parameter Help</th>
            <th>Parameter Value</th>
        </tr>
<?php
if (isset($val['header'])) {
    getInputHeader('i_input_header', 'right_header', $val['header'], 'Header');
}
if (isset($val['uri'])) {
    getInputList('i_input_url', 'right_uri', $val['uri'], 'URI');
}
if (isset($val['key'])) {
    getInputList('i_input_key', 'right_th', $val['key'], 'Input');
}
?>
        <tr>
            <td class="right_th">send url</th>
            <td colspan="3"><?=$val['item_url']?><?= $val['item_uri_help']?></td>
            <td>
                <button type="submit" class="btn" onclick="return load_iframe(this.form)"><?= $val['item_type'] ?></button>
            </td>
        </tr>
    </table>
    </div>
</form>
</div>
<?php
}
?>
</div>
        <div id="result_div" style="width:100%; min-height:600px; background:#fff; border:1px solid #ccc; padding:10px; overflow:auto;">
            <h3>Results Window</h3>
        </div>
    </div>
    <div class="history_list">
    <?php include ("status.tpl.php"); ?>
        <h3>歷史紀錄</h3>
        <ul id="history_list" class="history-ul">
        </ul>
        <input type="button" id="btn_has_more" value="更多" onclick="getLog();">
    </div>
</div>

<?php include ("javascript.tpl.php"); ?>
</body>
</html>
