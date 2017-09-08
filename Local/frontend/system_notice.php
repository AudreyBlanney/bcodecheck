<?php
include "session.php";
include "mysql_config.php";
include "history_data.php";
$system_name = !empty($_POST['system_name']) ? $_POST['system_name'] : ''; //系统名称
$cpu_sy_rate = !empty($_POST['cpu_sy_rate']) ? $_POST['cpu_sy_rate'] : ''; //cpu使用率
$memory_sy_rate = !empty($_POST['memory_sy_rate']) ? $_POST['memory_sy_rate'] : ''; //内存使用率
$disk_sy_rate = !empty($_POST['disk_sy_rate']) ? $_POST['disk_sy_rate'] : ''; //磁盘使用率

//判断cup值为正整数
if(!$cpu_sy_rate || !is_numeric($cpu_sy_rate)){
	$prompt = array('title' => 'cpu_sy_rate','result_err' => '格式不对，从重新输入');
	die(json_encode($prompt));
}

//判断内存值为正整数
if(!$memory_sy_rate || !is_numeric($memory_sy_rate)){
	$prompt = array('title' => 'memory_sy_rate','result_err' => '格式不对，从重新输入');
	die(json_encode($prompt));
}

//判断磁盘值为正整数
if(!$disk_sy_rate || !is_numeric($disk_sy_rate)){
	$prompt = array('title' => 'disk_sy_rate','result_err' => '格式不对，从重新输入');
	die(json_encode($prompt));
}

//清空数据
$tool_status = true;
$mysql_se = "select * from {$tb_prefix}_notice";
$query_se = $pdo->prepare($mysql_se);
$query_se->execute();
$res = $query_se->fetch(PDO::FETCH_ASSOC);
if($res){
	$del_sql = "truncate table {$tb_prefix}_notice";
	$del_query = $pdo->prepare($del_sql);
	$del_query->execute();
	$row = $del_query->rowCount();
	if($row == 0){
		$tool_status = true;
	}else{
		$tool_status = false;
	}
}



if($tool_status){
	//插入数据库
	$mysql_insert = "insert into {$tb_prefix}_notice(system_name,cpu_sy_rate,memory_sy_rate,disk_sy_rate) values(?,?,?,?)";
	$query_insert = $pdo->prepare($mysql_insert);
	$data = array($system_name,$cpu_sy_rate,$memory_sy_rate,$disk_sy_rate);
	$query_insert->execute($data);
	$res_insert = $pdo->lastinsertid();
	if($res_insert){
		$res_his = history_data($_SESSION['user_name'],'代码审查系统修改系统阈值','成功',2,date('Y-m-d H:i:s'));
		$prompt = array('success' => true,'system_name' => $system_name,'cpu_sy_rate' => $cpu_sy_rate, 'memory_sy_rate' => $memory_sy_rate, 'disk_sy_rate' => $disk_sy_rate);
		die(json_encode($prompt));
	}
}

