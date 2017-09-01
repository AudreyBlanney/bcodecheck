<?php
namespace Home\Controller;
use Common\Controller\BaseController;
class LogoutController extends BaseController {
    public function logout(){
        session_unset();
        session_destroy();
        $this->redirect('Login/login');
    }
}