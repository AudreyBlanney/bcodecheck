<?php
session_start();
include "mysql_config.php";
$username = $_SESSION['user_name'];
$mysql_str = "update user set login_id = 0,last_time=? where user = ?";
$query = $pdo->prepare($mysql_str);
$time = time();
$close = $query->execute(array($time,$username));
session_destroy();
header('Location: login.php');
?>	
