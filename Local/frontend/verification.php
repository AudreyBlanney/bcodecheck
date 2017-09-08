<?php
session_start();
require_once 'SmsApi.php';
$clapi  = new ChuanglanSmsApi();
$phone_code = rand(100000,999999);
$data ="【253云通讯】您好，您的验证码是" . $phone_code ;
//获取手机号，判断手机号是否正确
$phone = $_POST['phone'] ? trim($_POST['phone']) : ''; //获取手机号
if(!preg_match("/^1\d{10}$/",$phone)){
    $prompt = array('success' => false,'res' => '您输入的手机号格式不对，请您重新输入');
    die(json_encode($prompt));
}

//发送验证码
$result = $clapi->sendSMS($phone, $data);
$result = $clapi->execResult($result);
if(isset($result[1]) && $result[1]==0){
    //设置获取验证码的时间
    if(empty($_SESSION['phone_time'])){
        $_SESSION['phone_time'] = time();
        $_SESSION['phone_code'] = $phone_code;
    }
    $prompt = array('success' => true,'res' => '验证码发送成功');
    die(json_encode($prompt));
}else{
    $prompt = array('success' => false,'res' => '验证码发送失败');
    die(json_encode($prompt));
}