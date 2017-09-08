<?php
include "session.php";
include 'mysql_config.php';
include "history_data.php";
$id = $_POST['id'];
if($id != null){
    $mysql_str = "delete from {$tb_prefix}_user where id = ?";
    $query = $pdo->prepare($mysql_str);
    $res = $query->execute(array($id));
    if($res){
		$res_his = history_data($_SESSION['user_name'],'代码审查系统用户删除','成功',2,date('Y-m-d H:i:s'));
        $prompt = array('success' => true);
        die(json_encode($prompt));
    }else{
		$res_his = history_data($_SESSION['user_name'],'代码审查系统用户删除','失败',2,date('Y-m-d H:i:s'));
        $prompt = array('success' => false);
        die(json_encode($prompt));
    }
}else{
    $prompt = array('success' => false);
    die(json_encode($prompt));
}