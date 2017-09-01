<?php
namespace Home\Controller;
use Common\Controller\BaseController;
class UserinfoController extends BaseController {
    public function userinfo(){
        $Model = M('user');
        $user_id = $_SESSION['user_id'];
        $user_info = $Model->where("id = '%s'",$user_id)->field('user_name,phone,email,register_time')->find();
        $this->assign('user_info',$user_info);
        $this->display();
    }
}