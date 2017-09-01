<?php
return array(
    //常用邮箱集合
    'EMAIL_ARRAY' => array('126.com','163.com','sina.com','21cn.com','sohu.com','yahoo.com.cn','tom.com','qq.com','etang.com',
        'eyou.com','56.com','x.cn','chinaren.com','sogou.com','citiz.com','gmail.com','msn.com','hotmail.com','aol.com',
        'ask.com','live.com','qq.com','0355.net','163.net','263.net','3721.net','yeah.net','googlemail.com','mail.com'),
    //个人用户的上传文件大小
    'PER_UPLOAD_SIZE' => 5, //上传文件大小
    'PER_UPLOAD_NUM' => 10, //每天上传数量
    //企业用户的上传文件大小
    'ENT_UPLOAD_SIZE' => 20, //上传文件大小
    'ENT_UPLOAD_NUM' => 10, //每天上传数量
    'PHONE_CODE' => '【北京匠迪科技有限公司】您好，您的验证码是:', //验证码推送信息
    'EMAIL_HOST' => 'smtp.obsec.net', //链接域名邮箱的服务器地址
    'FROMNAME' => '北京匠迪科技有限公司', //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
    'USERNAME' => 'BCodeCheck@obsec.net',//smtp登录的账号 这里填入字符串格式的邮箱即可
    'PASSWORD' => 'JDadmin!!!!',//smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）
    'UPLOAD_FILE' => '/var/raptor/uploads/', //上传文件路径

);