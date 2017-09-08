<?php
$host='localhost';
$dbname='codecheck';
$dsn = "mysql:host=$host;dbname=$dbname";
$root='root';
$mysql_pwd='4869';
$tb_prefix = 'obsec';
try
{
        $pdo = new PDO($dsn, $root, $mysql_pwd);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$pdo->exec("set names 'utf8'");
}
catch(PDOException $e)
{
        die('数据库连接错误！!');
}


?>
