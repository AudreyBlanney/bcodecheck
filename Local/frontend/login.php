<?php
session_start();
?>

<!DOCTYPE html>
<html lang="zh">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>登录--源代码分析平台</title>
<?php
include "mysql_config.php";
//生成10位随机login_id
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
for ( $i = 0; $i < 10; $i++ ) 
{ 
$login_id .= $chars[ mt_rand(0, strlen($chars) - 1) ]; 
} 
//查询数据库中的login_id和last_time
$mysql_str = "select login_id,last_time from user where user = ?";
$query = $pdo->prepare($mysql_str);
$login_time = $query->execute(array($_SESSION['user_name']));
$login_time = $query->fetch(PDO::FETCH_ASSOC);
if (($login_time['login_id'] == $_SESSION['login_id']) && ($login_time['last_time']+600 >= time())){
 header('Location: index.php');
}

$timeout_duration = 60;
if($_POST['submit']!= NULL){
if(!trim($_POST['username'])=="" && !($_POST['password']==NULL)){
     @$username = $_POST['username'];
     @$password = $_POST['password'];
    // $mysql_str = "select * from user where user='$username' and passwd='$password' ";
    $mysql_str = "select * from user where user=? and passwd=?";
    $query = $pdo->prepare($mysql_str);
    $row_column = $query->execute(array($username,$password)); 
  //  var_dump($row_column);
    //$row_column= $pdo->query($mysql_str);
    $row = $query->fetch(PDO::FETCH_ASSOC);
	if($row)//->fetchColumn() >0 )
     { 

        $mysql_str = "select login_id,last_time from user where user = ?";
        $query = $pdo->prepare($mysql_str);
        $login_time = $query->execute(array($username));
        $login_time = $query->fetch(PDO::FETCH_ASSOC); 
        if ($login_time['login_id'] == '0'){
            $_SESSION['login_id'] = $login_id;
            $_SESSION['user_name'] = $username;
            $_SESSION['LAST_ACTIVITY'] = $_SERVER['REQUEST_TIME']; 
            $_SESSION['current_scan_report'] = '';
            $mysql_str = "update user set login_id = ?,last_time=? where user = ?";
            $query = $pdo->prepare($mysql_str);
            $time = time();
            $row_column = $query->execute(array($login_id,$time,$username));
            header('Location: index.php'); 


        } else {
          if($login_time['last_time']+600 <= time()){
            $_SESSION['login_id'] = $login_id;
            $_SESSION['user_name'] = $username;
            $_SESSION['LAST_ACTIVITY'] = $_SERVER['REQUEST_TIME']; 
            $_SESSION['current_scan_report'] = '';
            $mysql_str = "update user set login_id = ?,last_time=? where user = ?";
            $query = $pdo->prepare($mysql_str);
            $time = time();
            $row_column = $query->execute(array($login_id,$time,$username));
            header('Location: index.php');
       } else {
?>
<script>alert("用户已登陆！")</script>;
<?php
              } }
     } else {
?>
<script>alert("用户名或密码错误！")</script>;
<?php
}
} else if (!empty($_SESSION['user_name']) && $_SESSION['LAST_ACTIVITY'] > $timeout_duration) {
  header('Location: index.php');

} else {
?>
<script>alert("用户名或密码格式有误！")</script>;
<?php
}
}
?>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <script src="dist/js/jquery-1.11.1.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>

    <!-- Bootstrap Modal Dialog JS/CSS -->
    <script src="dist/js/bootstrap-dialog.min.js"></script>
    <link href="dist/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <style>
        .login-dialog .modal-dialog {
          width: 300px;
        }
        html { width: 100%; height:100%; overflow:hidden; }
        body { 
  width: 100%;
  height:100%;
  font-family: 'Open Sans', sans-serif;
  background: #092756;
  background: -moz-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%),-moz-linear-gradient(top,  rgba(57,173,219,.25) 0%, rgba(42,60,87,.4) 100%), -moz-linear-gradient(-45deg,  #670d10 0%, #092756 100%);
  background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -webkit-linear-gradient(top,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -webkit-linear-gradient(-45deg,  #670d10 0%,#092756 100%);
  background: -o-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -o-linear-gradient(top,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -o-linear-gradient(-45deg,  #670d10 0%,#092756 100%);
  background: -ms-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), -ms-linear-gradient(top,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), -ms-linear-gradient(-45deg,  #670d10 0%,#092756 100%);
  background: -webkit-radial-gradient(0% 100%, ellipse cover, rgba(104,128,138,.4) 10%,rgba(138,114,76,0) 40%), linear-gradient(to bottom,  rgba(57,173,219,.25) 0%,rgba(42,60,87,.4) 100%), linear-gradient(135deg,  #670d10 0%,#092756 100%);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3E1D6D', endColorstr='#092756',GradientType=1 );
}
    </style>
  </head>

  <body>

    <div class="container">
      <form method="POST" action="login.php" class="form-signin" role="form">
        <h2 class="form-signin-heading">Code Check</h2>
        <label for="inputEmail" class="sr-only">用户名</label>
        <input type="input" id="inputEmail" name="username" class="form-control" placeholder="用户名" required autofocus>
        <label for="inputPassword" class="sr-only">密码</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="密码" required>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> 记住我
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" name="submit" value = "submit"type="submit">登 录</button>
      </form>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="dist/js/heartbeat.js"></script>
</body>
<?php
$pdo=NULL;
?>
</html>
