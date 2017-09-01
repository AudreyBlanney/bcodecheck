<?php
namespace Common\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function _initialize(){
		if(empty($_SESSION['user_id'])) {
            $this->redirect('Index/index');
		}
        //限制多用户登录
        $Model = M('user');
        $user_name = $_SESSION['user_name'];
        $user_id = $_SESSION['user_id'];
        if($_COOKIE){
            $session_id = $_COOKIE['PHPSESSID'];
        }else{
            $session_id = session_id();
        }
        $login_result = $Model->where("user_name='%s' and id='%s'",$user_name,$user_id)->field('session_id')->find();
        if(!empty($login_result['session_id']) && $login_result['session_id'] != $session_id){
            echo "
                <script> alert('登录账户已被其他用户登录，如不是本人操作，请及时修改密码！');
                    window.location.href='/Home/Logout/logout';
                </script>
            ";
        }
    }
}