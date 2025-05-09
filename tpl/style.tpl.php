<style>
    html {overflow-y: scroll;}
    p {margin:0 0 5px 0px;}
    table{width: 100%;border-collapse: collapse;}
    table td{border: solid 1px white;}
    table tr:last-child{border-bottom: none;}
    table th{border: solid 1px white;background-color: #0cb9e0;color: white;text-align: center;}
    table .right_th{width:15%;position: relative;background-color: #0dcaf0;color: white;text-align: center;padding: 10px 0;}
    table .right_th:after{display: block;content: "";width: 0px;height: 0px;position: absolute;top:calc(50% - 10px);right:-10px;border-left: 10px solid #0dcaf0;border-top: 10px solid transparent;border-bottom: 10px solid transparent;}
    table .right_header{width:15%;position: relative;background-color: grey;color: white;text-align: center;padding: 10px 0;}
    table .right_header:after{display: block;content: "";width: 0px;height: 0px;position: absolute;top:calc(50% - 10px);right:-10px;border-left: 10px solid grey;border-top: 10px solid transparent;border-bottom: 10px solid transparent;}
    table .right_uri{width:15%;position: relative;background-color: green;color: white;text-align: center;padding: 10px 0;}
    table .right_uri:after{display: block;content: "";width: 0px;height: 0px;position: absolute;top:calc(50% - 10px);right:-10px;border-left: 10px solid green;border-top: 10px solid transparent;border-bottom: 10px solid transparent;}

    table td{text-align: left;background-color: #eee;padding: 2px 5px 2px 20px}
    .btn{padding:5px;width:100%;  font-size: calc(1em + 1vmin);background: #0d6efd;color:#fff;border-radius: 5px;border: 1px solid #0d6efd;}
    .btn:hover{color:#eee;border: 1px solid #000;}
    .status{text-align:right;}
    .html_iframe{display:flex;flex-wrap:wrap;justify-content:center;width:80%;align-content: flex-start;}
    .tab_css{display:flex;flex-wrap:wrap;width:100%;margin-bottom:10px;align-content: flex-start;flex-direction: row;}
    .tab_css .i_input{display:none}
    .tab_css .i_label{margin: 0 5px 5px 0; padding: 10px 16px; cursor: pointer; border-radius: 5px; background: #0d6efd; color: #fff; opacity: 0.7;height: fit-content;}
    .tab_content{order:1;display: none; width:100%; border-bottom: 3px solid #ddd; line-height: 1.6; padding: 15px; border: 1px solid #ddd; border-radius: 5px;}
    .tab_css .i_input:checked + .i_label, .i_label:hover{opacity: 1; font-weight:bold;}
    .tab_css .i_input:checked + .i_label + .tab_content{display: initial;}
    input[type=text]{width:90%;margin:0}
    #btn_has_more{width:100%;text-algin:center;}
    .pre-toggle{
        display: flex;
        position: relative;
        padding: 0px;
        margin:0px;
    }
    .format-toggle-btn {
        position: absolute;
        top: 0;
        right: 0;
        background-color: #0d6efd;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 3px 8px;
        font-size: 0.9em;
        cursor: pointer;
    }
    .text_area_view {
        width: 100%;
        resize: vertical;
        height: auto;
    }
    pre {
        display: block;
        padding: 3px;
        margin:0px;
        word-break: break-all;
        word-wrap: break-word;
        white-space: pre;
        white-space: pre-wrap;
        background-color: #fff;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
    .result-container {
        display: flex;
        gap: 10px;
        margin: 10px;
    }

    .history_list {
        width: 20%;
        min-width: 200px;
        background: white;
        border: 1px solid #ccc;
        padding: 10px;
        overflow-y: auto;
        max-height: 100%;
        height:2000px;
    }

    .history-ul {
        list-style-type: none;
        padding-left: 0;
    }

    .history-ul li {
        padding: 8px;
        margin-bottom: 5px;
        background-color: #0d6efd;
        color: white;
        border-radius: 4px;
        cursor: pointer;
    }

    .history-ul li:hover {
        background-color: #0b5ed7;
    }


    .html_body {
        background: white;
        border: 1px solid #767676;
        padding: 10px;
        overflow-y: auto;
        max-height: 100%;
        background-color: #eee;
    }


    .html_body_bg {
        background-color: #eee;
    }
</style>