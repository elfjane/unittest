<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="Author" content="elfjane">
        <meta name="Keywords" content="sdk">
        <meta name="Description" content="test debug mobile sdk">
        <title>unittest</title>
        <link href="/themes/unittest/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/themes/unittest/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <script src="/themes/unittest/bootstrap/js/jquery-2.1.1.min.js"></script>
        <script src="/themes/unittest/bootstrap/js/bootstrap.min.js"></script>
        <style>
            input, textarea {
                width: 300px;
            }
            table td, table th {
              padding: 6px 4px;
            }
        </style>
   <script type="text/javascript">

   </script>
    </head>
    <body>
    <div class="tab_css">
	<!-- TAB1 打包區塊 start -->
	<input id="tab1" type="radio" name="tab" checked="checked"/>
	<label for="tab1">頁籤 1</label>
	<div class="tab_content">
		第 1 個頁籤內容區塊<br/>
		WFU BLOG | Blogger 調校資料庫<br/>
	</div>
	<!-- TAB1 打包區塊 end -->

	<!-- TAB2 打包區塊 start -->
	<input id="tab2" type="radio" name="tab"/>
	<label for="tab2">頁籤 2</label>
	<div class="tab_content">
		第 2 個頁籤內容區塊<br/>
		WFU BLOG | Blogger 調校資料庫<br/>
		第 2 個頁籤內容區塊<br/>
		WFU BLOG | Blogger 調校資料庫<br/>
	</div>
	<!-- TAB2 打包區塊 end -->

	<!-- TAB3 打包區塊 start -->
	<input id="tab3" type="radio" name="tab"/>
	<label for="tab3">頁籤 3</label>
	<div class="tab_content">
		第 3 個頁籤內容區塊<br/>
		WFU BLOG | Blogger 調校資料庫<br/>
		第 3 個頁籤內容區塊<br/>
		WFU BLOG | Blogger 調校資料庫<br/>
		第 3 個頁籤內容區塊<br/>
		WFU BLOG | Blogger 調校資料庫<br/>
	</div>
	<!-- TAB3 打包區塊 end -->
</div>

<style>
	.tab_css{display:flex;flex-wrap:wrap;justify-content:center;}
	.tab_css input{display:none}
	.tab_css label{margin: 0 5px 5px 0; padding: 10px 16px; cursor: pointer; border-radius: 5px; background: #999; color: #fff; opacity: 0.5;}
	.tab_content{order:1;display: none; width:100%; border-bottom: 3px solid #ddd; line-height: 1.6; font-size: .9em; padding: 15px; border: 1px solid #ddd; border-radius: 5px;}
	.tab_css input:checked + label, .tab_css label:hover{opacity: 1; font-weight:bold;}
	.tab_css input:checked + label + .tab_content{display: initial;}
</style>
        <div class="container">
            <h1><?=$server_ip; ?>, your ip <?= $remote_ip; ?></h1>
            <ul class="nav nav-tabs" id="myTab"></ul>

            <div class="tab-content" id="myTabContent"></div>
            <iframe id="iframe_submit" name="iframe_a" src="" width="95%" height="400" scrolling="auto" allowtransparency="true"></iframe>
        </div>
        <script>
            $(function () {
                run();
                $('#myTab a').click(function (e) {
                    e.preventDefault();
                    $(this).tab('show');
                });

                $('#iframe_submit').load(function (e) {
                    console.log($('#iframe_submit'));
                    $('#i_url span').text($('#iframe_submit').src);
                });


            });

            function run() {
                var input_list = <?= $input_list ?>;
                var tabs = '';
                var input = '';
                var first = 1;
                var key_index = 1;
                for (var key in input_list) {
                    tabs += '<li><a href="#tab_' + key_index + '">'+ input_list[key].name + '</a></li>';
                    if (first === 1) {
                        input += '<div class="tab-pane active" id="tab_' + key_index + '">';
                        first = 0;
                    } else {
                        input += '<div class="tab-pane" id="tab_' + key_index + '">';
                    }
                    input += '<FORM  class="form-horizontal f_send" METHOD="post"  target="iframe_a"  ACTION="/post.php">';
                    input += '<input type="hidden" name="i_send_url" value="'+input_list[key].src+'">';
                    input += '<input type="hidden" name="i_api_type" value="<?= $api_type; ?>">';
                    input += '<table>';
                    for (var key2 in input_list[key].key) {

                        input += '<tr><td align="right">' + input_list[key].key[key2].title + '</td>';
                        if (input_list[key].key[key2].v.length < 40) {
                            input += '<td><input type="text" name="' + key2 + '" class="' + key2 + '" placeholder="' + input_list[key].key[key2].help + "\" value='" + input_list[key].key[key2].v + "'></td>";
                        } else {
                            input += '<td><textarea name="'+ key2 +'" class="' + key2 + ' auto-height" rows="6" cols="">'+ input_list[key].key[key2].v +'</textarea></td>';
                        }
                        input += '<td><pre>['+input_list[key].key[key2].type+']<BR>'+input_list[key].key[key2].help+"</pre></td></tr>"
                        input += '</tr>';
                    }
                    input += '<tr><td align="right">'+ input_list[key].item_title +'</td><td><button type="submit" class="btn">Send</button></td><td><pre>'+ input_list[key].item_help +'</pre></td>';
                    input += '</tr></table></div></div></FORM></div>';
                    key_index++;
                }
                $('#myTab').append(tabs);
                console.log(tabs);
                console.log(input_list);
                $('#myTabContent').prepend(input);
            }

        </script>
    </body>
</html>
