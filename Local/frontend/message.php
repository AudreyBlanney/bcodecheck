<?php
include "session.php";
include "header.php";
include "mysql_config.php";
//修改时间

if(!empty($_POST['system_date_time'])){
	if(!strtotime($_POST['system_date_time'])){
		echo '<script>alert("请输入正确的时间");location.href = "message.php";</script>';
	}else{
		$sys_date_nyr = $_POST['system_date_time'];
		exec("sudo date -s '{$sys_date_nyr}'",$res,$status);
		if($status == 0){
			header('Location:message.php');
		}else{
			echo '<script>alert("时间修改失败，请重新修改");location.href = "message.php";</script>';
		}
	}
}

	

//获取硬盘大小
exec("sudo fdisk -l | grep Disk", $output);
$disk_1 = explode(',',$output[0]);
$disk_2 = explode(':',$disk_1[0]);
$disk_size = $disk_2[1];

//获取内存大小
exec("sudo cat /proc/meminfo |grep MemTotal", $memory);
$memory_1 = explode(':',$memory[0]);
$memory_size = $memory_1[1];
$memory_size = round(rtrim($memory_1[1],'KB')/1024/1024).' GB';

//获取系统时间
exec('sudo date +%Y-%m-%d" "%T" , "%Z', $date);
$system_time = $date[0];

//获取时区
exec('sudo date +%Z', $zone);
$time_zone = $zone[0];

//获取系统时间
exec('sudo date +%Y-%m-%d" "%T', $time);
$system_date_time = $time[0];

//设备尺寸
$equipment_size = '宽~~mm*高~~mm*深~~mm';

//序列号
$serial_number = '0201725462142';

//获取警告阈值
$mysql_se = "select * from {$tb_prefix}_notice";
$query_se = $pdo->prepare($mysql_se);
$query_se->execute();
$res = $query_se->fetch(PDO::FETCH_ASSOC);
$system_name = !empty($res['system_name']) ? $res['system_name'] : ''; //系统名称
$cpu_sy_rate = !empty($res['cpu_sy_rate']) ? $res['cpu_sy_rate'] : ''; //cpu使用率
$memory_sy_rate = !empty($res['memory_sy_rate']) ? $res['memory_sy_rate'] : ''; //内存使用率
$disk_sy_rate = !empty($res['disk_sy_rate']) ? $res['disk_sy_rate'] : ''; //磁盘使用率

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
                <li style = "background-color:#20262f;box-shadow:1px 1px 2px #000">
                    <a href="message.php">设备信息</a>
                </li>
                <li style="margin-left:10px">
                    <a href="cpu.php">CPU/内存统计</a>
                </li>
                <li style="margin-left:10px">
                    <a href="harddisk.php">硬盘资源统计</a>
                </li>
                <li style="margin-left:10px">
                    <a href="sys-equipment.php">设备升级</a>
                </li>
                <li style="margin-left:10px">
                    <a href="close.php">重启/关机</a>
                </li>
            </ul>

            <div class = "fourT">设备信息状态</div>

            
            <div class="inforCon">
                <div class="infor-name-textCon">
                    <div class="infor-name">系统名称</div>
                    <div class="infor-text"><?php echo $system_name?></div>
                    <div class="infor-name">内存大小</div>
                    <div class="infor-text"><?php echo $memory_size;?></div>
                </div>
                <div class="infor-name-textCon">
                    <div class="infor-name">系统时间</div>
                    <div class="infor-text"><?php echo $system_time;?></div>
                    <div class="infor-name">序列号</div>
                    <div class="infor-text"><?php echo $serial_number;?></div>
                </div>
                <div class="infor-name-textCon">
                    <div class="infor-name">设备尺寸</div>
                    <div class="infor-text"><?php echo $equipment_size;?></div>
                    <div class="infor-name">软件版本</div>
                    <div class="infor-text">软件版本</div>
                </div>   
            </div>



            <div class = "fourT">设备信息设置</div>

            <div class="inforCon">
                <form id="form_sys" onsubmit="return false">
                    <div class="infor-name-textCon">
                        <div class="set-name">系统名称</div>
                        <div class="set-name">CPU告警阈值</div>
                        <div class="set-name">内存告警阈值</div>
                        <div class="set-name">硬盘告警阈值</div>
                    </div>
                    <div class="infor-name-textCon">
                        <div class="set-name"><input type="text" name="system_name" value="<?php echo $system_name?>" placeholder="请输入系统名称"></div>
                        <div class="set-name"><input type="number"  step="0.00001" name="cpu_sy_rate" value="<?php echo $cpu_sy_rate?>" min="0" max="100" placeholder="请输入一个0~100之间的数"></div>
                        <div class="set-name"><input type="number" step="0.00001" name="memory_sy_rate" value="<?php echo $memory_sy_rate?>" min="0" max="100"  placeholder="请输入一个0~100之间的数"></div>
                        <div class="set-name"><input type="number" step="0.00001" name="disk_sy_rate" value="<?php echo $disk_sy_rate?>" min="0" max="100"  placeholder="请输入一个0~100之间的数"></div>
                    </div>
                    <div class="infor-name-textCon" style="border-left:2px solid #2a323d">
                        <button class="ensure notice_sys">保存</button>
                    </div>
                </form>
            </div> 
            <div class="inforCon">
                <form method="post">
                    <div class="infor-name-textCon">
                        <div class="time" style="width:70%">
                            日期和时间:&nbsp;&nbsp;<input class="laydate-icon nyr tgy" name="system_date_time" value="<?php echo $system_date_time ?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"> 
                                       
                        </div>
                        <button class="ensure">保存</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="./dist/js/jquery-3.1.1.min.js"></script>
<script src="./dist/js/bootstrap.min.js"></script>
<script src="./dist/js/laydate/laydate.js"></script>
<script>
	//时区设置
	$(function(){
		var time_zone = '<?php echo $time_zone?>';
		$('.'+time_zone).attr('selected',true);
	});
	$('.notice_sys').click(function(){
		$.ajax({
			url: 'system_notice.php',
			type: "post",
			dataType:'json',
			data: $("#form_sys").serialize(),
			success: function (data) {
				 if(data.success){
                        location.href = "message.php";
                    }else{
						alert(data.result_err);
                    }
			},
			error: function (err) {
				 alert('修改失败，请重新修改');
			 }
		});
	});
   
</script>  
</body>
</html>
