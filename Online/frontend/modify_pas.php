<?php
include 'session.php';
include 'mysql_config.php';

$ya_password = $_POST['ya_password'] ? md5(trim($_POST['ya_password'])) : '';//密码
$password = $_POST['password'] ? md5(trim($_POST['password'])) : '';//密码
$password_ok = $_POST['password_ok'] ? md5(trim($_POST['password_ok'])) : '';//确认密码

//获取用户信息
$mysql_str = "select id,user_name,phone,email,register_time from {$tb_prefix}_user where user_name = ?  and password = ? and switch_type = ?";
$query = $pdo->prepare($mysql_str);
$query->execute(array($_SESSION['user_name'],$ya_password,1));
$res = $query->fetch(PDO::FETCH_ASSOC);
if(empty($res) && !$res){
    $prompt = array('title' => 'ya_password','result_err' => '原密码不对，请重新输入');
    die(json_encode($prompt));

}

//密码验证 .数字，字母，标点
if(!$password && !preg_match("/^[a-zA-Z0-9,，、。?!.'？！@#$%^&*·`~<>；;‘'[]【】]{6,20}$/",$password)){
    $prompt = array('title' => 'password','result_err' => '密码格式不对，请重新输入');
    die(json_encode($prompt));
}

//确认密码
if($password != $password_ok){
    $prompt = array('title' => 'password_ok','result_err' => '两次密码不一样，请重新输入');
    die(json_encode($prompt));
}

$up_sql = "update {$tb_prefix}_user set password = ? where user_name = ? and password = ? and switch_type = ?";
$up_sql = $pdo->prepare($up_sql);
$up_res = $up_sql->execute(array($password_ok,$_SESSION['user_name'],$ya_password,1));
if($up_res){
    $prompt = array('success' => true);
    die(json_encode($prompt));
}