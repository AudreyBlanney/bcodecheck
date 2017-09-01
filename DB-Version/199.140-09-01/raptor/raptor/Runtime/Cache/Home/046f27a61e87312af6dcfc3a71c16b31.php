<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
<link rel="stylesheet" href="/Public/Css/product.css">
<div class="container">
    <div class="row main">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12  subhead" >
          <h2 class="sta-h2">用户权益</h2>
        </div>
        <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 col-lg-offset-1 col-lg-10">
          <div class="fre"></div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 subhead">
            <h2 class="sta-h2">产品硬件介绍</h2>
            <p>匠迪根据不同用户应用需求，目前提供便携式BCodeCheck审计盒，一体式BCodeCheck审计机，机
            架式BCodeCheck服务器三类产品。</p>
        </div>


        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4 " >
            <div class="part3 part0">
                <div class="map-bg map-bg0"></div>
                <h3>一体式</h3>
                <p>自带显示器，携带方便，使用便捷，适合频繁出差、去客户现场的客户。</p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4 ">
            <div class="part3 part1">
                <div class="map-bg map-bg1"></div>
                <h3>便携式</h3>
                <p>公共工具式体验，谁有需求，谁使用 ，适合频繁更换代码审计服务地点的企业。</p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4" >
            <div class="part3 part2">
                <div class="map-bg map-bg2"></div>
                <h3>机架式</h3>
                <p>标准大小，上架使用，适合开发类和新需求、新平台上线频繁的客户。</p>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 step3" >
            <div class="use-con">
                <div class="use-text">
                   <p>设备使用简介：</p>
                    <p>操作简单，三步完成，一键扫描。
                        <br> 1.上传代码压缩包or配置代码管理服务器（svn、git等）链接。
                        <br> 2.自动分类、分析审查。
                        <br> 3.生成多维度图表、专业化报告。
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 subhead step6">
            <h2 class="sta-h2">代码审计服务</h2>
            <p>
                匠迪代码审计技术团队目前由10余名拥有5年以上代码审计经验的专职代码安全审计专家组成。
               为企业提供系统源码进行安全审查和评估，全面的发现源代码中的安全问题。
                根据代码审查结果评估其危害性并提出相应的整改建议，协助系统开发人员对源代码进行修改，
               达到增强业务系统自身抵抗力，保障业务系统安全运行的目标。
            </p>
            <h2 class="sta-h2">我们的依据</h2>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12  step6">
            <div class="part6">
                <div class="part6-con" style="float:left">
                    <div class="part6-conbg0"></div>
                    <p>CVE(Common Vulnerabilities & Exposures)公共漏洞字典表</p>
                </div>
                <div class="part6-con mid-4 part6Even">
                   <div class="part6-conbg1"></div>
                   <p>CWE（Common Weakness Enumeration）通用弱点枚举</p>
                </div>
                <div class="part6-con mid-4 part6Odd">
                    <div class="part6-conbg2"></div>
                    <p>OWASP Top10 <br>漏洞</p>
                </div>
                <div class="part6-con mid-4 part6Even">
                    <div class="part6-conbg3"></div>
                    <p>技术社区、设备、软件厂商公布的漏洞收集整理</p>
                </div>

                <div class="part6-con mid-4 part6Odd  part6_45">
                    <div class="part6-conbg4"></div>
                    <p>专业的代码审查设备BCodeCheck</p>
                </div>

                <div class="part6-con part6_45" style="float:right">
                     <div class="part6-conbg5"></div>
                     <p>经验丰富的代码审查专家</p>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 subhead  step11" >
          <h2 class="sta-h2">服务流程</h2>
          <div class="part11-con">

              <div class="sd sd0"></div>

              <div class="hd hdT hd0"></div>
              <div class="tp tpT tp0"></div>

              <div class="hd hdT hd1"></div>
              <div class="tp tpT tp1"></div>

              <div class="hd hdT hd2"></div>
              <div class="tp tpT tp2"></div>

              <div class="hd hdT hd3"></div>
              <div class="tp tpT tp3"></div>

              <div class="hd hdT hd4"></div>
              <div class="sc sc0"></div>

              <div class="hc hc0"></div>
              <div class="tp tpM tp4"></div>

              <div class="hd hdM hd5"></div>
              <div class="tp tpM tp5"></div>

              <div class="hd hdM hd6"></div>
              <div class="tp tpM tp6"></div>

              <div class="hc hc1"></div>
              <div class="sc sc1"></div>

              <div class="hd hdB hd7"></div>
              <div class="tp tpB tp7"></div>

              <div class="hd hdB hd8"></div>
              <div class="tp tpB tp8"></div>

              <div class="hd hdB hd9"></div>
              <div class="tp tpB tp9"></div>

              <div class="hd hdB hd10"></div>
              <div class="tp tpB tp11"></div>

              <div class="hd hdB hd11"></div>
              <div class="sd sd1"></div>
          </div>
        </div>
    </div>
</div>

<footer class="footer-con" >
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
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=vDR0scA5MDOrnxj556GHUCGHD0zbmk5B&s=1"></script>
<script src="/Public/Js/product.js"></script>
</body>
</html>