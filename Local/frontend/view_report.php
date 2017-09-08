<?php
include("session.php");
include 'mysql_config.php';
include "history_data.php";

@$report_id = $_GET['id'];

if(!empty($report_id)) {
	$res_his = history_data($_SESSION['user_name'],'工程管理数据查看','成功',3,date('Y-m-d H:i:s'));
	$_SESSION['current_scan_report'] = $_SESSION['report_id'][$report_id];
	header('Location: issues.php');
}else{
	$res_his = history_data($_SESSION['user_name'],'工程管理数据查看','失败',3,date('Y-m-d H:i:s'));
}
$pdo=NULL;
?>
