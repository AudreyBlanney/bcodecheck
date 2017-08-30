<?php
session_start();
include "mysql_config.php";
$user_name = $_POST['user_name'] ? trim($_POST['user_name']) : '';
$password = $_POST['password'] ? md5(trim($_POST['password'])) : '';

if($_COOKIE){
    $session_id = $_COOKIE['PHPSESSID'];
}else{
    $session_id = session_id();
}

if($user_name && $password) {
    $mysql_str = "select id from {$tb_prefix}_user where (user_name = ? or phone = ? or email = ?) and password = ? and switch_type = ?";
    $query = $pdo->prepare($mysql_str);
    $query->execute(array($user_name,$user_name,$user_name,$password,1));
    $row = $query->fetch(PDO::FETCH_ASSOC);
    if($row){
        $mysql_str = "select id,login_id,last_time,user_name from {$tb_prefix}_user where (user_name = ? or phone = ? or email = ?) and switch_type = ?";
        $query = $pdo->prepare($mysql_str);
        $query->execute(array($user_name,$user_name,$user_name,1));
        $login_result = $query->fetch(PDO::FETCH_ASSOC);
        if ($login_result){
            $_SESSION['login_id'] = $login_result['login_id'];
            $_SESSION['user_name'] = $login_result['user_name'];
            $mysql_str = "update {$tb_prefix}_user set session_id = ?,last_time = ? where (user_name = ? or phone = ? or email = ?) and switch_type = ?";
            $query = $pdo->prepare($mysql_str);
            $time = time();
            $row_column = $query->execute(array($session_id,$time,$user_name,$user_name,$user_name,1));
            if($row_column){
                $prompt = array('success' => true);
                die(json_encode($prompt));
            }
        } else{
            $prompt = array('success' => false,'res' => '用户名或密码错误');
            die(json_encode($prompt));
        } 
    } else {
        $prompt = array('success' => false,'res' => '用户名或密码错误');
        die(json_encode($prompt));
    }
}else if (!empty($_SESSION['user_name'])) {
    $prompt = array('success' => true);
    die(json_encode($prompt));
}