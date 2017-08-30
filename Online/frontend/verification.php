<?php
session_start();
include "./mysql_config.php";
require_once 'SmsApi.php';
$clapi  = new ChuanglanSmsApi();
$phone_code = rand(100000,999999);
$data ="【北京匠迪科技有限公司】您好，您的验证码是" . $phone_code ;
//获取手机号，判断手机号是否正确
$phone = $_POST['phone'] ? trim($_POST['phone']) : ''; //获取手机号
if(!preg_match("/^1\d{10}$/",$phone) || !$phone){
    $prompt = array('success' => false,'res' => '您输入的手机号格式不对，请您重新输入');
    die(json_encode($prompt));
}

//判断手机号是否存在
$mysql_phone = "select phone from {$tb_prefix}_user where phone = ?";
$phone_query = $pdo->prepare($mysql_phone);
$phone_query->execute(array($phone));
$resultphone = $phone_query->fetch(PDO::FETCH_ASSOC);
if($resultphone['phone'] == $phone){
	$prompt = array('success' => false,'res' => '此手机号已存在，不可以注册');
	die(json_encode($prompt));
}

//获取该手机号获取验证码的次数
$_SESSION['phone_num'] = !empty($_SESSION['phone_num']) ? $_SESSION['phone_num'] : 1;
if(!empty($_SESSION['user_phone']) && $_SESSION['user_phone'] == $phone){
    $_SESSION['phone_num'] +=1;
}else{
    unset($_SESSION['phone_num']);
}
//判断该手机号获取验证码的次数
if(!empty($_SESSION['phone_num']) && $_SESSION['phone_num'] >= 10){
    $prompt = array('success' => false,'res' => '获取短信次数过多，请稍后再试');
    die(json_encode($prompt));
}

//发送验证码
if(!empty($_SESSION['phone_time']) && $_SESSION['phone_time'] + 60 > time()){
    $prompt = array('success' => false,'res' => '请60秒后再获取');
    die(json_encode($prompt));
}else{
    $result = $clapi->sendSMS($phone, $data);
    $result = $clapi->execResult($result);
    if(isset($result[1]) && $result[1]==0){
        //设置获取验证码的时间
        $_SESSION['phone_time'] = time();
        $_SESSION['phone_code'] = $phone_code;
        $_SESSION['user_phone'] = $phone;
        $prompt = array('success' => true,'res' => '验证码发送成功');
        die(json_encode($prompt));
    }else{
        $prompt = array('success' => false,'res' => '验证码发送失败');
        die(json_encode($prompt));
    }
}

