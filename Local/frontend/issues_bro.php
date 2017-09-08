<?php
include 'session.php';
include 'header.php';
include "history_data.php";
//记录下载文件数据
if(!empty($_POST['download_type'])){
	$res_his = history_data($_SESSION['user_name'],'分览视图'.$_POST['download_type'].'文件导出','成功',1,date('Y-m-d H:i:s'));
}

if (!empty($_SESSION['current_scan_report'])) {
    if (file_exists($_SESSION['current_scan_report'])) {
        $data = json_decode(file_get_contents($_SESSION['current_scan_report']), true);
    } else {
        $_SESSION['current_scan_report'] = '';
    }
} else {
    error_log("[ERROR] session: current_scan_report is null.");
}

$chart_codetypes_metrics = array();//代码分类
$chart_vulntype_metrics = array(); //风险分类
$chart_severity_metrics = Array();//程度分类
/*风险代码视图种类*/
if(!empty($data)) {
    $fe_array = array();
    $vu_array = array();
    $se_array = array();
    foreach ($data['warnings'] as $key => $value) {
        if (array_key_exists('file', $value)) {
            array_push($fe_array, strtolower(trim(substr(strrchr($value['file'], '.'), 1))));
            $chart_codetypes_metrics = array_count_values($fe_array);
        }

        array_push($se_array,$value['severity']);
        $chart_severity_metrics = array_count_values($se_array);

        array_push($vu_array,$value['warning_type']);
        $chart_vulntype_metrics = array_count_values($vu_array);
    }
}
//数据整理
$up_data = array();
foreach($chart_vulntype_metrics as $k => $v){
    foreach($data['warnings'] as $data_key => &$data_value){
        if($k == $data_value['warning_type']){
            $up_data[$k]['content'][] = $data_value;
        }
    }
}
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
//审计总数，排序整理
unset($up_data[0]);
$sj_array = array();
foreach($up_data as $up_key => &$up_value){
    $up_value['sj_num'] = 0;
    $sj_array = array();
    foreach($up_value['content'] as $uv_key => &$uv_value){
        if($severity_type != 5){
            if($severity_type != $uv_value['severity']){
                unset($up_value['content'][$uv_key]);
            }else{
                array_push($sj_array,!empty($uv_value['type']) ? $uv_value['type'] : 0);
                $up_value['sj_num'] += !empty($uv_value['type']) ? $uv_value['type'] : 0; //获取审计总数
            }
        }else{
            array_push($sj_array,!empty($uv_value['type']) ? $uv_value['type'] : 0);
            $up_value['sj_num'] += !empty($uv_value['type']) ? $uv_value['type'] : 0; //获取审计总数
        }
    }
    if(empty($up_value['content'])){
        unset($up_data[$up_key]);
    }
    @array_multisort($sj_array, SORT_ASC, $up_value['content']); //按审计排序
}

//获取报警数
$security = explode('(',$data['scan_info']['security_warnings']);
$chart_severity_metrics['zg'] = $security[0]; //获取报警总数
?>
<link rel="stylesheet" href="./dist/css/caf.css">
<link rel="stylesheet" href="./dist/css/jeuic.css">
<link rel="stylesheet" href="./dist/css/table.css">

<div class="container">
    <div class="row rowpiece">
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" >
           <a href="issues.php" >总览视图</a>
        </div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead">
           <a href="issues_bro.php" style=" background-color:rgba(34,41,48,.6);box-shadow: 2px 2px 3px #000;">分览视图</a>
        </div>
        <div class = "col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead">
            <a href="online.php">在线审计</a></div>
    </div>


    <div class="row rowpiece type_language">
        <ul  class="nav nav-tabs" id="language_title" style="border-bottom: 1px solid #262d37">
        <?php foreach($chart_codetypes_metrics as $key => $value){?>
            <li class="subheadnext btn-gray btn-codetype">
                <a href="#language"  data-toggle="tab">
                    <?php echo $key; ?>
                </a>
            </li>
        <?php }?>

        </ul>


        <div  class="row tab-content" style="margin-top: 1rem">
        
            <div class="tab-pane fade active in" id="language">

                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 mainmap" id = "oc_grade">
                    <!--ec图-->
                </div>
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 mainmap" id = "oc_risk">
                    <!--ec图-->
                </div>
                


                <div id="toolbar">
                    <select class="form-control">
                       <option value="all" disabled selected hidden>请选择导出范围</option> 
                       <option value="all">导出全部</option>
                       <option value="selected">导出勾选</option>
                    </select>
                </div>
                <table id = "table" class="table table-bordered" style="word-wrap:break-word;word-break:break-all;margin-top: 20px" 
                        data-maintain-selected="true"
                        data-show-refresh="true"
                        data-search="true"
                        data-advanced-search="true"
                        data-id-table="advancedTable"
                        data-pagination="true"
                        data-click-to-select="true"
                        data-show-toggle="true"
                        data-show-columns="true"
                        data-toolbar="#toolbar"
                        data-show-export="true">
                    
                    <!--表格-->
                </table>
            </div>

        </div>
    </div>
</div>

<script src="./dist/js/bootstrap-table.js"></script>
<script src="./dist/js/echarts.js"></script>
<script src="./dist/js/dark.js"></script>

<script src="./dist/js/bootstrap-table-export.js"></script>
<script src="./dist/js/tableExport.js"></script>
<script src="./dist/js/ga.js"></script>
<script src="./dist/js/issues_bro.js"></script>
<!--文件下载 -->
<script>
   $("body").on('click','.dropdown-menu>li',function(){
	   var download_type = $(this).attr('data-type'); //获取下载文件类型
	   $.ajax({
		   url: 'issues_bro.php',
		   type: 'POST',
		   data: {'download_type':download_type},
		   success:function(data){}
		});
   })
</script>	
</body>
</html>
