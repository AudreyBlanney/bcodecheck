<?php
namespace Jd400bocde690obsec6007\Controller;
use Common\Controller\BaseadminController;
class ChangepasController extends BaseadminController {
    public function changepas(){
        $this->display();
    }
    //修改密码
    public function modify_pas(){
        $Model = M('admin_user');
        $admin_user_id = $_SESSION['admin_user_id'];
        $ya_password = $_POST['ya_password'] ? md5(trim($_POST['ya_password'])) : '';//密码
        $password = $_POST['password'] ? trim($_POST['password']) : '';//密码
        $password_ok = $_POST['password_ok'] ? md5(trim($_POST['password_ok'])) : '';//确认密码

        //获取用户信息
        $res = $Model->where("id = '%s' and password = '%s'",$admin_user_id,$ya_password)->field('id,user_name')->find();

        if(empty($res)){
            $prompt = array('title' => 'ya_password','result_err' => '原密码不对，请重新输入');
            die(json_encode($prompt));

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
        //更新密码
        $up_data['password'] =  $password_ok;
        $up_res = $Model->where("id = '%s' and password = '%s'",$admin_user_id,$ya_password)->save($up_data);
        if($up_res){
            $prompt = array('success' => true);
            die(json_encode($prompt));
        }
    }
}