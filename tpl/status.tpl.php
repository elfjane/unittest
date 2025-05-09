<div class="status">
    <p>
<?php
foreach ($api_type_list as $row)
{
    echo '<a href="?api_type=' . $row . '">' . $row . '</a> | ';
}
?>
</p>
    <p>server ip: <?=get_server_IP(); ?>, your ip <?= get_IP(); ?>
    </p>
    <select name="i_send_url" onchange="location.href=this.value;">
<?php
    foreach ($urlList as $url)
    {
?>
    <option value="?api_type=<?= $api_type; ?>&url=<?=$url?>"><?=$url?></option>
<?php
    }
?>
</select>
</div>