<?php
include("session.php");
include 'mysql_config.php';
include "history_data.php";

define('ENDPOINT', 'http://127.0.0.1:8000'); #defined in gunicorn_config.py

@$report_id = $_GET['id'];

if(!empty($report_id)) {
	$delete_dir = $_SESSION['delete_id'][$report_id];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, ENDPOINT . '/purge/?path=' . $delete_dir);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    if ($response === 'Success') {
		$res_his = history_data($_SESSION['user_name'],'工程管理数据删除','成功',3,date('Y-m-d H:i:s'));
    	$_SESSION['delete_id'][$report_id] = '';
    }else{
		$res_his = history_data($_SESSION['user_name'],'工程管理数据删除','失败',3,date('Y-m-d H:i:s'));
	}
	header('Location: history.php');
}
$pdo=NULL;
?>
