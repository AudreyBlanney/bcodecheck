<?php
	include "session.php";
	include "mysql_config.php";
	include "history_data.php";
	exec('sudo shutdown -h now',$res,$status);
	if($status == 0){
		$res_his = history_data($_SESSION['user_name'],'代码审查系统设备关机','成功',2,date('Y-m-d H:i:s'));
	}else{
		$res_his = history_data($_SESSION['user_name'],'代码审查系统设备关机','失败',2,date('Y-m-d H:i:s'));
	}
?>