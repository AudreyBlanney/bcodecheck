<?php


final class Info
{	
	// interesting functions, output and comment them if seen
	public static $F_INTEREST = array(
		'phpinfo'						=> 'phpinfo() 函数检测',
		'registerPHPFunctions'			=> 'registerPHPFunctions（）在XML允许代码执行',
		'session_start'					=> '使用 sessions',
		#'session_destroy'				=> 'session_destroy(), delete arbitrary file in PHP 5.1.2',
		'dbase_open' 					=> '正在使用 DBMS dBase',
		'dbplus_open' 					=> '正在使用 DBMS DB++',
		'dbplus_ropen' 					=> '正在使用 DBMS DB++',
		'fbsql_connect' 				=> '正在使用 DBMS FrontBase' ,
		'ifx_connect'					=> '正在使用 DBMS Informix',
		'db2_connect'					=> '正在使用 DBMS IBM DB2',
		'db2_pconnect'					=> '正在使用 DBMS IBM DB2',
		'ftp_connect'					=> '正在使用 FTP server', 
		'ftp_ssl_connect' 				=> '正在使用 FTP server', 
		'ingres_connect'				=> '正在使用 DBMS Ingres',
		'ingres_pconnect'				=> '正在使用 DBMS Ingres',
		'ldap_connect'					=> '正在使用 LDAP server',
		'msession_connect'	 			=> '正在使用 msession server',
		'msql_connect'					=> '正在使用 DBMS mSQL',
		'msql_pconnect'					=> '正在使用 DBMS mSQL',
		'mssql_connect'					=> '正在使用 DBMS MS SQL',
		'mssql_pconnect'				=> '正在使用 DBMS MS SQL',
		'mysql_connect'					=> '正在使用 DBMS MySQL',
		#'mysql_escape_string'			=> 'insecure mysql_escape_string',
		'mysql_pconnect'				=> '正在使用 DBMS MySQL',
		'mysqli'						=> '正在使用 DBMS MySQL, MySQLi Extension',
		'mysqli_connect'				=> '正在使用 DBMS MySQL, MySQLi Extension',
		'mysqli_real_connect'			=> '正在使用 DBMS MySQL, MySQLi Extension',
		'oci_connect'					=> '正在使用 DBMS Oracle OCI8',
		'oci_new_connect'				=> '正在使用 DBMS Oracle OCI8',
		'oci_pconnect'					=> '正在使用 DBMS Oracle OCI8',
		'ocilogon'						=> '正在使用 DBMS Oracle OCI8',
		'ocinlogon'						=> '正在使用 DBMS Oracle OCI8',
		'ociplogon'						=> '正在使用 DBMS Oracle OCI8',
		'ora_connect'					=> '正在使用 DBMS Oracle',
		'ora_pconnect'					=> '正在使用 DBMS Oracle',
		'ovrimos_connect'				=> '正在使用 DBMS Ovrimos SQL',
		'pg_connect'					=> '正在使用 DBMS PostgreSQL',
		'pg_pconnect'					=> '正在使用 DBMS PostgreSQL',
		'sqlite_open'					=> '正在使用 DBMS SQLite',
		'sqlite_popen'					=> '正在使用 DBMS SQLite',
		'SQLite3'						=> '正在使用 DBMS SQLite3',
		'sybase_connect'				=> '正在使用 DBMS Sybase',
		'sybase_pconnect'				=> '正在使用 DBMS Sybase',
		'TokyoTyrant'					=> '正在使用 DBMS TokyoTyrant',
		'xptr_new_context'				=> '正在使用 XML document',
		'xpath_new_context'				=> '正在使用 XML document'
	);	
	
	// interesting functions for POP/Unserialze
	public static $F_INTEREST_POP = array(
		'__autoload'					=> 'function __autoload',
		'__destruct'					=> 'POP 工具 __destruct',
		'__wakeup'						=> 'POP 工具 __wakeup',
		'__toString'					=> 'POP 工具 __toString',
		'__call'						=> 'POP 工具 __call',
		'__callStatic'					=> 'POP 工具 __callStatic',
		'__get'							=> 'POP 工具 __get',
		'__set'							=> 'POP 工具 __set',
		'__isset'						=> 'POP 工具 __isset',
		'__unset'						=> 'POP 工具 __unset'
	);
	
	// interesting functions regarding cryptography
	public static $F_INTEREST_CRYPTO = array(
		'base64_encode'					=> '请记住，你的编码的在这里，没有任何隐藏.', 
		'base64_decode'					=> '请记住，你的解码的在这里，没有任何隐藏.', 
		'crc32'							=> '不要使用CRC32任何加密实现，它呈线性关系 (see Gregor Kopf\'s Joomla exploit).', 
		'crypt'							=> '', 
		'hash'							=> '哈希在这里产生。确保哈希不剪裁，总是比较类型安全（见TYPO3-SA-2010-020）。彩虹表猜解密码，以防止敏感数据，比如存储时，你可能想使用的salt.', 
		'md5'							=> '这里生成的MD5哈希值。确保哈希不剪裁，总是比较类型安全（见TYPO3-SA-2010-020）。彩虹表猜解密码，以防止敏感数据，比如存储时，你可能想使用的salt', 
		'mt_srand'						=> '有没有需要生成新的PRNG种子。如果你这样做，只做一次为您的服务器和存储的种子。不要多次新种子将毁掉雪崩效应 (见 Gregor Kopf\'s Joomla exploit).', 
		'rand'							=> 'RAND（）的输出被限制在32767值默认情况下，可以暴力破解。', 
		'sha1'							=> 'SHA1哈希在这里产生。确保哈希不剪裁，总是比较类型安全（见TYPO3-SA-2010-020）。彩虹表猜解密码，以防止敏感数据，比如存储时，你可能想使用的salt.', 
		'mt_srand'						=> '有没有需要生成新的PRNG种子。如果你这样做，只做一次为您的服务器和存储的种子。不要多次新种子将毁掉雪崩效应 (见 Gregor Kopf\'s Joomla exploit).', 
		'str_rot13'						=> '请记住，你移动的在这里，没有任何隐藏.',
		'mcrypt_cbc'					=> 'CBC模式中的数据加密/解密',
		'mcrypt_cfb'					=> 'CFB模式中的数据加密/解密',
		'mcrypt_create_iv'				=> '创建一个初始化向量（IV）从一个随机源',
		'mcrypt_decrypt'				=> '解密加密文字与给定的参数',
		'mcrypt_ecb'					=> '不赞成使用：ECB模式中的数据加密/解密',
		'mcrypt_encrypt'				=> '加密的明文与给定的参数',
		'mcrypt_generic_init'			=> '这个函数初始化加密所需的所有缓冲区',
		'mcrypt_generic'				=> '此功能加密数据',
		'mcrypt_module_open' 			=> '在模块的算法，要使用的模式',
		'mcrypt_ofb' 					=> '在OFB模式的加密/解密数据',
		'mdecrypt_generic'				=> '解密数据'
	);
}

?>	