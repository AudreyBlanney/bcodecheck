<?php
session_start();
include "mysql_config.php";
$username = $_SESSION['user_name'];
$mysql_str = "update {$tb_prefix}_user set login_id = ?,last_time = ? where user_name = ? or email = ? or phone = ?";
$query = $pdo->prepare($mysql_str);
$time = time();
$close = $query->execute(array(1,$time,$username,$username,$username));
if($close){
    session_unset();
    session_destroy();
    header('Location: login_not.php');
}

?>	
