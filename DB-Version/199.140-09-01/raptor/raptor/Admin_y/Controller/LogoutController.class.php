<?php
namespace Admin\Controller;
use Common\Controller\BaseadminController;
class LogoutController extends BaseadminController {
    public function logout(){
        session_unset();
        session_destroy();
        $this->redirect('Login/login');
    }
}