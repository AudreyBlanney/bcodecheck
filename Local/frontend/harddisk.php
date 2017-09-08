<?php
include "session.php";
include "header.php";
include "mysql_config.php";

//获取硬盘大小
exec("sudo fdisk -l | grep Disk", $output);
$disk_1 = explode(',',$output[0]);
$disk_2 = explode(':',$disk_1[0]);
$disk_size = $disk_2[1];

//获取磁盘已用空间
exec("sudo df -k | awk 'NR != 1{a+=$3}END{print a}'", $disk_y_space);
$disk_y_space_size = round($disk_y_space[0]/1024/1024,2).' GB';

//获取磁盘可用空间
$disk_s_space = round($disk_size - $disk_y_space_size,2).' GB';

//获取磁盘信息
$mysql_se = "select * from {$tb_prefix}_disk";
$query_se = $pdo->prepare($mysql_se);
$query_se->execute();
$res = $query_se->fetchAll(PDO::FETCH_ASSOC);
$disk_str = '';
//获取一个月的时间
for($i = 1;$i <30;$i++){
	$disk_str .= '"'.date('Y-m-d',(strtotime($res[0]['date'])+(60*60*24)*$i)).'",';
}
$disk_date = '"'.date('Y-m-d',(strtotime($res[0]['date']))).'"';
$disk_str = $disk_str ? rtrim($disk_date.','.$disk_str,',') : '';
$disk_array = explode(',',$disk_str);
$json_data = json_encode($res);

$disk_y_space_size_str = ''; //已用磁盘空间
$disk_s_space_str = ''; //可用磁盘空间

//数据整理
foreach($res as $res_key => $res_value){
	$data_res[date('Y-m-d',strtotime($res_value['date']))] = $res_value;
}

foreach($disk_array as $disk_key => $disk_value){
	$disk_y_space_size_str .= !empty($data_res[trim($disk_value,'"')]) ? rtrim($data_res[trim($disk_value,'"')]['disk_y'],'GB').',' : 0 .',';
	
	$disk_s_space_str .= !empty($data_res[trim($disk_value,'"')]) ? rtrim($data_res[trim($disk_value,'"')]['disk_k'],'GB').',' : 0 .',';
}

$disk_y_space_size_str = rtrim($disk_y_space_size_str,',');
$disk_s_space_str = rtrim($disk_s_space_str,',');


?>
    <link rel="stylesheet" href="./dist/css/contact.css">
    <link rel="stylesheet" href="./dist/css/equipment.css">
    <link rel="stylesheet" href="./dist/css/table.css">
    <link rel="stylesheet" href="./dist/css/pop-up.css">
<div class="container">
    <div class="row" style="margin-top: 10px">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 addtitle">
            <a href = "contact.php" >用户管理</a>
            <a href = "sys-tool.php" class= "secondT" >网络管理</a>
            <a href = "sys-jour.php" class= "secondT">系统日志</a>
            <a href = "sys-equipment.php" class= "secondT" style = "background-color:#20262f;box-shadow:2px 2px 3px #000">设备管理</a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <ul class="thirdT">
                <li>
                    <a href="message.php">设备信息</a>
                </li>
                <li style="margin-left:10px;">
                    <a href="cpu.php">CPU/内存统计</a>
                </li>
                <li style="margin-left:10px;background-color:#20262f;box-shadow:1px 1px 2px #000">
                    <a href="harddisk.php">硬盘资源统计</a>
                </li>
                <li style="margin-left:10px">
                    <a href="sys-equipment.php">规则升级</a>
                </li>
                <li style="margin-left:10px">
                    <a href="close.php">重启/关机</a>
                </li>
            </ul>

            <div class = "fourT">磁盘空间总览</div>
            <div class="cpzl">
                <div class="bt" id="charts0"></div>  
                <div class="btmessage">
                    <div class="disk-title">
                        <div class="hardtitle">总磁盘空间(GB)</div>
                        <div class="hardtitle">已用磁盘空间(GB)</div>
                        <div class="hardtitle">可用磁盘空间(GB)</div>
                    </div>
                    <div class="disk-title">
                        <div class="hardmessage"><?php echo $disk_size?></div>
                        <div class="hardmessage"><?php echo $disk_y_space_size?></div>
                        <div class="hardmessage"><?php echo $disk_s_space?></div>
                    </div>
                </div> 
            </div>
            <div class = "fourT">磁盘使用趋势</div>
            <div class="charts" id="charts1"></div>

            <table class="table table-bordered " id = "table">
            </table>
            
            
        </div>
    </div>
</div>
<script src="./dist/js/jquery-3.1.1.min.js"></script>
<script src="./dist/js/bootstrap.min.js"></script>
<script src="./dist/js/bootstrap-table.js"></script>
<script src="./dist/js/echarts.js"></script>
<script src="./dist/js/dark.js"></script>
<script>
//ec图
    $(window).resize(function(){
        charts0.resize();
        charts1.resize();
    })
    charts0 = echarts.init(document.getElementById('charts0'),'dark');
    charts1 = echarts.init(document.getElementById('charts1'),'dark');
    option0 = {
        backgroundColor:'#2a323d',
	    tooltip : {
	        trigger: 'item',
	        formatter: "{a} <br/>{b} : {c} ({d}%)"
	    },
	    series : [
	        {
	            name: '磁盘空间',
	            type: 'pie',
	            x:'center',
	            y:'center',
	            radius : '70%',
	            data:[
	                
	                {value:<?php echo rtrim($disk_y_space_size,'GB')?>, name:'已用磁盘空间'},
	                {value:<?php echo rtrim($disk_s_space,'GB')?>, name:'可用磁盘空间'}
	            ],
	            itemStyle: {
	                emphasis: {
	                    shadowBlur: 10,
	                    shadowOffsetX: 0,
	                    shadowColor: 'rgba(0, 0, 0, 0.5)'
	                },
					 normal:{ 
						label:{ 
						   show: true, 
						   formatter: '{b} : {c} ({d}%)' 
						}, 
						labelLine :{show:true}
					} 
                        
	            },
	           
	        }
	    ]
	};


	option1 = {
		backgroundColor:'#2a323d',
		tooltip: {
			trigger: 'axis',

		},
		legend: {
			x : 'center',
			y : 'top',
			width:'100%',
			height:'20%',
				data:['已用空间','可用空间']
			},
		xAxis: [
			{
				type: 'category',
				data: [<?php echo $disk_str ?>],
				axisPointer: {
					type: 'shadow'
				},
				axisLabel: {
					interval:0,
					rotate:40
				}
			}
		],
		yAxis: [
			{
				type: 'value',
				name: '空间（GB）',
				axisLabel: {
					formatter: '{value}'
				}
			}
		],
		series: [
			{
				name:'已用空间',
				type:'bar',
				data:[<?php echo $disk_y_space_size_str?>],
				itemStyle: {
					normal: {
						color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
							offset: 0,
							color: '#bf4254'
						}, {
							offset: 1,
							color: '#ff9683'
						}], false)
					}
				},
			},
			{
				name:'可用空间',
				type:'bar',
				 data:[<?php echo $disk_s_space_str?>],
				itemStyle: {
					normal: {
						color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
							offset: 0,
							color: '#3dc3fd'
						}, {
							offset: 1,
							color: 'rgba(107, 207, 254,0.2)'
						}], false)
					}
				},
			}
		   
		]
	};

    charts0.setOption(option0);
    charts1.setOption(option1);

//数据表格
    $('#table').bootstrapTable({
                    pagination: true,
                    pageNumber:1,
                    pageSize: 10,
                    pageList: [10,20],
                    columns:[ {
                        field: 'date',
                        title: '时间',
                        align: 'center'
                    }, {
                        field: 'disk_z',
                        title: '总磁盘空间(GB)',
                        align: 'center'
                    }, {
                        field: 'disk_y',
                        title: '已用磁盘空间(GB)',
                        align: 'center'
                    }, {
                        field: 'disk_k',
                        title: '可用磁盘空间(GB)',
                        align: 'center'
                    },{
                        field: 'disk_y_rate',
                        title: '已用比率(%)',
                        align: 'center'
                    }],
                 data:<?php echo $json_data?>  
                 
                });

</script>  
</body>
</html>
