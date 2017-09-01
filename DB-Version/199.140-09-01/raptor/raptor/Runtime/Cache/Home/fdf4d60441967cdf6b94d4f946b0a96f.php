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

<link rel="stylesheet" href="/Public/Css/table.css">
<link rel="stylesheet" href="/Public/Css/proman.css">
<script src="/Public/Js/bootstrap-table.js"></script>

<div class="container">
    <div class="row rowpiece">
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" ><a href="<?php echo U('History/history');?>" style=" background-color:rgba(34,41,48,.6);">工程列表</a></div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" ><a href="<?php echo U('Task/task');?>" >任务列表</a></div>
    </div>
    <div class="row rowpiece">
        <table class="table table-bordered " id = "table" style="text-align: center;ord-wrap:break-word;word-break:break-all">

        </table>
    </div>

</div>
 <script src="/Public/Js/history.js"></script>
</body>
</html>