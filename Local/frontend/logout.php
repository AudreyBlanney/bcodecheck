<?php
session_start();
include "mysql_config.php";
include "history_data.php";
$username = $_SESSION['user_name'];
$mysql_str = "update {$tb_prefix}_user set login_id = ?,last_time = ? where user_name = ?";
$query = $pdo->prepare($mysql_str);
$time = time();
$close = $query->execute(array(1,$time,$username));
if($close){
    $res_his = history_data($username,'退出代码审查系统','成功',2,date('Y-m-d H:i:s'));
    session_unset();
    session_destroy();
    header('Location: login.html');
}

?>	
