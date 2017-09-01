<?php
namespace Home\Controller;
use Think\Controller;
class SigninController extends Controller {
    public  $corporate_name = ''; //公司名称
    public $upload_size = ''; //上传文件大小
    public $upload_num = ''; //上传文件数量
    public $nickname = ''; //昵称
    public $phone = ''; //手机号
    public  $phone_code = ''; //手机验证码
    public  $email = ''; //邮箱
    public  $password = ''; //密码
    public  $password_ok = ''; //确认密码
    public  $register_type = ''; //注册类型

    //注册页面加载
    public function signin(){
        $this->display();
    }

    //用户注册
    public function register(){
        $email_array = C('EMAIL_ARRAY');
        if (IS_POST){
            $this->nickname = trim(!empty($_POST['nickname'])) ? trim($_POST['nickname']) : '';//昵称
            $this->phone = !empty($_POST['phone']) ? trim($_POST['phone']) : '';//手机号
            $this->phone_code = !empty($_POST['phone_code']) ? trim($_POST['phone_code']) : '';//手机验证码
            $this->email = !empty($_POST['email']) ? trim($_POST['email']) : '';//邮箱
            $this->password = !empty($_POST['password']) ? trim($_POST['password']) : '';//密码
            $this->password_ok = !empty($_POST['password_ok']) ? md5(trim($_POST['password_ok'])) : '';//确认密码
            $this->register_type = !empty($_POST['register_type']) ? $_POST['register_type'] : 1;//注册类型 1:个人注册 2：企业注册
            if($this->register_type == 2){
                if(empty($_POST['corporate_name'])){
                    $prompt = array('title' => 'corporate_name','result_err' => '公司名称格式不对，请重新输入');
                    die(json_encode($prompt));
                }
                $this->corporate_name = $_POST['corporate_name']; //公司名称
            }

            //设置不同用户的上传文件大小
            if($this->register_type == 1){
                $this->upload_size = C('PER_UPLOAD_SIZE');
                $this->upload_num = C('PER_UPLOAD_NUM');
            }elseif($this->register_type == 2){
                $this->upload_size = C('ENT_UPLOAD_SIZE');
                $this->upload_num = C('ENT_UPLOAD_NUM');
            }
            //昵称验证 中文 数字 字母
            if(!$this->nickname || !preg_match("/^[\x7f-\xff0-9a-zA-Z]{3,15}$/",$this->nickname)){
                $prompt = array('title' => 'nickname','result_err' => '用户名格式不对，从重新输入');
                die(json_encode($prompt));
            }

            //手机号验证
            if(!$this->phone || !preg_match('/^1\d{10}$/',$this->phone)){
                $prompt = array('title' => 'phone','result_err' => '手机号格式不对，请重新输入');
                die(json_encode($prompt));
            }

            //验证码验证
            if(empty($_SESSION['phone_code']) || empty($_SESSION) || !$_SESSION['phone_code']){
                $prompt = array('title' => 'phone_code','result_err' => '验证码不正确，请重新输入');
                unset($_SESSION['phone_code']);
                die(json_encode($prompt));
            }
            if($this->phone_code != $_SESSION['phone_code']){
                $prompt = array('title' => 'phone_code','result_err' => '验证码不正确，请重新输入');
                unset($_SESSION['phone_code']);
                die(json_encode($prompt));
            }

            //验证码时间验证
            if($_SESSION['phone_time'] + 60 < time()){
                unset($_SESSION['phone_time']);
                unset($_SESSION['phone_code']);
                $prompt = array('title' => 'phone_code','result_err' => '验证码超时，请重新获取');
                die(json_encode($prompt));
            }
            /*if($this->phone_code != 123456){
                $prompt = array('title' => 'phone_code','result_err' => '验证码不正确，请重新输入');
                die(json_encode($prompt));
            }*/

            //邮箱验证
            if(!$this->email || !preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i",$this->email)){
                $prompt = array('title' => 'email','result_err' => '邮箱格式不正确，请重新输入');
                die(json_encode($prompt));
            }

            // 如果企业注册，判断是否是企业邮箱
            if($this->register_type == 2){
                $get_suffix = explode('@',$this->email);
                $suffix = $get_suffix[1];
                if(in_array($suffix,$email_array)){
                    $prompt = array('title' => 'email','result_err' => '您的邮箱不是企业邮箱，请您重新输入');
                    die(json_encode($prompt));
                }
            }

            //密码验证 .数字，字母，标点
            if(!$this->password || !preg_match("/^[a-zA-Z0-9,，、。?!.'？！@#$%^&*·`~<>；;‘'【】]{6,20}$/",$this->password)){
                $prompt = array('title' => 'password','result_err' => '密码格式不对，请重新输入');
                die(json_encode($prompt));
            }

            //确认密码
            if(md5($this->password) != $this->password_ok){
                $prompt = array('title' => 'password_ok','result_err' => '两次密码不一样，请重新输入');
                die(json_encode($prompt));
            }

            //判断用户信息是否存在
            if($data_result = $this->existence()){
                die(json_encode($data_result));
            }

            //用户数据插入
            die(json_encode($this->cj_user()));
        }else{
            $this->error('非法请求');
        }
    }

    //用户查询是否已经注册
    private  function existence(){
        //判断昵称是否存在
        $Model = M('user');
        $resultnick = $Model->where("user_name='%s'",$this->nickname)->find();
        if($resultnick['user_name'] == $this->nickname){
            $prompt = array('title' => 'nickname','result_err' => '此用户已存在，不可以注册');
            return $prompt;
        }

        //判断手机号是否存在
        $resultphone =  $Model->where("phone='%s'",$this->phone)->find();
        if($resultphone['phone'] == $this->phone){
            $prompt = array('title' => 'phone','result_err' => '此手机号已存在，不可以注册');
            return $prompt;
        }

        //判断邮箱是否存在
        $resultemail = $Model->where("email='%s'",$this->email)->find();
        if($resultemail['email'] == $this->email){
            $prompt = array('title' => 'email','result_err' => '此邮箱已存在，不可以注册');
            return $prompt;
        }

        //判断公司是否存在
        if($this->register_type == 2){
            $resultcorporate = $Model->where("corporate_name='%s'",$this->corporate_name)->find();
            if($resultcorporate['corporate_name'] == $this->corporate_name){
                $prompt = array('title' => 'corporate_name','result_err' => '此公司已注册，不可以注册');
                return $prompt;
            }
        }
    }

    //录入用户数据信息
    private function cj_user(){
        //录入用户信息
        $register_time = date('Y-m-d H:i:s'); //获取注册时间
        $register_date = date('Y-m-d'); //获取注册日期
        $Model = M('user');
        $Model->user_name = $this->nickname;
        $Model->corporate_name = $this->corporate_name;
        $Model->phone = $this->phone;
        $Model->email = $this->email;
        $Model->password = $this->password_ok;
        $Model->register_type = $this->register_type;
        $Model->register_time = $register_time;
        $Model->register_date = $register_date;
        $Model->switch_type = 1;
        $user_id =  $Model->add();
        if($user_id){
            //该用户上传文件限制
            $data_model = M('upload_data');
            $data_model->user_id = $user_id;
            $data_model->upload_size = $this->upload_size;
            $data_model->upload_num = $this->upload_num;
            if($data_model->add()){
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $this->nickname;
                $prompt = array('success' => true);
            }else{
                $prompt = array('success' => false);
            }
        }else{
            $prompt = array('success' => false);
        }
        return $prompt;
    }
}
