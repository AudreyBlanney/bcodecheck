<?php 
	include "secure_filtering.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>匠迪云</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="匠迪云，代码安全，代码检测，匠迪技术">
    <meta name="description" content="匠迪云是一款线上安全服务平台，集产品安全服务展示，体验，交付等多种功能">
    <link rel="shortcut icon" href="images/favicon.ico"/>
    <link rel="stylesheet" href="./dist/css/bootstrap.css">
    <link rel="stylesheet" href="./dist/css/common.css">
    <script src="./dist/js/jquery-3.1.1.min.js"></script>
	<style type="text/css">
		#navbar .navbar-nav li a:hover{
			color:#65b6fc;
		}
	</style>
</head>
<body>
<nav class="navbar navbar-default  navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img src="images/logo1.png" alt="" title="匠迪云安全"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" >

            <ul class="nav navbar-nav">
                <li><a href="index.php" class="index">首页</a></li>
                <li><a href="index.php?type=second#second" class="second">产品介绍</a></li>
                <li><a href="product.php" class="product">产品服务</a></li>
                <li><a href="index.php?type=third#third" class="third">联系我们</a></li>
                <li><a href="login_not.php">免费检测</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="login_not.php" class="login_not">登陆</a></li>
                <li><a href="signin.php" class="signin">注册</a></li>
            </ul>

        </div>
    </div>
</nav>

<script>
	 $(function(){
        var file = "<?php echo  $_SERVER['PHP_SELF']?>";
        var type = "<?php echo !empty($_GET['type']) ? $_GET['type'] : ''?>";
        if(file == '/index.php' && type == ''){
            $('.index').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/product.php'){
            $('.product').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/login_not.php'){
            $('.login_not').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/signin.php'){
            $('.signin').css('border-bottom','4px solid #65b6fc');
        }else if(type == 'second' && file == '/index.php'){
			$('.second').css('border-bottom','4px solid #65b6fc');
        }else if(type == 'third' && file == '/index.php'){
        	$('.third').css('border-bottom','4px solid #65b6fc');
        }
    });
</script>