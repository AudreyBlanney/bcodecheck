<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function login(){
        $this->display();
    }
    public function login_on(){
        $Model = M('user');
        $user_name = $_POST['user_name'] ? trim($_POST['user_name']) : '';
        $password = $_POST['password'] ? md5(trim($_POST['password'])) : '';
        if($_COOKIE){
            $session_id = $_COOKIE['PHPSESSID'];
        }else{
            $session_id = session_id();
        }
        if($user_name && $password) {
            $result = $Model->where("(user_name='%s' or email='%s' or phone='%s') and password='%s' and switch_type='%s'",$user_name,$user_name,$user_name,$password,1)->find();
            if($result){
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['user_name'] = $result['user_name'];

                //更新用户session_id
                $data['session_id']    =  $session_id;
                $up_res = $Model->where("id = '%s'",$result['id'])->save($data);
                if($up_res === false){
                    $prompt = array('success' => false,'res' => '用户名或密码错误');
                    die(json_encode($prompt));
                }else{
                    $prompt = array('success' => true);
                    die(json_encode($prompt));
                }
            } else {
                $prompt = array('success' => false,'res' => '用户名或密码错误');
                die(json_encode($prompt));
            }
        }else {
            $prompt = array('success' => false,'res' => '用户名或密码错误');
            die(json_encode($prompt));
        }
    }
}