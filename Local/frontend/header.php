<?php
    @session_start();
    include 'mysql_config.php';
    $mysql_str = "select id,diction from {$tb_prefix}_user where user_name = ?";
    $query = $pdo->prepare($mysql_str);
    $query->execute(array($_SESSION['user_name']));
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $diction = $row['diction'];



    $session_id = $_COOKIE['PHPSESSID'];
    $u_name = $_SESSION['user_name'];

    $mysql_str = "select session_id from obsec_user where user_name = ?";
    $query = $pdo->prepare($mysql_str);
    $query->execute(array($u_name));
    $login_result = $query->fetch(PDO::FETCH_ASSOC);
 
    if($login_result['session_id'] != $session_id){
        echo "
        <script> alert('登录账户已被其他用户登录，如不是本人操作，请及时修改密码！'); 
            window.location.href='login.html';
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
            <a class="navbar-brand" href=""><img src="./images/logo1.png" alt="" title="匠迪云安全"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" >

            <ul class="nav navbar-nav ">
                <li><a href="scan.php" class="navA scan" >代码扫描</a></li>
                <li><a href="issues.php" class="navA issues" >代码分析</a></li>
                <!--<li><a href="proman.html">工程管理</a></li>-->
                <li><a href="history.php" class="navA history">工程管理</a></li>
		<?php if($diction == 1){?>
                <li><a href="contact.php" class="navA contact">系统管理</a></li>
		<?php }?>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a>您好，<?php echo $_SESSION['user_name'];?></a></li>
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
        }else if(file == '/issues.php' || file =='/online.php' || file == '/issues_bro.php'){
            $('.issues').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/history.php' || file == '/task.php'){
            $('.history').css('border-bottom','4px solid #65b6fc');
        }else if(file =='/contact.php' ||file == '/sys-tool.php'||file == '/sys-jour.php' ||file == '/sys-equipment.php' ||file == '/message.php' ||file == '/cpu.php' ||file == '/harddisk.php' ||file == '/close.php'){
            $('.contact').css('border-bottom','4px solid #65b6fc');
	    }
    });
</script>
