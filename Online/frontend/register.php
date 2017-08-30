<?php
session_start();
include "./mysql_config.php";
include "./mail_config.php";
include "secure_filtering.php";

$nickname = trim($_POST['nickname']) ? trim($_POST['nickname']) : '';//昵称
$phone = $_POST['phone'] ? trim($_POST['phone']) : '';//手机号
$phone_code = $_POST['phone_code'] ? trim($_POST['phone_code']) : '';//手机验证码
$email = $_POST['email'] ? trim($_POST['email']) : '';//邮箱
$password = $_POST['password'] ? trim($_POST['password']) : '';//密码
$password_ok = $_POST['password_ok'] ? md5(trim($_POST['password_ok'])) : '';//确认密码
$register_type = $_POST['register_type'] ? $_POST['register_type'] : 1;//注册类型 1:个人注册 2：企业注册
if($register_type == 2){
	if(empty($_POST['corporate_name'])){
		$prompt = array('title' => 'corporate_name','result_err' => '公司名称格式不对，请重新输入');
		die(json_encode($prompt));
	}
	$corporate_name = $_POST['corporate_name']; //公司名称
}

//设置不同用户的上传文件大小
if($register_type == 1){
    $upload_size = 5;
    $upload_num = 10;
}elseif($register_type == 2){
    $upload_size = 20;
    $upload_num = 10;
}
//昵称验证 中文 数字 字母
if(!$nickname || !preg_match("/^[\x7f-\xff0-9a-zA-Z]{3,15}$/",$nickname)){
    $prompt = array('title' => 'nickname','result_err' => '用户名格式不对，从重新输入');
    die(json_encode($prompt));
}

//手机号验证
if(!$phone || !preg_match('/^1\d{10}$/',$phone)){
    $prompt = array('title' => 'phone','result_err' => '手机号格式不对，请重新输入');
    die(json_encode($prompt));
}

//验证码验证
if(empty($_SESSION['phone_code']) || empty($_SESSION) || !$_SESSION['phone_code']){
    $prompt = array('title' => 'phone_code','result_err' => '验证码不正确，请重新输入');
    unset($_SESSION['phone_code']);
    die(json_encode($prompt));
}
if($phone_code != $_SESSION['phone_code']){
    $prompt = array('title' => 'phone_code','result_err' => '验证码不正确，请重新输入');
    unset($_SESSION['phone_code']);
    die(json_encode($prompt));
}

//验证码时间验证
if($_SESSION['phone_time'] + 60 < time()){
    unset($_SESSION['phone_time']);
    unset($_SESSION['phone_code']);
    $prompt = array('title' => 'phone_code','result_err' => '验证码超时，请重新获取');
    die(json_encode($prompt));
}
/*if($phone_code != 123456){
    $prompt = array('title' => 'phone_code','result_err' => '验证码不正确，请重新输入');
    die(json_encode($prompt));
}*/

//邮箱验证
if(!$email || !preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i",$email)){
    $prompt = array('title' => 'email','result_err' => '邮箱格式不正确，请重新输入');
    die(json_encode($prompt));
}

// 如果企业注册，判断是否是企业邮箱
if($register_type == 2){
    $get_suffix = explode('@',$email);
    $suffix = $get_suffix[1];
    if(in_array($suffix,$email_array)){
        $prompt = array('title' => 'email','result_err' => '您的邮箱不是企业邮箱，请您重新输入');
        die(json_encode($prompt));
    }
}

//密码验证 .数字，字母，标点
if(!$password || !preg_match("/^[a-zA-Z0-9,，、。?!.'？！@#$%^&*·`~<>；;‘'【】]{6,20}$/",$password)){
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

//判断验证码是否过期
if($_SESSION['time'] + 60 < time()){
    unset($_SESSION['time']);
    $prompt = array('title' => 'phone_code','result_err' => '验证码已过期，请重新获取');
    die(json_encode($prompt));
}

//用户查询
function existence(){
    global $pdo,$tb_prefix,$nickname,$phone,$email,$corporate_name,$register_type;
    //判断昵称是否存在
	$mysql_nick = "select user_name from {$tb_prefix}_user where user_name = ?";
	$nick_query = $pdo->prepare($mysql_nick);
	$nick_query->execute(array($nickname));
    $resultnick = $nick_query->fetch(PDO::FETCH_ASSOC);
    if($resultnick['user_name'] == $nickname){
        $prompt = array('title' => 'nickname','result_err' => '此用户已存在，不可以注册');
        return $prompt;
    }
	
    //判断手机号是否存在
	$mysql_phone = "select phone from {$tb_prefix}_user where phone = ?";
	$phone_query = $pdo->prepare($mysql_phone);
	$phone_query->execute(array($phone));
    $resultphone = $phone_query->fetch(PDO::FETCH_ASSOC);
    if($resultphone['phone'] == $phone){
        $prompt = array('title' => 'phone','result_err' => '此手机号已存在，不可以注册');
        return $prompt;
    }

    //判断邮箱是否存在
	$mysql_email = "select email from {$tb_prefix}_user where email = ?";
	$email_query = $pdo->prepare($mysql_email);
	$email_query->execute(array($email));
    $resultemail = $email_query->fetch(PDO::FETCH_ASSOC);
    if($resultemail['email'] == $email){
        $prompt = array('title' => 'email','result_err' => '此邮箱已存在，不可以注册');
        return $prompt;
    }

    //判断公司是否存在
    if($register_type == 2){
		$mysql_corporate = "select corporate_name from {$tb_prefix}_user where corporate_name = ?";
		$corporate_query = $pdo->prepare($mysql_corporate);
		$corporate_query->execute(array($corporate_name));
		$resultcorporate = $corporate_query->fetch(PDO::FETCH_ASSOC);
        if($resultcorporate['corporate_name'] == $corporate_name){
            $prompt = array('title' => 'corporate_name','result_err' => '此公司已注册，不可以注册');
            return $prompt;
        }
    }
}

function cj_user(){
    global $pdo,$tb_prefix,$nickname,$corporate_name,$phone,$email,$password_ok,$register_type,$upload_size,$upload_num; //引入相关数据
    $register_time = date('Y-m-d H:i:s'); //获取注册时间
    $mysql_insert = "insert into {$tb_prefix}_user(user_name,corporate_name,phone,email,password,register_type,register_time,switch_type) values(?,?,?,?,?,?,?,?)";
    $query_insert = $pdo->prepare($mysql_insert);
    $data = array($nickname,$corporate_name,$phone,$email,$password_ok,$register_type,$register_time,1);
    $query_insert->execute($data);
    $res_insert = $pdo->lastinsertid();
    if($res_insert){
        $data_sql = "insert into {$tb_prefix}_upload_data(user_id,upload_size,upload_num) values(?,?,?)";
        $data_query = $pdo->prepare($data_sql);
        $data_query->execute(array($res_insert,$upload_size,$upload_num));
        $data_res = $pdo->lastinsertid();
        if($data_res){
            $_SESSION['user_name'] = $nickname;
            $prompt = array('success' => true);
        }
       return $prompt;
    }
}

