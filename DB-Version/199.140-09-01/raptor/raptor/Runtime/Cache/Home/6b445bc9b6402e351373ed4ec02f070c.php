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
            <a class="navbar-brand" href="index.php"><img src="/Public/Images/logo1.png" alt="" title="匠迪云安全"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" >

            <ul class="nav navbar-nav ">
                <li><a href="<?php echo U('Index/index');?>" class="navA index">首页</a></li>
                <li><a href="<?php echo U('Scan/scan');?>" class="navA scan">代码扫描</a></li>
                <li><a href="<?php echo U('CodeAnalysis/issues');?>" class="navA issues" >代码分析</a></li>
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
        var file = "<?php echo $_SERVER['PHP_SELF']?>";
        if(file == '/scan.php'){
            $('.scan').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/issues.php' || file == '/issues_bro.php' || file == '/online.php'){
            $('.issues').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/history.php' || file == '/task.php'){
            $('.history').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/contact.php'){
            $('.contact').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/userinfo.php'){
            $('.usemes').css('background-position','0 -30px');
            $('.usemes-li a').css('color','#145876');
        }else if(file == '/changepas.php'){
            $('.usepas').css('background-position','-40px -30px');
            $('.usepas-li a').css('color','#145876');
        }else if(file == '/index.php'){
			//$('nav').addClass('navbar-fixed-top');
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
<div class="container">
    <div class="row rowpiece">
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" ><a href="<?php echo U('CodeAnalysis/issues');?>" style=" background-color:rgba(34,41,48,.6);">总览视图</a></div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead"><a href="<?php echo U('CodeAnalysis/issues_bro');?>">分览视图</a></div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead"><a href="<?php echo U('CodeAnalysis/online');?>">在线审计</a></div>
    </div>
    <div class="row rowpiece">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mainmap" id = "mainmap0"></div>
    </div>
    <div class="row rowpiece">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mainmap" id = "mainmap1"></div>
    </div>
    <div class="row rowpiece">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mainmap" id = "mainmap2" style="min-height: 400px;"></div>
    </div>
</div>
<script src="/Public/Js/echarts.js"></script>
<script src="/Public/Js/dark.js"></script>
<script>
    //    EC图
    $(window).resize(function(){
        mainmap0.resize();
        mainmap1.resize();
        mainmap2.resize();
    })
    mainmap0 = echarts.init(document.getElementById('mainmap0'),'dark');
    mainmap1 = echarts.init(document.getElementById('mainmap1'),'dark');
    mainmap2 = echarts.init(document.getElementById('mainmap2'),'dark');
    option0 = {
        backgroundColor:'#2a323d',
        title : {
            text: '文件分类视图',

            x:'left'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        toolbox: {
            x: 'right',
            feature: {
                saveAsImage : {
                    show : true,
                    title : '保存为图片',
                    type : 'png',
                    lang : ['点击保存']
                }
            }
        },
        legend: {
            x : '60%',
            y : 'center',
            width:'20%',
            height:'100%',
            data:[ 'php','css','js','c']
        },

        calculable : true,
        series : [
            {
                name:'面积模式',
                type:'pie',
                radius : [30, 110],
                center : ['30%', '50%'],
                roseType : 'area',
                data:[ {value:55, name:'php'},
                {value:5, name:'css'},
                {value:15, name:'js'},
                {value:25, name:'c'}],
				itemStyle:{ 
					normal:{ 
						label:{ 
							show: true, 
							formatter: '{b} : {c} ({d}%)' 
						}, 
							labelLine :{show:true} 
					} 
	            }
            }
        ]
    };
    option1 = {
        backgroundColor:'#2a323d',
        title : {
            text: '程度分类视图',
            x:'left'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        toolbox: {
            x: 'right',
            feature: {
                saveAsImage : {
                    show : true,
                    title : '保存为图片',
                    type : 'png',
                    lang : ['点击保存']
                }
            }
        },
        legend: {
            x : '20%',
            y : 'center',
            width:'20%',
            height:'100%',
            data:['中','低','高']
        },

        calculable : true,
        series : [
            {
                name:'面积模式',
                type:'pie',
                radius : [30, 110],
                center : ['70%', '50%'],
                roseType : 'area',
                data:[
                {value:60, name:'中'},
                {value:15, name:'低'},
                {value:25, name:'高'}],
                
				itemStyle:{ 
					normal:{ 
						label:{ 
							show: true, 
							formatter: '{b} : {c} ({d}%)' 
						}, 
							labelLine :{show:true} 
					} 
	            }
            }
        ]
    };
    option2 = {
        backgroundColor:'#2a323d',
        title : {
            text: '风险分类视图',

            x:'left'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
	formatter:function(val){    
	    return (val.length > 21 ? (val.slice(0,21)+"...") : val ); 
			
	},
        toolbox: {
            x: 'right',
            feature: {
                saveAsImage : {
                    show : true,
                    title : '保存为图片',
                    type : 'png',
                    lang : ['点击保存']
                }
            }
        },
        legend: {
            orient: 'vertical',
            x : '60%',
            y : 'center',
            width:'20%',
            height:'98%',
            data:['敏感信息泄露','潜在的xss','自定义消息摘要有风险','在cookie的潜在敏感数据'
                ,'硬编码的电子邮件id'],
        },

        calculable : true,
        series : [
            {
                name:'面积模式',
                type:'pie',
                radius: ['30%', '70%'],
                center:['30%','50%'],
                roseType : 'area',
                avoidLabelOverlap: true,
                data:[
                    {value:6, name:'敏感信息泄露'},
                    {value:15, name:'潜在的xss'},
                    {value:25, name:'自定义消息摘要有风险'},
                    {value:35, name:'在cookie的潜在敏感数据'},
                    {value:25, name:'硬编码的电子邮件id'}]
            }
        ]
    };
    mainmap0.setOption(option0);
    mainmap1.setOption(option1);
    mainmap2.setOption(option2);
</script>
</body>
</html>