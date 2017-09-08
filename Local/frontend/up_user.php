<?php
session_start();
include "./mysql_config.php";
include "./mail_config.php";
include "history_data.php";
$user_id = trim($_POST['id']) ? trim($_POST['id']) : '';//用户id
$nickname = trim($_POST['edit_nickname']) ? trim($_POST['edit_nickname']) : '';//昵称
$password = $_POST['edit_password'] ? trim($_POST['edit_password']) : '';//密码
$password_ok = $_POST['edit_password_ok'] ? md5(trim($_POST['edit_password_ok'])) : '';//确认密码
$diction = $_POST['diction'] ? $_POST['diction'] : 2;

//昵称验证 中文 数字 字母
if(!$nickname || !preg_match("/^[\x7f-\xff0-9a-zA-Z]{3,15}$/i",$nickname)){
    $prompt = array('title' => 'edit_nickname','result_err' => '昵称格式不对，从重新输入');
    die(json_encode($prompt));
}


if($password || $password_ok){
	//密码验证 .数字，字母，标点
	if(!preg_match("/^[a-zA-Z0-9,，、。?!.'？！@#$%^&*·`~<>；;‘'【】]{6,20}$/",$password)){
		$prompt = array('title' => 'edit_password','result_err' => '密码格式不对，请重新输入');
		die(json_encode($prompt));
	}
	//确认密码
	if(md5($password) != $password_ok){
		$prompt = array('title' => 'edit_password_ok','result_err' => '两次密码不一样，请重新输入');
		die(json_encode($prompt));
	}
}



//用户数据修改
die(json_encode(cj_user()));

function cj_user(){
    global $pdo,$tb_prefix,$nickname,$password_ok,$diction,$user_id; //引入相关数据
    $register_time = date('Y-m-d H:i:s'); //获取注册时间
	if($password_ok){
		$password = "password = '{$password_ok}' , ";
	}else{
		$password = '';
	}
    $mysql_up = "update {$tb_prefix}_user set user_name = ? , {$password} register_time =? , diction =? where user_name = ? and id = ?";
    $query_up = $pdo->prepare($mysql_up);
    $data = array($nickname,$register_time,$diction,$nickname,$user_id);
    $query_up->execute($data);
    $res_up = $query_up->rowCount();
    if($res_up){
		$res_his = history_data($_SESSION['user_name'],'代码审查系统修改用户','成功',2,date('Y-m-d H:i:s'));
		$prompt = array('success' => true);
    }else{
		$res_his = history_data($_SESSION['user_name'],'代码审查系统修改用户','失败',2,date('Y-m-d H:i:s'));
        $prompt = array('success' => false);
	}
    return $prompt;
}

