<?php
//目录标题
include('data_processing.php');
//目录配置文件
include('word_config.php');
$host = $_SERVER['HTTP_HOST'];
$url = "http://".$host.'/';

$html_k =  '<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <xml><w:WordDocument><w:View>Print</w:View></xml>
</head>
<style>
</style>
';
$body_k = '
<body>
    <div style="text-align: center;margin-top: 250px">
        <p><font size="5" style="font-size: 30pt;">'. $_SESSION['word_name'].'</font></p>
        <p><font size="5" style="font-size: 30pt">代码审计报告</font></p>
    </div>
    <div style="text-align: center;margin-top: 300px;margin-bottom:150px">
        <p>
            <img src="'.$url.'images/logo.png" width="147" height="37" style="margin-bottom: 20px"><br>
            <p><font size="5" style="font-size: 18pt;">北京匠迪技术有限公司</font></p>
            <font size="4" style="font-size: 16pt"><b>'.date('Y').'</b></font><font face="宋体">
            <font size="4" style="font-size: 16pt"><b>年</b></font>
            <font size="4" style="font-size: 16pt"><b>'.date('m').'</b></font><font face="宋体">
            <font size="4" style="font-size: 16pt"><b>月</b></font>
        </p>
    </div>
    ';
    //目录
    $catalog_j = '';
/*    foreach($word_array as $key1 => $value1){
        $catalog_k =  '<div>';
        // 一级目录
        $catalog_1 = '';

        $catalog_1 .=  '<p style="line-height: 0.17in">
                <font size="4" style="font-size: 14pt">'.$value1['number'].'</font>
                <font style="font-size: 14pt">'.$value1['title'].'</font>
            </p>';
        if(is_array($value1['cd']) ){
            //二级目录
            $catalog_2 = '';
            foreach($value1['cd'] as $key2 => $value2){
                $catalog_2 .=  '<p style="margin-left: 0.15in;line-height: 0.17in">
                            <font size="4" style="font-size: 14pt">'.$value2['number'].'</font>
                            <font style="font-size: 14pt">'.$value2['title'].'</font>
                        </p>';
                if(is_array($value2['cd']) ){
                    //三级目录
                    foreach($value2['cd'] as $key3 => $value3){
                        $catalog_2 .= '<p style="margin-left: 0.45in;line-height: 0.17in">
                                    <font size="4" style="font-size: 14pt;">'.$value3['number'].' </font>
                                    <font style="font-size: 14pt">'.$value3['title'].'</font>
                              </p>';
                    }
                }
            }
        }
        $catalog_z = '</div>';
        $catalog_j .= $catalog_k.$catalog_1.$catalog_2.$catalog_z;
    }*/
    //目录实体
    $catalog_content = '';
    foreach($word_content as $content_key => $content_value){
        $conent_title = '';$content_content = '';
        if($content_value['grade'] == 1){
            $conent_title = '<div><p><h1><span style="line-height:150%;font-size:22pt" face="宋体"><b>'.$content_key.'</b></span><span  style="line-height:150%;font-size:22pt" face="宋体"><b>'.$content_value['title'].' &nbsp;</b></span></h1></p>';
        }else{
            $conent_title = '<div><p><h'.$content_value['grade'].'><span style="line-height:150%;font-size:16pt" face="宋体"><b>'.$content_key.'</b></span><span  style="line-height:150%;font-size:16pt" face="宋体"><b>'.$content_value['title'].' &nbsp;</b></span></h'.$content_value['grade'].'></p>';
        }

        $content_content = '
        <p style="line-height: 150%">
            <span style="font-size: 12pt" face="宋体">
               '.$content_value['content'].'
            </span>
        </p></div>';
        $catalog_content .= $conent_title.$content_content;
    }
$body_z =  '</body>';

    $html = $html_k.$body_k.$catalog_j.$catalog_content.$body_z;

    ob_start(); //打开缓冲区
    echo $html.'</html>';
    echo $html_k.$html.'</html>';
    Header("Content-type: application/octet-stream");
    header('Content-Disposition: attachment; filename=代码审计报告.doc');
    ob_end_flush();//输出全部内容到浏览器
