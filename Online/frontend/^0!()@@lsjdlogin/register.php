<?php
session_start();
include "config/mysql_config.php";
include "secure_filtering.php";
include 'config/session.php';

$user_name = trim($_POST['user_name']) ? trim($_POST['user_name']) : '';//昵称
$phone = $_POST['phone'] ? trim($_POST['phone']) : '';//手机号
$email = $_POST['email'] ? trim($_POST['email']) : '';//邮箱
$password = $_POST['password'] ? md5(trim($_POST['password'])) : '';//密码
$password_ok = $_POST['password_ok'] ? md5(trim($_POST['password_ok'])) : '';//确认密码
$register_type = !empty($_POST['register_type']) ? $_POST['register_type'] : 3;//注册类型 1:个人注册 2：企业注册 3:系统用户
$corporate_name = !empty($_POST['corporate_name']) ? trim($_POST['corporate_name']) : ' '; //公司名称
$upload_size = $_POST['upload_size'] ? trim($_POST['upload_size']) : ' '; //允许上传大小
$upload_num = $_POST['upload_num'] ? trim($_POST['upload_num']) : ' '; //允许上传次数
$switch_type = $_POST['switch_type'] ? trim($_POST['switch_type']) : 1; //是否启用
$content = $_POST['content'] ? $_POST['content'] : ' '; //备注

if(!$user_name){
    $prompt = array('title' => 'user_name','result_err' =>'用户名不能为空');
    die(json_encode($prompt));
}

//确认密码
if($password != $password_ok){
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
    global $pdo,$tb_prefix,$user_name;
    //判断昵称是否存在
    $mysql_nick = "select user_name,phone,email from {$tb_prefix}_user where user_name = ? and switch_type = ?";
    $nick_query = $pdo->prepare($mysql_nick);
    $nick_query->execute(array($user_name,1));
    $result = $nick_query->fetch(PDO::FETCH_ASSOC);
    if($result['user_name'] == $user_name){
        $prompt = array('title' => 'user_name','result_err' => '此用户已存在，不可以注册');
        return $prompt;
    }
}

function cj_user(){
    global $pdo,$tb_prefix,$user_name,$corporate_name,$phone,$email,$password_ok,$register_type,$upload_size,$upload_num,$content,$switch_type; //引入相关数据
    $register_time = date('Y-m-d H:i:s'); //获取注册时间
    $mysql_insert = "insert into {$tb_prefix}_user(user_name,corporate_name,phone,email,password,register_type,register_time,switch_type,content) values(?,?,?,?,?,?,?,?,?)";
    $query_insert = $pdo->prepare($mysql_insert);
    $data = array($user_name,$corporate_name,$phone,$email,$password_ok,$register_type,$register_time,$switch_type,$content);
    $query_insert->execute($data);
    $res_insert = $pdo->lastinsertid();
    if($res_insert){
        $data_sql = "insert into {$tb_prefix}_upload_data(user_id,upload_size,upload_num) values(?,?,?)";
        $data_query = $pdo->prepare($data_sql);
        $data_query->execute(array($res_insert,$upload_size,$upload_num));
        $data_res = $pdo->lastinsertid();
        if($data_res){
            $prompt = array('success' => true);
        }
       return $prompt;
    }
}

