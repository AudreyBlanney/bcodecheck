<?php
namespace Jd400bocde690obsec6007\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function login(){
        $this->display();
    }

    public function login_on(){
        $Model=M('admin_user');
        $user_name = $_POST['user_name'] ? trim($_POST['user_name']) : '';
        $password = $_POST['password'] ? md5(trim($_POST['password'])) : '';
        if($user_name && $password) {
            $row = $Model->where("user_name = '%s' and password = '%s'",$user_name,$password)->field('id,user_name')->find();
            if($row){
                $_SESSION['admin_user_name'] = $row['user_name'];
                $_SESSION['admin_user_id'] = $row['id'];
                $prompt = array('success' => true);
                die(json_encode($prompt));
            }else{
                $prompt = array('success' => false,'res' => '用户名或密码错误');
                die(json_encode($prompt));
            }
        }else{
            $prompt = array('success' => false,'res' => '用户名或密码错误');
            die(json_encode($prompt));
        }
    }
}