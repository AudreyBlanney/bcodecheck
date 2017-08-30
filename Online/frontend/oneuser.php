<?php
session_start();
include "mysql_config.php";
$mysql_str = "select login_id from user where user = ?";
$query = $pdo->prepare($mysql_str);
$login_ids = $query->execute(array($_SESSION['user_name']));
$login_ids = $query->fetch(PDO::FETCH_ASSOC);
if (($_SESSION['login_id'] == NULL) or ( $_SESSION['login_id'] != $login_ids['login_id'])){
 header('Location: login.php');
} else {
 $mysql_str = "update user set last_time = ? where user = ?";
$query = $pdo->prepare($mysql_str);
$uptime = $query->execute(array(time(),$_SESSION['user_name']));
$uptime = $query->fetch(PDO::FETCH_ASSOC);
}
?>
