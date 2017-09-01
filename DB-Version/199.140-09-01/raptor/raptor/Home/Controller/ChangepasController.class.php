<?php
namespace Home\Controller;
use Common\Controller\BaseController;
class ChangepasController extends BaseController {
    public function changepas(){
        $this->display();
    }
    //修改用户密码
    public function modify_pas(){
        $Model = M('user');
        $user_id = $_SESSION['user_id'];
        $ya_password = $_POST['ya_password'] ? md5(trim($_POST['ya_password'])) : '';//密码
        $password = $_POST['password'] ? md5(trim($_POST['password'])) : '';//密码
        $password_ok = $_POST['password_ok'] ? md5(trim($_POST['password_ok'])) : '';//确认密码

        //获取用户信息
        $res = $Model->where("id = '%s' and password = '%s' and switch_type = '%s'",$user_id,$ya_password,1)->find();
        if(empty($res)){
            $prompt = array('title' => 'ya_password','result_err' => '原密码不对，请重新输入');
            die(json_encode($prompt));
        }

        //密码验证 .数字，字母，标点
        if(!$password && !preg_match("/^[a-zA-Z0-9,，、。?!.'？！@#$%^&*·`~<>；;‘'【】]{6,20}$/",$password)){
            $prompt = array('title' => 'password','result_err' => '密码格式不对，请重新输入');
            die(json_encode($prompt));
        }

        //确认密码
        if($password != $password_ok){
            $prompt = array('title' => 'password_ok','result_err' => '两次密码不一样，请重新输入');
            die(json_encode($prompt));
        }
        $data['password'] = $password_ok;
        $up_res = $Model->where("id = '%s' and password = '%s'",$user_id,$ya_password)->save($data);
        if($up_res){
            $prompt = array('success' => true);
            die(json_encode($prompt));
        }
    }
}