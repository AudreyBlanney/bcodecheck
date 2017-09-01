<?php
namespace Home\Controller;
use Think\Controller;
class BackpwdController extends Controller {
    public function backpwd(){
        $this->display();
    }

    //发送邮箱验证码
    public function email(){
        $code = rand(100000,999999);
        $data = C('PHONE_CODE') . $code ;
        $email = $_POST['email'] ? $_POST['email'] : ' '; //收件人邮箱
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i"; //邮箱判断
        if (!preg_match( $pattern, $email ) || !$email){
            $prompt = array('success' => false,'res' => '您的邮箱格式不正确，请您重新输入');
            die(json_encode($prompt));
        }
        //发送邮件
        $flag = $this->sendMail($email,'匠迪科技',$data);
        if($flag){
            $_SESSION['email_time'] = time();
            $_SESSION['email_code'] = $code;
            $prompt = array('success' => true,'res' => '验证码发送成功');
            die(json_encode($prompt));
        }else{
            $prompt = array('success' => false,'res' => '验证码发送失败');
            die(json_encode($prompt));
        }
    }

    //后去邮件模板信息
   private function sendMail($to,$title,$content){
        //实例化PHPMailer核心类
        import('Vendor.PHPemail.phpmailer');
        $mail = new \PHPMailer();

        //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        $mail->SMTPDebug = 0;

        //使用smtp鉴权方式发送邮件
        $mail->isSMTP();

        //smtp需要鉴权 这个必须是true
        $mail->SMTPAuth=true;

        //链接域名邮箱的服务器地址
        $mail->Host = C('EMAIL_HOST');

        //设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';

        //设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
        $mail->Port = 465;

        //设置smtp的helo消息头 这个可有可无 内容任意
        // $mail->Helo = 'Hello smtp.qq.com Server';

        //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
        $mail->Hostname = '127.0.0.1';

        //设置发送的邮件的编码 可选GB2312
        $mail->CharSet = 'UTF-8';

        //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = C('FROMNAME');

        //smtp登录的账号 这里填入字符串格式的邮箱即可
        $mail->Username = C('USERNAME');

        //smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）
        $mail->Password = C('PASSWORD');

        //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
        $mail->From = C('USERNAME');

        //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
        $mail->isHTML(true);

        //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
        $mail->addAddress($to,C('FROMNAME'));

        //添加多个收件人 则多次调用方法即可
        // $mail->addAddress('xxx@163.com','lsgo在线通知');

        //添加该邮件的主题
        $mail->Subject = $title;

        //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
        $mail->Body = $content;

        //为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
        // $mail->addAttachment('./d.jpg','mm.jpg');
        //同样该方法可以多次调用 上传多个附件
        // $mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');

        $status = $mail->send();

        //简单的判断与提示信息
        if($status) {
            return true;
        }else{
            return false;
        }
    }
}