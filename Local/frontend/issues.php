<?php
include 'session.php';
include 'header.php';
$GLOBALS['report_base']  = '/var/raptor/scan_results/';
//判断是否有上传文件
$dir_bas = @opendir($GLOBALS['report_base']);
while($file_array[] = @readdir($dir_bas));
foreach($file_array as $dir_key => $dir_value){
    if($dir_value == '.' || $dir_value == '..' || $dir_value == ''){
        unset($file_array[$dir_key]);
    }
}
if(!$file_array || empty($file_array) || !in_array($_SESSION['user_name'],$file_array)){
    exit;
}
//判断用户是否上传过文件
$user_dir = @opendir($GLOBALS['report_base'].$_SESSION['user_name'].'/');
while($user_file_array[] = @readdir($user_dir));
foreach($user_file_array as $usfile_key => $usfile_value){
    if($usfile_value == '.' || $usfile_value == '..' || $usfile_value == ''){
        unset($user_file_array[$usfile_key]);
    }
}
if(!$user_file_array || empty($user_file_array)){
    exit;
}
$new_user = true;
function previous_scans($user_name){
    $root = $GLOBALS['report_base'];
	$scans = array();
	if(is_dir($root.$user_name)){
		foreach(scandir($root.$user_name) as $us_path){
			if($us_path != '.' && $us_path != '..' && is_dir($root.$user_name.'/'.$us_path)){
				foreach(scandir($root.$user_name.'/'.$us_path) as $path_value){
					if($path_value !== '.' && $path_value !== '..'){
						$scans[$us_path] = $root.$user_name.'/'.$us_path.'/'.$path_value;
					}
				}
			}
		}
	}

    $final_result = array(array());
    foreach ($scans as $scan_name => $path) {
        foreach (scandir($path) as $report) {
            if ($report[0] !== '.') {
                $final_result[$scan_name]['report_path'] = $path . '/' . $report;
                $epoch = (int)explode('.json', $report)[0];
                $dt = new DateTime("@$epoch");
                $final_result[$scan_name]['scan_date'] = $dt->format('Y-m-d H:i:s');
            }
        }
    }
    unset($final_result[0]);
    //数据处理
    $sx_array = array();
    foreach($final_result as $key => $value){
        array_push($sx_array,@$value['scan_date']); //获取时间
    }
    array_multisort($sx_array, SORT_DESC, $final_result); //按时间倒序排序
    return $final_result;
}
//加载获取默认数据
$final_result = previous_scans($_SESSION['user_name']);

$default_array = array_shift($final_result);

$default_array = $default_array ? $default_array['report_path'] : '';

$_SESSION['current_scan_report'] = !empty($_SESSION['current_scan_report']) ? $_SESSION['current_scan_report'] : $default_array;
$cr_data = $_SESSION['current_scan_report'];

$chart_codetypes_metrics    = Array();//代码分类
$chart_severity_metrics = Array();//程度分类
$chart_vulntype_metrics = Array();//风险分类

$data = !empty($cr_data) ? @json_decode(file_get_contents($cr_data), true) : ' ';
if($data){
    $se_array = array();
    $vu_array = array();
    for($i=0; $i < count($data['warnings']); $i++) {
        @$rule_id = !empty($data['warnings'][$i]['warning_code']) ? $data['warnings'][$i]['warning_code'] : '-';
        array_push($se_array,$data['warnings'][$i]['severity']);
        $chart_severity_metrics = array_count_values($se_array);

        array_push($vu_array,$data['warnings'][$i]['warning_type']);
        $chart_vulntype_metrics = array_count_values($vu_array);
    }
}

/*风险代码视图种类*/
if(!empty($data)){
    $fe_array = array();
    foreach ($data['warnings'] as $key => $value) {
        if (array_key_exists('file', $value)) {
            array_push($fe_array,strtolower(trim(substr(strrchr($value['file'],'.'),1))));
            $chart_codetypes_metrics = array_count_values($fe_array);
        }
    }
}
?>
<div class="container">
    <div class="row rowpiece">
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" >
          <a href="issues.php" style=" background-color:rgba(34,41,48,.6);box-shadow: 2px 2px 3px #000;">总览视图</a>
        </div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead">
        <a href="issues_bro.php">分览视图</a></div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead">
        <a href="online.php">在线审计</a></div>
    </div>
    <div class="row rowpiece">
        <!--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 subtitle">代码分类视图</div>-->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mainmap" id = "mainmap0"></div>
    </div>
    <div class="row rowpiece">
        <!--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 subtitle">代码分类视图</div>-->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mainmap" id = "mainmap1"></div>
    </div>
    <div class="row rowpiece">
        <!--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 subtitle">代码分类视图</div>-->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mainmap" id = "mainmap2" style="min-height: 400px;"></div>
    </div>
</div>
<script src="./dist/js/echarts.js"></script>
<script src="./dist/js/dark.js"></script>
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
            data:[<?php
                        $lang_metrics_name = "";
                        $chart_codetype_name = '';
                        foreach ($chart_codetypes_metrics as $key => $value) {
                            $chart_codetype_name .= "'" . $key ."',";
                        }
                        echo $chart_codetype_name;
                    ?>]
        },

        calculable : true,
        series : [
            {
                name:'面积模式',
                type:'pie',
                radius : [30, 110],
                center : ['30%', '50%'],
                roseType : 'area',
                data:[<?php
                            $codetypes_metrics = '';
                            foreach ($chart_codetypes_metrics as $key => $value) {
                              $codetypes_metrics .= "{name:'" . $key ."',value:".$value."},";
                            }
                            echo $codetypes_metrics;
                      ?>],
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
            data:[<?php
                        $sev_metrics_name = "";
                        foreach ($chart_severity_metrics as $key => $value) {
                          $sev_metrics_name .= "'" . $key ."',";
                        }
                        echo $sev_metrics_name;
                  ?>]
        },

        calculable : true,
        series : [
            {
                name:'面积模式',
                type:'pie',
                radius : [30, 110],
                center : ['70%', '50%'],
                roseType : 'area',
                data:[<?php
                            $sev_metrics = "";
                            foreach ($chart_severity_metrics as $key => $value) {
                              if($key == '高'){
                                $sev_metrics .= "{name:'" . $key ."',value:".$value.",itemStyle:{normal:{color:'#dd6b66'}}},";
                              }else if($key == '中'){
                                $sev_metrics .= "{name:'" . $key ."',value:".$value.",itemStyle:{normal:{color:'#fece56'}}},";
                              }else{
                                $sev_metrics .= "{name:'" . $key ."',value:".$value.",itemStyle:{normal:{color:'#93d665'}}},";
                              }
                            }
                            echo $sev_metrics;
                      ?>],
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
            data:[<?php
                          $vuln_metrics_name = "";
                          foreach ($chart_vulntype_metrics as $key => $value) {
                            $vuln_metrics_name .= "'" . $key ."',";
                          }
                          echo $vuln_metrics_name;
                    ?>]
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
                data:[<?php
                           $vuln_metrics = "";
                           foreach ($chart_vulntype_metrics as $key => $value) {
                              $vuln_metrics .= "{name:'" . $key ."',value:".$value."},";
                            }
                          echo $vuln_metrics;
                        ?>]
            }
        ]
    };
    mainmap0.setOption(option0);
    mainmap1.setOption(option1);
    mainmap2.setOption(option2);
</script>
</body>
</html>
