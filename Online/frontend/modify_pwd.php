<?php
session_start();
include "./mysql_config.php";
include "./mail_config.php";
include "secure_filtering.php";

$email = $_POST['email'] ? trim($_POST['email']) : '';//邮箱
$email_code = $_POST['email_code'] ? trim($_POST['email_code']) : '';//验证码
$password = $_POST['password'] ? md5(trim($_POST['password'])) : '';//密码
$password_ok = $_POST['password_ok'] ? md5(trim($_POST['password_ok'])) : '';//确认密码
//邮箱验证
if(!$email || !preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i",$email)){
    $prompt = array('success' => false,'title' => 'email','result_err' => '邮箱格式不正确，请重新输入');
    die(json_encode($prompt));
}

$mysql_str = "select id from {$tb_prefix}_user where email = ?";
$query = $pdo->prepare($mysql_str);
$query->execute(array($email));
$email_result = $query->fetch(PDO::FETCH_ASSOC);
if(!$email_result){
    $prompt = array('success' => false,'title' => 'email','result_err' => '此邮箱不存在请重新输入');
    die(json_encode($prompt));
}

//验证码验证
if(empty($_SESSION) || !$_SESSION['email_code'] || empty($_SESSION['email_code'])){
    $prompt = array('success' => false,'title' => 'email_code','result_err' => '验证码不正确，请重新输入');
    die(json_encode($prompt));
}
if($email_code != $_SESSION['email_code']){
    $prompt = array('success' => false,'title' => 'email_code','result_err' => '验证码不正确，请重新输入');
    die(json_encode($prompt));
}

//验证码时间验证
if($_SESSION['email_time'] + 60 < time()){
    unset($_SESSION['email_time']);
    unset($_SESSION['email_code']);
    $prompt = array('success' => false,'title' => 'email_code','result_err' => '验证码超时，请重新获取');
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

$up_sql = "update {$tb_prefix}_user set password = ? where id = ? and email = ?";
$up_sql = $pdo->prepare($up_sql);
$up_res = $up_sql->execute(array($password_ok,$email_result['id'],$email));
if($up_res){
    $prompt = array('success' => true);
    die(json_encode($prompt));
}