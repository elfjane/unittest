<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Author" content="elfjane">
    <meta name="Keywords" content="sdk">
    <meta name="Description" content="test debug api">
    <title>unittest</title>

    <style>
        pre {
            display: block;
            padding: 10px;
            margin: 10px;
            word-break: break-all;
            word-wrap: break-word;
            white-space: pre;
            white-space: pre-wrap;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border: 1px solid rgba(0,0,0,0.15);
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }
        .html_iframe{display:flex;flex-wrap:wrap;justify-content:center;margin:10px;}
        .tab_css{display:flex;flex-wrap:wrap;justify-content:center;margin:10px;}
        .tab_css .i_input{display:none}
        .tab_css .i_label{margin: 0 5px 5px 0; padding: 10px 16px; cursor: pointer; border-radius: 5px; background: #000; color: #fff; opacity: 0.2;}
        .tab_content{order:1;display: none; width:100%; border-bottom: 3px solid #ddd; line-height: 1.6; font-size: .9em; padding: 15px; border: 1px solid #ddd; border-radius: 5px;}
        .tab_css .i_input:checked + .i_label, .i_label:hover{opacity: 1; font-weight:bold;}
        .tab_css .i_input:checked + .i_label + .tab_content{display: initial;}
    </style>
</head>
<body>

<div class="container">
    <h1>server ip: <?=$server_ip; ?>, your ip <?= $remote_ip; ?></h1>
</div>
<div class="tab_css">
<?php
$index=10;
$checked = ' checked="checked"';
foreach ($html as $key => $val)
{
    $index++;
	echo '<input class="i_input" id="tab'.$index. '" type="radio" name="tab"'.$checked .'/>';
    echo '<label class="i_label" for="tab'.$index. '">'.$key.'</label>';
	echo '<div class="tab_content">';
    //var_dump($val);
?>
<form  class="form-horizontal f_send" method="post"  target="iframe_a"  action="/post.php">
    <input type="hidden" name="i_send_url" value="<?= $val['src'] ?>">
    <input type="hidden" name="i_api_type" value="<?= $api_type; ?>">
    <TABLE>
<?php
foreach ($val['key'] as $key2 => $val2)
{
    //var_dump($val2['v']);
    //exit;
    echo '<tr><td align="right">' . $val2['title'] . '</td>';
    if (strlen($val2['v']) < 40) {
        echo '<td><input type="text" name="' . $key2 . '" class="' . $key2 . '" placeholder="' . $val2['help'] . '" value="' . $val2['v'] .  '"></td>';
    } else {
        echo '<td><textarea name="' . $key2 . '" class="' . $key2 . ' auto-height" rows="6" cols="">'. $val2['v'] .'</textarea></td>';
    }

    echo '<td><pre>['. $val2['type'] .']<BR>'. $val2['help'] .'</pre></td></tr>';
   // exit;
}
?>

        <tr>
            <td align="right"><?=$val['item_title']?></td>
            <td><button type="submit" class="btn">Send</button></td>
            <td><pre><?=$val['item_help']?></pre></td>
        </tr>
    </table>
</form>
<?php
	echo '</div>';
    $checked  = '';
}
?>
</div>
<div class="html_iframe">
    <iframe id="iframe_submit" name="iframe_a" src="" width="100%" height="400" scrolling="auto" allowtransparency="true"></iframe>
</div>
</body>
</html>
