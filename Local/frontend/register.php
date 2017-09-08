<?php
session_start();
include "./mysql_config.php";
include "./mail_config.php";
include "history_data.php";
$nickname = trim($_POST['nickname']) ? trim($_POST['nickname']) : '';//昵称
$password = $_POST['password'] ? trim($_POST['password']) : '';//密码
$password_ok = $_POST['password_ok'] ? md5(trim($_POST['password_ok'])) : '';//确认密码
$diction = $_POST['diction'] ? $_POST['diction'] : 2;
//昵称验证 中文 数字 字母
if(!$nickname || !preg_match("/^[\x7f-\xff0-9a-zA-Z]{3,15}$/i",$nickname)){
    $prompt = array('title' => 'nickname','result_err' => '昵称格式不对，从重新输入');
    die(json_encode($prompt));
}

//密码验证 .数字，字母，标点
if(!$password || !preg_match("/^[a-zA-Z0-9,，、。?!.'？！@#$%^&*·`~<>；;‘'【】]{6,20}$/i",$password)){
    $prompt = array('title' => 'password','result_err' => '密码格式不对，请重新输入');
    die(json_encode($prompt));
}

//确认密码
if(md5($password) != $password_ok){
    $prompt = array('title' => 'password_ok','result_err' => '两次密码不一样，请重新输入');
    die(json_encode($prompt));
}

//判断用户信息是否存在
if($data_result = existence()){
    die(json_encode($data_result));
}
//用户数据插入
die(json_encode(cj_user()));

//用户查询
function existence(){
    global $pdo,$tb_prefix,$nickname;
    //判断昵称是否存在
    $mysql_nick = "select user_name from {$tb_prefix}_user where user_name = ?";
    $nick_query = $pdo->prepare($mysql_nick);
    $nick_query->execute(array($nickname));
    $result = $nick_query->fetch(PDO::FETCH_ASSOC);
    if($result['user_name'] == $nickname){
		$res_his = history_data( $_SESSION['user_name'],'代码审查系统添加用户','失败',2,date('Y-m-d H:i:s'));
        $prompt = array('title' => 'nickname','result_err' => '此用户已存在，不可以注册');
        return $prompt;
    }
}

function cj_user(){
    global $pdo,$tb_prefix,$nickname,$password_ok,$diction; //引入相关数据
    $register_time = date('Y-m-d H:i:s'); //获取注册时间
    $mysql_insert = "insert into {$tb_prefix}_user(user_name,password,register_time,diction) values(?,?,?,?)";
    $query_insert = $pdo->prepare($mysql_insert);
    $data = array($nickname,$password_ok,$register_time,$diction);
    $query_insert->execute($data);
    $res_insert = $pdo->lastinsertid();
    if($res_insert){
		    $res_his = history_data($_SESSION['user_name'],'代码审查系统添加用户','成功',2,date('Y-m-d H:i:s'));
            $prompt = array('success' => true);
    }
    return $prompt;
}

