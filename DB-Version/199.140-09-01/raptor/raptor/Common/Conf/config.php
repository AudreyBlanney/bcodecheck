<?php
return array(
	//数据库配置信息
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'codecheck', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => '4869', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PARAMS' => array(PDO::ATTR_PERSISTENT => true), // 数据库连接参数PDO
	'DB_PREFIX' => 'obsec_', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG'  =>  true, // 数据库调试模式 开启后可以记录SQL日志
    'DEFAULT_FILTER' => 'htmlspecialchars', //xss过滤
);
