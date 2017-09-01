<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <title>匠迪云</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="/Public/Images/favicon.ico"/>
    <link rel="stylesheet" href="/Public/Css/bootstrap.css">
    <link rel="stylesheet" href="/Public/Css/common.css">
    <link rel="stylesheet" href="/Public/Css/header.css">

    <script src="/Public/Js/jquery-3.1.1.min.js"></script>
    <script src="/Public/Js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-default ">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"><img src="/Public/Images/logo1.png" alt="" title="匠迪云安全"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" >

            <ul class="nav navbar-nav ">
                <li><a href="<?php echo U('Index/index');?>" class="navA index">首页</a></li>
                <li><a href="<?php echo U('Scan/scan');?>" class="navA scan">代码扫描</a></li>
                <li><a href="<?php echo U('Analysis/issues');?>" class="navA issues" >代码分析</a></li>
                <li><a href="<?php echo U('History/history');?>" class="navA history">工程管理</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown user_down"><a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true">您好，<?php echo ($_SESSION['user_name']); ?><span class="caret"></span></a>
                    <ul class="dropdown-menu user_menu">
                        <li class="usemes-li"><a href="<?php echo U('Userinfo/userinfo');?>" class="user_data"><span class="use usemes" ></span> 用户信息</a></li>
                        <li class="usepas-li"><a href="<?php echo U('Changepas/changepas');?>" class="user_data"><span class="use usepas"></span> 修改密码</a></li>
                        <li><a href="mailto:hr@Obsec.net?Subject=代码扫描平台用户建议" class="user_data"><span class="use "></span> 用户建议</a></li>
                    </ul>
                </li>
                <li><a href="/Home/Logout/logout" class="navA"><span class="glyphicon glyphicon-log-out"></span>注销</a></li>
            </ul>
        </div>
    </div>
</nav>
<script>
    $(function(){
        var file = "<?php echo ($_SERVER['PATH_INFO']); ?>";
        if(file == 'Scan/scan'){
            $('.scan').css('border-bottom','4px solid #65b6fc');
        }else if(file.indexOf("Analysis/issues") >= 0 || file.indexOf("Analysis/issues_bro") >= 0 || file.indexOf("Analysis/online") >= 0){
            $('.issues').css('border-bottom','4px solid #65b6fc');
        }else if(file == 'History/history' || file == 'Task/task'){
            $('.history').css('border-bottom','4px solid #65b6fc');
        }else if(file == 'Userinfo/userinfo'){
            $('.usemes').css('background-position','0 -30px');
            $('.usemes-li a').css('color','#145876');
        }else if(file == 'Changepas/changepas'){
            $('.usepas').css('background-position','-40px -30px');
            $('.usepas-li a').css('color','#145876');
        }else if(file == 'Index/index'){
			$('.index').css('border-bottom','4px solid #65b6fc');
		}
    });

    $('.user_down').click(function(){
        if( $('.user_menu').css('display') == 'none'){
            $('.user_menu').css('display','block');
        }else{
            $('.user_menu').css('display','none');
        }

    });
</script>

<link rel="stylesheet" href="/Public/Css/caf.css">
<link rel="stylesheet" href="/Public/Css/jeuic.css">
<link rel="stylesheet" href="/Public/Css/table.css">

<div class="container">
    <div class="row rowpiece">
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" ><a id="zong" href="<?php echo U('Home/Analysis/issues/info_id/'.$info_id);?>">总览视图</a></div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead"><a href="<?php echo U('Home/Analysis/issues_bro/info_id/'.$info_id);?>" style=" background-color:rgba(34,41,48,.6);">分览视图</a></div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead"><a href="<?php echo U('Home/Analysis/online/info_id/'.$info_id);?>">在线审计</a></div>
    </div>
    <div class="row rowpiece type_language">
        <ul  class="nav nav-tabs" id="language_title" style="border-bottom: 1px solid #262d37">
         <?php if(is_array($file_class)): foreach($file_class as $file_key=>$file_class): ?><li class="subheadnext btn-gray btn-codetype">
                <a href="#language"  data-toggle="tab" info_id="<?php echo ($info_id); ?>">
                    <?php echo ($file_key); ?>
                </a>
            </li><?php endforeach; endif; ?>
        </ul>
        <div  class="row tab-content" style="margin-top: 1rem">
            <div class="tab-pane fade active in" id="language">
                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 mainmap" id = "oc_grade">
                    <!--ec图-->
                </div>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 mainmap" id = "oc_risk">
                    <!--ec图-->
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <a href="<?php echo U('Home/Analysis/issues_bro/info_id/'.$info_id);?>"><button class="rel reset-view">全部</button></a>	
                </div>
                <table  class = "table table-bordered" id="table" style="word-wrap:break-word;word-break:break-all;margin-top: 20px">
                </table>
            </div>

        </div>
    </div>
</div>

<script src="/Public/Js/bootstrap-table.js"></script>
<script src="/Public/Js/echarts.js"></script>
<script src="/Public/Js/dark.js"></script>
<script src="/Public/Js/issues_bro.js"></script>
</body>
</html>