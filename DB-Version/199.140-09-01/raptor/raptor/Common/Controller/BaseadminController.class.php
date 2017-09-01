<?php
namespace Common\Controller;
use Think\Controller;
class BaseadminController extends Controller {
    public function _initialize(){
		if(empty($_SESSION['admin_user_id'])) {
            $this->redirect('Login/login');
		}
    }
}