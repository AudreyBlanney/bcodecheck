<?php
include 'config/mysql_config.php';
include "secure_filtering.php";
include 'config/session.php';

$user_id = $_POST['user_id']; //获取用户id
$upload_size = !empty($_POST['upload_size']) ? trim($_POST['upload_size']) : 0; //上传大小限制
$upload_num = !empty($_POST['upload_num']) ? trim($_POST['upload_num']) : 0; //上传数量限制
$switch_type = !empty($_POST['switch_type']) ? trim($_POST['switch_type']) : 1; //是否启用
$content = !empty($_POST['content']) ? $_POST['content'] : ''; //备注
$register_type = !empty($_POST['register_type']) ? $_POST['register_type'] : ''; //用户类型

$xt_user = '';
if($register_type == 3){
    $user_name = !empty($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $corporate_name = !empty($_POST['corporate_name']) ? trim($_POST['corporate_name']) : '';
    $email = !empty($_POST['email']) ? trim($_POST['email']) :'';
    $phone = !empty($_POST['phone']) ? trim($_POST['phone']) :'';
    $password = !empty($_POST['password']) ? md5(trim($_POST['password']) ) : '';
    $password_ok = !empty($_POST['password_ok']) ? md5(trim($_POST['password_ok'])) : '';
    if($password != $password_ok){
        $prompt = array('success' => false,'res' => 'password_ok');
        die(json_encode($prompt));
    }
    $xt_user = " , user_name = '{$user_name}' , corporate_name = '{$corporate_name}' , email = '{$email}' , phone = '{$phone}'";
    if($password_ok){
        $xt_user = " , password = '{$password_ok}' , user_name = '{$user_name}' , corporate_name = '{$corporate_name}' , email = '{$email}' , phone = '{$phone}'";
    }
}
if($user_id != null){
    //修改用户是否启用状态，上传文件大小 次数，限制
        $user_sql = "update {$tb_prefix}_user set switch_type = ? , content = ? $xt_user where id = ?";
        $user_query = $pdo->prepare($user_sql);
        $user_res = $user_query->execute(array($switch_type,$content,$user_id));
        if($user_res){
            $data_sql = "update {$tb_prefix}_upload_data set upload_size = ?,upload_num = ? where user_id = ?";
            $data_user = $pdo->prepare($data_sql);
            $data_res = $data_user->execute(array($upload_size,$upload_num,$user_id));
            if($data_res){
                $prompt = array('success' => true);
                die(json_encode($prompt));
            }else{
                $prompt = array('success' => false);
                die(json_encode($prompt));
            }
        }else{
            $prompt = array('success' => false);
            die(json_encode($prompt));
        }
}

