<?php
session_start();
header("Content-type: text/html; charset=utf-8");
require_once("./mail_config.php");
$code = rand(100000,999999);
$data ="【匠迪科技】您好，您的验证码是:" . $code ;
$email = $_POST['email'] ? $_POST['email'] : ' '; //收件人邮箱
$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i"; //邮箱判断
if (!preg_match( $pattern, $email ) || !$email){
    $prompt = array('success' => false,'res' => '您的邮箱格式不正确，请您重新输入');
    die(json_encode($prompt));
}
//发送邮件
$flag = sendMail($email,'匠迪科技',$data);
if($flag){
    if(empty($_SESSION['email_time'])){
        $_SESSION['email_time'] = time();
        $_SESSION['email_code'] = $code;
    }
    $prompt = array('success' => true,'res' => '验证码发送成功');
    die(json_encode($prompt));
}else{
    $prompt = array('success' => false,'res' => '验证码发送失败');
    die(json_encode($prompt));
}


