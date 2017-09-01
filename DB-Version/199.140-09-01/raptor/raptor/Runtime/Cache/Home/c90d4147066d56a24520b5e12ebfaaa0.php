<?php if (!defined('THINK_PATH')) exit(); if(empty($_SESSION['user_name']) ): ?><!DOCTYPE html>
<html lang="en">
<head>
    <title>匠迪云</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="匠迪云，代码安全，代码检测，匠迪技术">
    <meta name="description" content="匠迪云是一款线上安全服务平台，集产品安全服务展示，体验，交付等多种功能">
    <link rel="shortcut icon" href="images/favicon.ico"/>
    <link rel="stylesheet" href="/Public/Css/bootstrap.css">
    <link rel="stylesheet" href="/Public/Css/common.css">
    <script src="/Public/Js/jquery-3.1.1.min.js"></script>
    <script src="/Public/Js/bootstrap.min.js"></script>
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
            <a class="navbar-brand" href="<?php echo U('index');?>"><img src="/Public/Images/logo1.png" alt="" title="匠迪云安全"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" >

            <ul class="nav navbar-nav">
                <li><a href="<?php echo U('Index/index');?>" class="index">首页</a></li>
                <li><a href="<?php echo U('Index/index?type=second#second');?>" class="second">产品介绍</a></li>
                <li><a href="<?php echo U('product/product');?>" class="product">产品服务</a></li>
                <li><a href="<?php echo U('Index/index?type=third#third');?>" class="third">联系我们</a></li>
                <li><a href="<?php echo U('Login/login');?>">免费检测</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo U('Login/login');?>" class="login_not">登陆</a></li>
                <li><a href="<?php echo U('Signin/signin');?>" class="signin">注册</a></li>
            </ul>

        </div>
    </div>
</nav>

<script>
	 $(function(){
        var file = "<?php echo ($_SERVER['PATH_INFO']); ?>";
        var type = "<?php echo ($_GET['type'] ? $_GET['type'] : ''); ?>";
        if(file == 'Index/index' && type == ''){
            $('.index').css('border-bottom','4px solid #65b6fc');
        }else if(file == 'product/product'){
            $('.product').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/login_not.html'){
            $('.login_not').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/signin.html'){
            $('.signin').css('border-bottom','4px solid #65b6fc');
        }else if(type == 'second' && file == 'Index/index/type/second'){
			$('.second').css('border-bottom','4px solid #65b6fc');
        }else if(type == 'third' && file == 'Index/index/type/third'){
        	$('.third').css('border-bottom','4px solid #65b6fc');
        }
    });
</script>
<?php else: ?>
    <!DOCTYPE html>
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
</script><?php endif; ?>

<link rel="stylesheet" href="/Public/Css/index1.css">
<!--第一屏-->
<div class="banner">
    <div class="container" style="height: inherit;position: relative;">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12  col-lg-12 bg_title"></div>
        </div>
        <?php if(empty($_SESSION['user_name']) ): ?><a href="<?php echo U('Login/login');?>"><button class="free-btn" style="color:#fff">免费检测</button></a>
        <?php else: ?>
            <a href="<?php echo U('Scan/scan');?>"><button class="free-btn" style="color:#fff">免费检测</button></a><?php endif; ?>
        <div class="row bg_num" id="second">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style = "margin-left: 15px">
                <div class="num_part3" style="margin-left: 5%">
                    <span>检测代码项目数量</span>
                    <span style="color: #ff3652" id="numCon0"></span>
                </div>
                <div class=" num_part3">
                    <span>累计检测代码行数</span>
                    <span style="color: #edae3e" id="numCon1"></span>
                </div>
                <div class="num_part3">
                    <span>累计发现缺陷个数</span>
                    <span style="color: #13ac4c" id="numCon2"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container" >
    <div class = "row">
        <h2 class = "title_h2">BCodeCheck是什么？</h2>
        <p class="title_p">BCodeCheck从编码安全的角度出发，自动快速检查源代码中的缺点和敏感信息，分析并定位这些问题将会引发的安全漏洞隐患。
            并提供代码修订措施和建议，帮助企业和个人高效的解决软件产品所带来的先天缺陷。</p>
        <div class = "col-xs-12 col-sm-6 col-md-6 col-lg-6 picCon">
            <div class="pic-leftCon">
                <div class="pic-left"></div>
            </div>
        </div>

        <div class = "col-xs-12 col-sm-12 col-md-6 col-lg-6 bcc">
            <h4 class = "title_h4">什么时候能用到BCodeCheck？</h4>
            <ul  class="bbc-text" >
                <li> <span></span> &nbsp;&nbsp;如何知道业务系统在开发的过程中使用了哪些高危函数？</li>
                <li> <span></span> &nbsp;&nbsp;如何知道不同编程语言会给业务系统埋下哪些安全隐患？</li>
                <li> <span></span> &nbsp;&nbsp;如何从本质上解决掉隐藏在业务系统中的潜在漏洞？</li>
                <li> <span></span> &nbsp;&nbsp;如何有效的从安全的角度提升代码编写质量？</li>
                <li> <span></span> &nbsp;&nbsp;如何有效评估软件开发商交付的软件产品安全可靠？</li>
                <li> <span></span> &nbsp;&nbsp;如何高效完成信息系统新增上线功能代码安全评估？</li>
                <li> <span></span> &nbsp;&nbsp;......</li>
            </ul>
        </div>
    </div>
    <h2 class = "title_h2">BCodeCheck具备哪些优势？</h2>
    <div class = "row">
        <div class = "col-xs-12 col-sm-6 col-md-3 col-lg-3">
            <div class="advant4 advant0">
                <div class="map4 map0"></div>
                <p>检查源码种类全，对外开放力度大，目前为国内最多，包含Java、c/c++、PHP等13种编程和脚本语言</p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 ">
            <div class="advant4 advant1">
                <div class="map4 map1"></div>
                <p>自动化程度高，整包上传，一键扫描，界面友好，可视化效果好</p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 ">
            <div class="advant4 advant2">
                <div class="map4 map2"></div>
                <p>速度快，定位准、漏报和误报率低、修改意见详细，参考价值大</p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 ">
            <div class="advant4 advant3">
                <div class="map4 map3"></div>
                <p>全面的代码先天缺陷发现能力，支持OWSAP、CWE和CVE等定义的漏洞项检查，优秀的专家团队支撑</p>
            </div>
        </div>
    </div>
    <h2 class = "title_h2">基于客户需求的代码审计解决方案</h2>
    <p class="title_p fa">精准定制，根据客户的不同需求与应用场景，匠迪将提供线上与线下相结合的方式。做到客户有需求，匠迪有方案；以成本和效果为出发点。</p>
    <div class = "row">
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
            <div class="h4-3 ">
                <h3 class="h4-3left">线上<br>自主扫描 ，远程协助 <br> 远程技术答疑支持 <br>人工修正审计报告服务</h3>
            </div>
        </div>
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
            <div class = "pic-right3Con">
                <div class="pic-right3"></div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-7 col-md-7  col-lg-7" >
            <div class = "pic-left3Con">
                <div class="pic-left3"></div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-5  col-md-5 col-lg-5 " >
            <div class="h4-3">
                <h3 class=" h4-3right" >线下<br>人员培训，针对辅导<br> 提供便携式与机架式不同型号产品<br>提供专业审计人员到场服务</h3>
            </div>
        </div>
    </div>
</div>
<footer class="footer-con" id="third">
    <div class = "map-con" id = "footer_add"></div>
    <div class="footer-meng">
        <div class="footer-message">
            <div class="footer-top">
                <div class="fmc footer-message-left">
                    <div class="mess-email">邮箱：hr@Obsec.net </div>
                    <div class="mess-tel">电话：400-690-6007 </div>
                </div>
                <div class="fmc footer-message-mid">
                    <div class="ser-num"></div>
                    <span>微信服务号</span>
                </div>
                <div class="fmc footer-message-right">
                    <div class="off-num"></div>
                    <span>微信公众号</span>
                </div>
            </div>
            <address>北京市昌平区黄平路19号龙旗广场D座610</address>
            <br><hr>
            <p style="text-align: center">网站声明 | 法律版权 | 京ICP备案：17000897号 Copyright © 2017 OBSeC Inc. 北京匠迪</p>
        </div>
    </div>
</footer>

<script type="text/javascript" src="/Public/Js/countUp.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=vDR0scA5MDOrnxj556GHUCGHD0zbmk5B&s=1"></script>
<script type="text/javascript" src="/Public/Js/index.js"></script>

</body>
</html>