<?php
session_start();
include "mysql_config.php";
include "history_data.php";
$user_name = $_POST['user_name'] ? trim($_POST['user_name']) : '';
$password = $_POST['password'] ? md5(trim($_POST['password'])) : '';

if($_COOKIE){
    $session_id = $_COOKIE['PHPSESSID'];
}else{
    $session_id = session_id();
}
if($user_name && $password) {
    $mysql_str = "select id from {$tb_prefix}_user where user_name = ? and password = ?";
    $query = $pdo->prepare($mysql_str);
    $query->execute(array($user_name,$password));
    $row = $query->fetch(PDO::FETCH_ASSOC);
    if($row){
        $mysql_str = "select id,login_id,last_time,diction from {$tb_prefix}_user where user_name = ?";
        $query = $pdo->prepare($mysql_str);
        $query->execute(array($user_name));
        $login_result = $query->fetch(PDO::FETCH_ASSOC);
        if ($login_result){
			$res_his = history_data($user_name,'登录代码审查系统','成功',2,date('Y-m-d H:i:s'));
            $_SESSION['login_id'] = $login_result['login_id'];
            $_SESSION['user_name'] = $user_name;
            $mysql_str = "update {$tb_prefix}_user set session_id = ?,last_time = ? where user_name = ?";
            $query = $pdo->prepare($mysql_str);
            $time = time();
            $row_column = $query->execute(array($session_id,$time,$user_name));
            if($row_column){
                $prompt = array('success' => true);
                die(json_encode($prompt));
            }
        } else{
			$res_his = history_data($user_name,'登录代码审查系统','失败',2,date('Y-m-d H:i:s'));
            $prompt = array('success' => false,'res' => '用户名或密码错误');
            die(json_encode($prompt));
        } 
    } else {
		$res_his = history_data($user_name,'登录代码审查系统','失败',2,date('Y-m-d H:i:s'));
        $prompt = array('success' => false,'res' => '用户名或密码错误');
        die(json_encode($prompt));
    }
}else if (!empty($_SESSION['user_name'])) {
    $prompt = array('success' => true);
    die(json_encode($prompt));
}