<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <title>匠迪云</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="shortcut icon" href="/Public/admin/Images/favicon.ico"/>

    <link rel="stylesheet" href="/Public/admin/Css/bootstrap.css">
    <link rel="stylesheet" href="/Public/admin/Css/common.css">

    <script src="/Public/admin/Js/jquery-3.1.1.min.js"></script>
    <script src="/Public/admin/Js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href=""><img src="/Public/admin/Images/logo.png" alt="" title="匠迪云安全"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" >
            <ul class="nav navbar-nav navbar-right">
                <?php if(!empty($_SESSION['admin_user_name']) ): ?><li class="dropdown user_down"><a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true">您好，<?php echo ($_SESSION['admin_user_name']); ?><span class="caret"></span></a>
                    <ul class="dropdown-menu user_menu" style="background:#252c34;">
                        <li style="background: #252c34"><a href="<?php echo U('Changepas/changepas');?>" class="user_data" style="color: #2188b6">修改密码</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo U('Logout/logout');?>" class="navA"><span class="glyphicon glyphicon-log-out"></span>注销</a></li><?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<script>
    $(function(){
        var file = "<?php echo $_SERVER['PHP_SELF']?>";
        if(file == '/scan.php'){
            $('.scan').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/issues.php'){
            $('.issues').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/history.php'){
            $('.history').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/contact.php'){
            $('.contact').css('border-bottom','4px solid #65b6fc');
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
<link rel="stylesheet" href="/Public/admin/Css/signin.css">
<div class="container">
    <div class="row main">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <h2 class="title">欢迎使用<br>匠迪代码安全审查后台系统</h2>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <form action= "" id="form" onsubmit="return false">
                <div class="xddw message">
                    <span>用户名：</span>
                    <input type="text" name="user_name" placeholder="请输入用户名" class="message-input">
                    <p class="warning"></p>
                </div>

                <div class="xddw message">
                    <span>密码：</span>
                    <input type="password" name="password" placeholder="请输入密码" class="message-input">
                    <p class="warning warning_err"></p>
                </div>

                <div class="xddw message">
                    <span>验证码：</span>
                    <input type="text" id="inputCode" placeholder="输入验证码" class="message-input" style="width: 45%">
                    <div class="code" id="checkCode" onclick="createCode()" ></div>
                    <p class="warning login_code"></p>
                </div>
                <div class="xddw message">
                    <button class="register" type="submit" type = "button">登&nbsp;&nbsp;陆</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/Public/admin/Js/jquery-3.1.1.min.js"></script>
<script src="/Public/admin/Js/bootstrap.min.js"></script>
<script src="/Public/admin/Js/login.js"></script>
</body>
</html>