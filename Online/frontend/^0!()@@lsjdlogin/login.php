<?php
session_start();
include "config/mysql_config.php";
if($_POST['user_name']){
    $user_name = $_POST['user_name'] ? trim($_POST['user_name']) : '';
}else{
    header('Location: login_not.php');
}
if($_POST['password']){
    $password = $_POST['password'] ? md5(trim($_POST['password'])) : '';
}else{
    header('Location: login_not.php');
}
if($user_name && $password) {
    $mysql_str = "select id from {$tb_prefix}_admin_user where user_name = ? and password = ?";
    $query = $pdo->prepare($mysql_str);
    $query->execute(array($user_name,$password));
    $row = $query->fetch(PDO::FETCH_ASSOC);
    if($row){
        $_SESSION['admin_user_name'] = $user_name;
        $prompt = array('success' => true);
        die(json_encode($prompt));
    }else{
        $prompt = array('success' => false,'res' => '用户名或密码错误');
        die(json_encode($prompt));
    }
}else{
    $prompt = array('success' => false,'res' => '用户名或密码错误');
    die(json_encode($prompt));
}
