<?php
namespace Home\Controller;
use Think\Controller;
class VerificationController extends Controller {

    //创蓝发送短信接口URL, 如无必要，该参数可不用修改
    const API_SEND_URL='http://sms.253.com/msg/send';

    //创蓝短信余额查询接口URL, 如无必要，该参数可不用修改
    const API_BALANCE_QUERY_URL='http://sms.253.com/msg/balance';

    const API_ACCOUNT='N2247200';//创蓝账号 替换成你自己的账号

    const API_PASSWORD='Al4yY0odi';//创蓝密码 替换成你自己的密码

    public function phone_code(){
        //获取信息发送验证码
        $phone_code = rand(100000,999999);
        $data = C('PHONE_CODE') . $phone_code ;
        //获取手机号，判断手机号是否正确
        $phone = $_POST['phone'] ? trim($_POST['phone']) : ''; //获取手机号
        if(!preg_match("/^1\d{10}$/",$phone) || !$phone){
            $prompt = array('success' => false,'res' => '您输入的手机号格式不对，请您重新输入');
            die(json_encode($prompt));
        }

        //判断手机号是否存在
        $Model = M('user');
        $resphone = $Model->where("phone='%s'",$phone)->find();
        if($resphone['phone']){
            $prompt = array('success' => false,'res' => '此手机号已存在，不可以注册');
            die(json_encode($prompt));
        }

        //获取该手机号获取验证码的次数
        $_SESSION['phone_num'] = !empty($_SESSION['phone_num']) ? $_SESSION['phone_num'] : 1;
        if(!empty($_SESSION['user_phone']) && $_SESSION['user_phone'] == $phone){
            $_SESSION['phone_num'] +=1;
        }else{
            unset($_SESSION['phone_num']);
        }
        //判断该手机号获取验证码的次数
        if(!empty($_SESSION['phone_num']) && $_SESSION['phone_num'] >= 10){
            $prompt = array('success' => false,'res' => '获取短信次数过多，请稍后再试');
            die(json_encode($prompt));
        }

        //发送验证码
        if(!empty($_SESSION['phone_time']) && $_SESSION['phone_time'] + 60 > time()){
            $prompt = array('success' => false,'res' => '请60秒后再获取');
            die(json_encode($prompt));
        }else{
            $result = $this->sendSMS($phone, $data);
            $result = $this->execResult($result);
            if(isset($result[1]) && $result[1]==0){
                //设置获取验证码的时间
                $_SESSION['phone_time'] = time();
                $_SESSION['phone_code'] = $phone_code;
                $_SESSION['user_phone'] = $phone;
                $prompt = array('success' => true,'res' => '验证码发送成功');
                die(json_encode($prompt));
            }else{
                $prompt = array('success' => false,'res' => '验证码发送失败');
                die(json_encode($prompt));
            }
        }
    }

    /**
     * 发送短信
     *
     * @param string $mobile 		手机号码
     * @param string $msg 			短信内容
     * @param string $needstatus 	是否需要状态报告
     */
    private function sendSMS( $mobile, $msg, $needstatus = 1) {

        //创蓝接口参数
        $postArr = array (
            'un' => self::API_ACCOUNT,
            'pw' => self::API_PASSWORD,
            'msg' => $msg,
            'phone' => $mobile,
            'rd' => $needstatus
        );

        $result = $this->curlPost( self::API_SEND_URL , $postArr);
        return $result;
    }

    /**
     * 查询额度
     *
     *  查询地址
     */
    private function queryBalance() {
        //查询参数
        $postArr = array (
            'un' => self::API_ACCOUNT,
            'pw' => self::API_PASSWORD,
        );
        $result = $this->curlPost(self::API_BALANCE_QUERY_URL, $postArr);
        return $result;
    }

    /**
     * 处理返回值
     *
     */
    private function execResult($result){
        $result=preg_split("/[,\r\n]/",$result);
        return $result;
    }

    /**
     * 通过CURL发送HTTP请求
     * @param string $url  //请求URL
     * @param array $postFields //请求参数
     * @return mixed
     */
    private function curlPost($url,$postFields){
        $postFields = http_build_query($postFields);
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postFields );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }

    //魔术获取
    public function __get($name){
        return $this->$name;
    }

    //魔术设置
    public function __set($name,$value){
        $this->$name=$value;
    }

}