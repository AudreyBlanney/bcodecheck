<?php
//http://127.0.0.1/issues_audit.php?array_key=可能不被信任的用户输入&array_id=0
include 'session.php';
include 'issues_config.php';
header("Content-type:text/html; charset=utf-8");
//筛选指定数据
$data_type = !empty($_GET['data_type']) ? $_GET['data_type'] : 5; //1忽略 2高 3中 4低 5所有
switch($data_type){
    case 1;
        $severity_type = '忽略';
        break;
    case 2:
        $severity_type = '高';
        break;
    case 3;
        $severity_type = '中';
        break;
    case 4;
        $severity_type = '低';
        break;
    case 5;
        $severity_type = 5;
        break;
}
//获取审计数据
$fu_key = !empty($_GET['fu_key']) ? $_GET['fu_key'] : ''; //获取数据父级
$key_id = $_GET['key_id'] != null ? $_GET['key_id'] : ''; //获取父级子级

if($fu_key && $key_id != null){
    if (!empty($_SESSION['current_scan_report'])) {
        if (file_exists($_SESSION['current_scan_report'])) {
            $data = json_decode(file_get_contents($_SESSION['current_scan_report']), true);
        } else {
            $_SESSION['current_scan_report'] = '';
        }
    } else {
        error_log("[ERROR] session: current_scan_report is null.");
    }

    $chart_vulntype_metrics = array(); //风险分类
    /*风险代码视图种类*/
    if(!empty($data)) {
        $fe_array = array();
        $vu_array = array();
        $se_array = array();
        foreach ($data['warnings'] as $key => $value) {
            array_push($vu_array,$value['warning_type']);
            $chart_vulntype_metrics = array_count_values($vu_array);
        }
    }
//数据整理
    $up_data = array();
    foreach($chart_vulntype_metrics as $k => $v){
        foreach($data['warnings'] as $data_key => $data_value){
            if($k == $data_value['warning_type']){
                $up_data[$k][] = $data_value;
            }
        }
    }

    unset($up_data[0]);
    $sj_array = array();
    foreach($up_data as $up_key => &$up_value){
        foreach($up_value as $uv_key => &$uv_value){
            foreach($issues_array as $iss_key => $iss_value){
                if($iss_key == $up_key){
                    $uv_value['link'] = $iss_value['link'];
                }
            }
            if($severity_type != 5){
                if($severity_type != $uv_value['severity']){
                    unset($up_value[$uv_key]);
                }else{
                    array_push($sj_array,!empty($uv_value['type']) ? $uv_value['type'] : 0);
                }
            }else{
                array_push($sj_array,!empty($uv_value['type']) ? $uv_value['type'] : 0);
            }
        }
        if(empty($up_value)){
            unset($up_data[$up_key]);
        }
        @array_multisort($sj_array, SORT_ASC, $up_value); //按时间倒序排序
    }

    $audit = array(
        'track' => array( //跟踪路径
            'file' => $up_data[$fu_key][$key_id]['file'],
            'line' => $up_data[$fu_key][$key_id]['line'],
            'code' => $up_data[$fu_key][$key_id]['code'],
        ),
        'warning_ms' =>  $up_data[$fu_key][$key_id]['message'], //缺陷描述
        'message' =>  $up_data[$fu_key][$key_id]['message'], //修复建议
        'link' =>  $up_data[$fu_key][$key_id]['link'], //参考信息
        'severity' =>  $up_data[$fu_key][$key_id]['severity'], //警告等级
        'describe' =>  !empty($up_data[$fu_key][$key_id]['describe']) ? $up_data[$fu_key][$key_id]['describe'] : '', //警告等级
    );

    //显示文件代码数据整理
        $report_file = explode('/',$_SESSION['current_scan_report']);
        $report_index = count($report_file) - 2;
        $file_dir = $report_file[$report_index];//获取问题代码文件 文件目录
        $zip_file = '/var/raptor/uploads/'.$file_dir.'.zip';
        $file_rout = '/var/raptor/uploads/'.$file_dir.'/'.$up_data[$fu_key][$key_id]['file']; //获取问题代码文件绝对路径
        if(!is_dir("/var/raprot/uploads/$file_dir")){
            $zip=new ZipArchive();
            if ($zip->open($zip_file) === TRUE) {
                $zip->extractTo('/var/raptor/uploads/'.$file_dir);
            }else{
                echo ("无法获取数据");
            }
        }
        $content = is_file($file_rout) ? file_get_contents($file_rout) : '';
        $content_array = $content ? explode(PHP_EOL,htmlspecialchars($content)) : ''; //\t数据分割整理数据
        $data_json = json_encode($audit['track']); //获取表格json数据

}

//缺陷审计form提交数据处理
$file = !empty($_POST['file']) ? $_POST['file'] : ''; //form提交file文件名称
$line = !empty($_POST['line']) ? $_POST['line'] : ''; //form提交line的行号数据
$warning_type = !empty($_POST['warning_type']) ? $_POST['warning_type'] : ''; //form提交的那个数据类型
$severity = !empty($_POST['grade']) ? $_POST['grade'] : '';//获取审计类型
$describe = !empty($_POST['describe']) ? $_POST['describe'] : '';//获取审计的描述消息
$type = 0;//是否审计 1：位审计
if($file && $line && $warning_type){
//根据文件名称和错误行号来更新json数据
    foreach($data['warnings'] as $da_key => &$da_value){
        if($da_value['file'] == $file && $da_value['line'] == $line && $da_value['warning_type'] == $warning_type){
            $da_value['severity'] = $severity;
            $da_value['describe'] = $describe;
            $da_value['type'] = 1;
        }
    }

    $new_json = json_encode($data); //获取新的json数据
    //写入文件
	exec("sudo chmod -R 777 /var/raptor/scan_results/");
    $myfile = fopen($_SESSION['current_scan_report'], "w") or die("没有此文件!");
    $sec = fwrite($myfile, $new_json);
    if($sec){
		if(!empty($_GET['data_type'])){
			$data_type = "?data_type={$_GET['data_type']}";
		}else{
			$data_type = '';
		}
        echo '<script>top.location="online.php'.$data_type.'"</script>';
    }
    fclose($myfile);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="./dist/css/bootstrap.css">
    <link rel="stylesheet" href="./dist/css/caf.css">
    <link rel="stylesheet" href="./dist/css/table.css">
    <link rel="stylesheet" href="./dist/css/messshow.css">

</head>
<body>


<div  class="tab-content tab-content-top">
    <div class="scroll-container">
        <div class="scroll">

            <div class="tab-pane fade in active changecode" id="tabmessage">
			<?php if(!empty($content_array)){?>
                <table>
                    <?php foreach($content_array as $key => $value){?>
                        <tr style="font-size: 13px" id="<?php echo $key+1 ?>">
                            <td>
                                <?php echo $key+1 ?>
                            </td>
                            <td>
                                <?php echo '<pre id="pre">';echo $value ?>
                            </td>
                        </tr>
                    <?php }?>
                </table>
			<?php }?>
            </div>
        </div>
    </div>
</div>
<!-------------------------下面结构------------------------->
<ul class="nav nav-tabs tabsT" style="border:1px solid #0978b7">


    
    <li class="active"><a href="#tab2" data-toggle="tab" class="tab-titles">缺陷描述</a></li>
    <li><a href="#tab3" data-toggle="tab" class="tab-titles">修改建议</a></li>
    <li><a href="#tab4" data-toggle="tab" class="tab-titles">缺陷审计</a></li>
	<li><a href="#tab1" data-toggle="tab" class="tab-titles">跟踪路径</a></li>


</ul>
<div  class="tab-content tab-content-bottom ">



    <div class="tab-pane fade in active changedescribe scroll-right" id="tab2">
        <p class="part4-text">
            <?php echo $audit['warning_ms'];?>
        </p>
    </div>
    <div class="tab-pane fade changesuggest  scroll-right" id="tab3">
        <p class="part4-text">
            <?php echo $audit['link'] ?>
        </p>
    </div>
    <div class="tab-pane fade changeaudit  scroll-right" id="tab4">

        <form action="" class="audit-form" method="post">
            <div class="audit-relative">
                <span class="audit-title">告警等级</span>
                <div class="level-posi">
                    <input name="file" type="hidden" value="<?php echo $audit['track']['file'] ?>">
                    <input name="line" type="hidden" value="<?php echo $audit['track']['line'] ?>">
                    <input name="warning_type" type="hidden" value="<?php echo $fu_key ?>">
                    <input type="radio" name="grade" id="height" value="高" <?php if($audit['severity'] == '高'){?> checked <?php }?> >
                    <label for="height"></label>
                    <span class="grade-cla">高</span>

                    <input type="radio" name="grade" id="middle" value="中" <?php if($audit['severity'] == '中'){?> checked <?php }?>>
                    <label for="middle" style="left: 48px;"></label>
                    <span class="grade-cla">中</span>

                    <input type="radio" name="grade" id="low" value="低" <?php if($audit['severity'] == '低'){?> checked <?php }?>>
                    <label for="low" style="left: 94px;"></label>
                    <span class="grade-cla">低</span>

                    <input type="radio" name="grade" id="ign" value="忽略" <?php if($audit['severity'] == '忽略'){?> checked <?php }?>>
                    <label for="ign" style="left: 138px;"></label>
                    <span class="grade-cla">忽略</span>
                </div>
            </div>
            <div class="audit-relative-bott">
                <span class="audit-title">备注</span>
                <textarea class="text-area" name="describe"><?php echo $audit['describe'] ?></textarea>
            </div>
            <button type="submit" class="sub">提交</button>
        </form>
    </div>
    <div class="tab-pane fade in changepath  scroll-right" id="tab1">


        <!----------------推入跟踪路径   表格-------------------->
        <table id = "tableBottom" class="table table-bordered" style="margin-top: 0">

        </table>

    </div>


</div>
<script src = "./dist/js/jquery-3.1.1.min.js"></script>
<script src = "./dist/js/bootstrap.min.js"></script>
<script src="./dist/js/bootstrap-table.js"></script>
<script>
    $(function(){
        //警告行数显示
        var line_num = "<?php echo $audit['track']['line'] ?>";
        $('#'+line_num+' #pre').css('background','#3a80a7');
        var top = $('#'+line_num).offset().top;
        $('.scroll').scrollTop(top-100);
    });

    $('#tableBottom').bootstrapTable({
        columns: [
            {
                field: 'file',
                title: '文件名',
                align:'center',
                valign:'middle'

            }, {
                field: 'line',
                title: '行号',
                align:'center',
                valign:'middle'
            },{
                field: 'code',
                title: '代码段',
                align:'center',
                valign:'middle'
            }],
        data:[<?php echo $data_json ?>]


    });
</script>
</body>
</html>
