<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Author" content="elfjane">
    <meta name="Keywords" content="unittest">
    <meta name="Description" content="test debug api">
    <title>unittest</title>

    <style>
        table{width: 100%;border-collapse: collapse;}
        table td{border: solid 1px white;}
        table tr:last-child{border-bottom: none;}
        table th{border: solid 1px white;background-color: #0cb9e0;color: white;text-align: center;}
        table .right_th{width:15%;position: relative;background-color: #0dcaf0;color: white;text-align: center;padding: 10px 0;}
        table .right_th:after{display: block;content: "";width: 0px;height: 0px;position: absolute;top:calc(50% - 10px);right:-10px;border-left: 10px solid #0dcaf0;border-top: 10px solid transparent;border-bottom: 10px solid transparent;}
        table td{text-align: left;background-color: #eee;padding: 2px 5px 2px 20px}
        .btn{padding:5px;width:100%;  font-size: calc(1em + 1vmin);background: #0d6efd;color:#fff;border-radius: 5px;border: 1px solid #0d6efd;}
        .btn:hover{color:#eee;border: 1px solid #000;}
        .status{margin:10px;text-align:right;}
        .html_iframe{display:flex;flex-wrap:wrap;justify-content:center;margin:10px;}
        .tab_css{display:flex;flex-wrap:wrap;margin:10px;}
        .tab_css .i_input{display:none}
        .tab_css .i_label{margin: 0 5px 5px 0; padding: 10px 16px; cursor: pointer; border-radius: 5px; background: #0d6efd; color: #fff; opacity: 0.2;}
        .tab_content{order:1;display: none; width:100%; border-bottom: 3px solid #ddd; line-height: 1.6; padding: 15px; border: 1px solid #ddd; border-radius: 5px;}
        .tab_css .i_input:checked + .i_label, .i_label:hover{opacity: 1; font-weight:bold;}
        .tab_css .i_input:checked + .i_label + .tab_content{display: initial;}
        input[type=text]{width:90%;margin:0}
        pre {
            display: block;
            padding: 3px;
            word-break: break-all;
            word-wrap: break-word;
            white-space: pre;
            white-space: pre-wrap;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0,0,0,0.15);
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="status">
    <p>server ip: <?=get_server_IP(); ?>, your ip <?= get_IP(); ?></p>
</div>
<div class="tab_css">
<?php
$index=10;
$checked = ' checked="checked"';

foreach ($html as $key => $val)
{
    $index++;
	echo '<input class="i_input" id="tab'.$index. '" type="radio" name="tab"'.$checked .'/>';
    $checked  = '';
    echo '<label class="i_label" for="tab'.$index. '">'.$key.'</label>';
	echo '<div class="tab_content">';
    //var_dump($val);
?>
<form  class="form-horizontal f_send" method="post"  target="iframe_a"  action="/post.php">
    <input type="hidden" name="i_send_url" value="<?= $val['src'] ?>">
    <input type="hidden" name="i_api_type" value="<?= $api_type; ?>">
    <pre><?=$val['item_title']?><BR><?=$val['item_help']?></pre>
    <div class="table_main">
    <table>
    <tr>
        <th>Parameter Name</th>
        <th>Parameter Type</th>
        <th>Parameter Help</th>
        <th>Parameter Value</th>
    </tr>
<?php
foreach ($val['key'] as $key2 => $val2)
{
    echo '<tr><td class="right_th" align="right">' . $val2['title'] . '</th>';
    echo '<td>'. $val2['type'] .'</td>';
    echo '<td>'. $val2['help'] .'</td>';

    echo '<td>';
    if (strlen($val2['v']) < 40) {
        echo '<input type="text" name="' . $key2 . '" class="' . $key2 . '" placeholder="' . $val2['help'] . '" value="' . $val2['v'] .  '">';
    } else {
        echo '<textarea name="' . $key2 . '" class="' . $key2 . ' auto-height" rows="6" cols="">'. $val2['v'] .'</textarea>';
    }
    echo '</td></tr>';
}
?>
        <tr>
            <td class="right_th">send url</th>
            <td colspan="2"><?=$val['src']?></td>
            <td><button type="submit" class="btn">Send</button></td>
        </tr>
    </table>
    </div>

</form>
</div>
<?php
}
?>
</div>
<div class="html_iframe">
    <iframe id="iframe_submit" name="iframe_a" src="" width="100%" height="400" scrolling="auto" allowtransparency="true"></iframe>
</div>
</body>
</html>
