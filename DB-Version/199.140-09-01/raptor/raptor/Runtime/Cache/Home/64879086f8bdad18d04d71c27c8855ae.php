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

<link rel="stylesheet" href="/Public/Css/codescan.css">
<div class="container">
    <div class="row ">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>您创建了<span style="color: #65b6fc"><?php echo ($scan_result['scan_num']); ?></span>个检测工程</span></h3></div>
        <div class=" col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8 codemap">
            <p class="right-bottom"><span></span></p>
            <p class="left-bottom"><span></span></p>


            <div class="mt">
                <h4>检测工程统计</h4>
                <span style="width: 14px;height: 14px;margin-left: -7px;top:40px"></span>
                <span style="width: 10px;height: 10px;margin-left: -5px;top:60px"></span>
                <span style="width: 6px;height: 6px;margin-left: -3px;top:78px"></span>
                <span style="width: 4px;height: 4px;margin-left: -2px;top:94px"></span>
            </div>

            <div class="lt sxzy">
                <div class="sxzy-text" style="right: 30px;border-bottom: 1px solid #3f7178">
                    <label style="top:-4px;background-color: #1d4149;">总共缺陷个数</label>
                    <strong style="color: #1fd931;top:30px"><?php echo ($scan_result['leak_num']); ?>个</strong>
                </div>
                <span></span>

            </div>
            <div class="rt sxzy">
                <div class="sxzy-text" style="left:30px;border-bottom: 1px solid #156f9e">
                    <label style="top:-4px;background-color: #156189">总共代码检测行数</label>
                    <strong style="color: #c1d91f;top:30px"><?php echo ($scan_result['code_line_num']); ?>行</strong>
                </div>
                <span></span>

            </div>
            <div class="lb sxzy">
                <div class="sxzy-text" style="right:30px;border-top:1px solid #156f9e">
                    <label style="bottom:-8px;background-color: #156189">监测语言种类</label>
                    <strong style="color: #c1d91f;top:-30px"><?php echo ($scan_result['leak_file_num']); ?>类</strong>
                </div>
                <span></span>

            </div>

            <div class="rb sxzy">
                <div class="sxzy-text" style="left:30px;border-top:1px solid #1d4149">
                    <label style="bottom:-8px;background-color: #1d4149;">发现缺陷种类</label>
                    <strong style="color: #1fd931;top:-30px"><?php echo ($scan_result['leak_defect_num']); ?>种</strong>
                </div>
                <span></span>

            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h3>开始扫描---->请上传一个<span style="color: #14851f">zip</span>压缩包</span></h3>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12  uploading">
            <div class="file-container">


                <form action="/raptor/upload" method="POST" enctype="multipart/form-data" id="postForm"  onsubmit="return false" >
                    <input type="hidden" name="user_id" value="<?php echo ($_SESSION['user_id']); ?>">
                    <span class="task-name">任务名称</span>
                    <input type="text" placeholder="任务名称" id="scan_name" name="scan_name" class="task-input" maxlength="20">
                    <div class="file-con">
                        <a class="file">选择文件
                            <input type="file" name="file" required="required" multiple="" class="form-control" id="f js-upload-files" accept="aplication/zip">
                        </a>
                        <p class="showfilemessage"></p>
                    </div>
                    <button type="submit" class="sub-button btn btn-sm btn-primary form-inline" id="js-upload-submit">上传扫描</button>
                </form>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bar-con">
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" >

                </div>
            </div>
            <p class="percent" style="color: #8b8b8b;font-size: 1.2rem">0% </p>
        </div>
    </div>
</div>
<script src="/Public/Js/scan.js"></script>
</body>
</html>