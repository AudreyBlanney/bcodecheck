<?php 
    @session_start();
    include "mysql_config.php";
	include "secure_filtering.php";

    $session_id = $_COOKIE['PHPSESSID'];
    $u_name = $_SESSION['user_name'];

    $mysql_str = "select session_id from obsec_user where user_name = ?";
    $query = $pdo->prepare($mysql_str);
    $query->execute(array($u_name));
    $login_result = $query->fetch(PDO::FETCH_ASSOC);

    if($login_result['session_id'] && $login_result['session_id'] != $session_id){
        echo "
        <script> alert('登录账户已被其他用户登录，如不是本人操作，请及时修改密码！'); 
            window.location.href='login_not.php';
        </script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>匠迪云</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="shortcut icon" href="./images/favicon.ico"/>

    <link rel="stylesheet" href="./dist/css/bootstrap.css">
    <link rel="stylesheet" href="./dist/css/common.css">
    <link rel="stylesheet" href="./dist/css/header.css">

    <script src="./dist/js/jquery-3.1.1.min.js"></script>
    <script src="./dist/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img src="./images/logo1.png" alt="" title="匠迪云安全"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" >

            <ul class="nav navbar-nav ">
                <li><a href="index.php" class="navA index">首页</a></li>
                <li><a href="scan.php" class="navA scan">代码扫描</a></li>
                <li><a href="issues.php" class="navA issues" >代码分析</a></li>
                <!--<li><a href="proman.html">工程管理</a></li>-->
                <li><a href="history.php" class="navA history">工程管理</a></li>
               <!-- <li><a href="contact.php" class="navA contact">联系我们</a></li>-->
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown user_down"><a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true">您好，<?php echo $_SESSION['user_name'];?><span class="caret"></span></a>
                    <ul class="dropdown-menu user_menu">
                        <li class="usemes-li"><a href="userinfo.php" class="user_data"><span class="use usemes" ></span> 用户信息</a></li>
                        <li class="usepas-li"><a href="changepas.php" class="user_data"><span class="use usepas"></span> 修改密码</a></li>
                        <li><a href="mailto:hr@Obsec.net?Subject=代码扫描平台用户建议" class="user_data"><span class="use "></span> 用户建议</a></li>
                    </ul>
                </li>
                <li><a href="logout.php" class="navA"><span class="glyphicon glyphicon-log-out"></span>注销</a></li>
            </ul>
        </div>
    </div>
</nav>



<script>
    $(function(){
        var file = "<?php echo  $_SERVER['PHP_SELF']?>";
        if(file == '/scan.php'){
            $('.scan').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/issues.php'){
            $('.issues').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/history.php'){
            $('.history').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/task.php'){
            $('.history').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/contact.php'){
            $('.contact').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/issues_bro.php'){
            $('.issues').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/online.php'){
            $('.issues').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/userinfo.php'){
            $('.usemes').css('background-position','0 -30px');
            $('.usemes-li a').css('color','#145876');
        }else if(file == '/changepas.php'){
            $('.usepas').css('background-position','-40px -30px');
            $('.usepas-li a').css('color','#145876');
        }else if(file == '/index.php'){
	    $('nav').addClass('navbar-fixed-top');
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
