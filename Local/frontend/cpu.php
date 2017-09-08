<?php
include "session.php";
include "header.php";
include "mysql_config.php";
//获取cpu利用率
$date_nyr = !empty($_POST['date_nyr']) ? $_POST['date_nyr'] : date('Y-m-d');
if(!strtotime($date_nyr)){
	echo '<script>alert("请输入正确的时间");location.href = "cpu.php";</script>';
	
}
$mysql_resource = "select id,cpu_rate,memory_rate,date,date_nyr from {$tb_prefix}_resource where date_nyr = '{$date_nyr}'";
$query_resource = $pdo->prepare($mysql_resource);
$query_resource->execute();
$re_resource = $query_resource->fetchAll(PDO::FETCH_ASSOC);

$date_time_str = '';//x抽时间
$cpu_rate_str = '';//cpu使用
$memory_rate_str = '';//内存使用
foreach($re_resource as $re_key => $re_value){
	$date_time_str .= '"'.$re_value['date'].'",';
	$cpu_rate_str .= '"'.rtrim($re_value['cpu_rate'],'%').'",'; //cpu使用
	$memory_rate_str .= '"'.rtrim($re_value['memory_rate'],'%').'",';//内存使用
}
$date_time_str = rtrim($date_time_str,','); //x抽时间
$cpu_rate_str = rtrim($cpu_rate_str,','); //cpu使用
$memory_rate_str = rtrim($memory_rate_str,','); //内存使用

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
            <a href = "sys-equipment.php" class= "secondT" style = "background-color:#20262f;box-shadow: 2px 2px 3px #000">设备管理</a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           <ul class="thirdT">
                <li>
                    <a href="message.php">设备信息</a>
                </li>
                <li style = "background-color:#20262f;box-shadow:1px 1px 2px #000">
                    <a href="cpu.php">CPU/内存统计</a>
                </li>
                <li style="margin-left:10px">
                    <a href="harddisk.php">硬盘资源统计</a>
                </li>
                <li style="margin-left:10px">
                    <a href="sys-equipment.php">规则升级</a>
                </li>
                <li style="margin-left:10px">
                    <a href="close.php">重启/关机</a>
                </li>
            </ul>

            <div class = "fourT">
                <form method="post">
					<button class="btntime"></button>
					<input class="laydate-icon nyr cpudate" name="date_nyr" value="<?php echo $date_nyr?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})">    	
				</form>
            </div>

            <div class="charts" id="charts0"></div>
            <div class="charts" id="charts1"></div>
            
            
        </div>
    </div>
</div>



<script src="./dist/js/jquery-3.1.1.min.js"></script>
<script src="./dist/js/bootstrap.min.js"></script>
<script src="./dist/js/echarts.js"></script>
<script src="./dist/js/dark.js"></script>
<script src="./dist/js/laydate/laydate.js"></script>
<script>	
    resizeOption()
    function resizeOption(){
		charts0 = echarts.init(document.getElementById('charts0'),'dark');
		charts1 = echarts.init(document.getElementById('charts1'),'dark');

		option0 = {
			backgroundColor:'#2a323d',
			title: {
				x:'center',
				y:'top',
				text: 'CPU曲线统计',
				textStyle:{
					fontWeight:'200',
					fontSize:18
				}
			},
			tooltip: {
				trigger: 'axis'
			},
			dataZoom: [{
				type: 'inside',
				start: 30,
				end: 70
			}, {
				handleSize: 12 ,  //滑动条的 左右2个滑动条的大小
				height:18,
				handleStyle: {
                    borderColor: "#cacaca",
                    borderWidth: "1",
                    shadowBlur: 2,
                    background: "#ddd",
                    shadowColor: "#ddd",
                },
                fillerColor: new echarts.graphic.LinearGradient(1, 0, 0, 0, [{
                    //给颜色设置渐变色 前面4个参数，给第一个设置1，第四个设置0 ，就是水平渐变
                    //给第一个设置0，第四个设置1，就是垂直渐变
                    offset: 0,
                    color: '#337ab7'
                }, {
                    offset: 1,
                    color: '#5ccbb1'
                }]),
                backgroundColor: 'rgba(88,113,179,0.2)',//两边未选中的滑动条区域的颜色
				handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
				filterMode: 'filter',
			}],
			xAxis :
				{
					type : 'category',
					boundaryGap : false,
					axisLine: {onZero: true},
					data: [<?php echo $date_time_str?>]
				},
			   
			
			yAxis : [
						{
							type: 'value',
							name: '利用率%',
							min: 0,
							max: 100,
							axisLabel: {
								formatter: '{value}% '
							}
						}
					],

			
			series : {
					name:'利用率',
					type:'line',
					yAxisIndex: 0,
					data:[
					   <?php echo $cpu_rate_str?>
					]
				},
		  
		};
			
			
			
		option1 = {
			backgroundColor:'#2a323d',
			title: {
				x:'center',
				y:'top',
				text: '内存曲线统计',
				textStyle:{
					fontWeight:'200',
					fontSize:18
				}
			},
			tooltip: {
				trigger: 'axis'
			},
			dataZoom: [{
				type: 'inside',
				start: 30,
				end: 70
			}, {
				handleSize: 12 ,  //滑动条的 左右2个滑动条的大小
				height:18,
				handleStyle: {
                    borderColor: "#cacaca",
                    borderWidth: "1",
                    shadowBlur: 2,
                    background: "#ddd",
                    shadowColor: "#ddd",
                },
                fillerColor: new echarts.graphic.LinearGradient(1, 0, 0, 0, [{
                    //给颜色设置渐变色 前面4个参数，给第一个设置1，第四个设置0 ，就是水平渐变
                    //给第一个设置0，第四个设置1，就是垂直渐变
                    offset: 0,
                    color: '#337ab7'
                }, {
                    offset: 1,
                    color: '#5ccbb1'
                }]),
                backgroundColor: 'rgba(88,113,179,0.2)',//两边未选中的滑动条区域的颜色
				handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
				filterMode: 'filter',
			}],
			xAxis :
				{
					type : 'category',
					boundaryGap : false,
					axisLine: {onZero: true},
					data: [<?php echo $date_time_str?>]
				},
			   
			
			yAxis : [
						{
							type: 'value',
							name: '利用率%',
							min: 0,
							max: 100,
							axisLabel: {
								formatter: '{value}% '
							}
						}
					],

			
			series : {
					name:'利用率',
					type:'line',
					yAxisIndex: 0,
					data:[
					   <?php echo $memory_rate_str?>
					]
				},
		  
		};
        charts0.setOption(option0);
        charts1.setOption(option1);
    }
   
</script>  
</body>
</html>
