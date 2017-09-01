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
            <a class="navbar-brand" href="/Admin"><img src="/Public/admin/Images/logo.png" alt="" title="匠迪云安全"></a>
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
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 lrHeight left">
            <div class="left-bg">
                <!--p为占位-->
                <p style="height: 10px"></p>

                <div class="left-nav" style="color: #2188b6">
                    <span class="map-nav usemessage"></span>
                    <span class="messagename">用户管理</span>
                    <span class="rx" style="background-position:-260px -36px "></span>
                </div>
                <ul class="list" style="display: block;color: #2188b6">
                    <li class="list-li" url="<?php echo U('Usecreat/usecreat');?>">用户列表</li>
                </ul>

                <div class="left-nav">
                    <span class="map-nav runanalyze"></span>
                    <span class="messagename">运营分析</span>
                    <span class="rx"></span>
                </div>
                <ul class="list">
                    <li class="list-li" url="<?php echo U('Userview/userview');?>">用户视图</li>
                    <li class="list-li" url="<?php echo U('Useroper/useroper');?>">用户操作</li>
                    <li class="list-li" url="<?php echo U('Staticang/staticang');?>">统计分析</li>
                </ul>

            </div>
        </div>
        <div class=" col-lg-10 lrHeight right">
            <div class="iframe-con">

                <iframe src="<?php echo U('Usecreat/usecreat');?>" frameborder="0" class="iframe"></iframe>
            </div>
        </div>
    </div>
</div>
<script>
    //  左侧导航第一个
    var bol0 = true;
    $(".left-nav").eq(0).click(function(){
        if(bol0){
            $(this).css("color","#ffffff");
            $(".usemessage").css("background-position","2px 6px")
            $(".rx").eq(0).css("background-position"," -260px 6px");
            $(".list").eq(0).slideUp();
            bol0 = false;
        }else{
            $(this).css("color","#2188b6");
            $(".usemessage").css("background-position","2px -41px")
            $(".rx").eq(0).css("background-position"," -260px -36px");
            $(".list").eq(0).slideDown();
            bol0 = true;
        }
    })
    //  左侧导航第2个
    var bol1 = true;
    $(".left-nav").eq(1).click(function(){
        if(bol1){
            $(this).css("color","#2188b6");
            $(".runanalyze").css("background-position"," -46px -40px");
            $(".rx").eq(1).css("background-position"," -260px -36px");
            $(".list").eq(1).slideDown();
            bol1 = false;
        }else{
            $(this).css("color","#ffffff");
            $(".runanalyze").css("background-position"," -46px 7px");
            $(".rx").eq(1).css("background-position"," -260px 6px");
            $(".list").eq(1).slideUp();
            bol1 = true;
        }
    })
</script>
<script>
    //    导航对应的iframe
    var arr = ["iframe-usecreat.html","iframe-useview.html","iframe-useopera.html","iframe-stati-analy.html"];
    $(".list-li").each(function(i){
        $(this).click(function(){
            var url = $(this).attr('url');
            $(".list-li").css("color","#ffffff");
            $(this).css("color","#2188b6");
            // $(".iframe").attr("src",arr[i]);
            $(".iframe").attr("src",url);
        })
    })
</script>
</body>
</html>