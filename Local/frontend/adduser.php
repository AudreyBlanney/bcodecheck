<?php
include ("oneuser.php");
include("session.php");
?>

<!DOCTYPE html>
<html lang="zh"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="assets/css/glyphicons.css" rel="stylesheet">
    <link rel="icon" href="/favicon.ico">

    <title>添加用户--源代码分析平台</title>
<?php
if($_POST['submit']!= NULL){
	if(trim($_POST['username']) && trim($_POST['password']) && trim($_POST['repassword'])){
	$username=($_POST['username']);
	$password=($_POST['password']);
	$repassword=($_POST['repassword']);
	if(gettype(strpos($username," ")) !== "integer" && gettype(strpos($password," ")) !== "integer"){
	if($password === $repassword){
	$mysql_str="insert into user(user,passwd) values('$username','$password')";
	$row_column= $pdo->query($mysql_str);
	if($row_column){
?>
<script>alert("添加用户成功!")</script>
<?php
			} else {
?>
<script>alert("添加用户失败！可能用户名已经存在！")</script>
<?php
			}
} else {
?>
<script>alert('两次密码不一致!')</script>
<?php
}	
	} else {
?>
<script>alert('用户名密码不能包含空格！')</script>
<?php
		}	
      } else {
?>
<script>alert('用户名密码格式有误！')</script>
<?php
	}
}
?>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <!-- Bootstrap Modal Dialog JS/CSS -->
    <script src="dist/js/bootstrap-dialog.min.js"></script>
    <link href="dist/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css">

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <style>
        .login-dialog .modal-dialog {
          width: 300px;
        }
    </style>
  </head>
<style type="text/css">
.containerw { margin: 25px auto; position: relative; width: 40%; }
#content {
  background: #f9f9f9;
  background: -moz-linear-gradient(top,  rgba(248,248,248,1) 0%, rgba(249,249,249,1) 100%);
  background: -webkit-linear-gradient(top,  rgba(248,248,248,1) 0%,rgba(249,249,249,1) 100%);
  background: -o-linear-gradient(top,  rgba(248,248,248,1) 0%,rgba(249,249,249,1) 100%);
  background: -ms-linear-gradient(top,  rgba(248,248,248,1) 0%,rgba(249,249,249,1) 100%);
  background: linear-gradient(top,  rgba(248,248,248,1) 0%,rgba(249,249,249,1) 100%);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f8f8f8', endColorstr='#f9f9f9',GradientType=0 );
  -webkit-box-shadow: 0 1px 0 #fff inset;
  -moz-box-shadow: 0 1px 0 #fff inset;
  -ms-box-shadow: 0 1px 0 #fff inset;
  -o-box-shadow: 0 1px 0 #fff inset;
  box-shadow: 0 1px 0 #fff inset;
  border: 1px solid #c4c6ca;
  margin: 0 auto;
  padding: 25px 0 0;
  position: relative;
  text-align: center;
  text-shadow: 0 1px 0 #fff;
  width: 400px;
}

</style>
  <body>

<!--     <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Raptor: Source Code Scanner</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="/">Dashboard</a></li>
        <li><a href="/">Settings</a></li>
        <li><a href="/"><php echo $_SESSION['user_name'];?></a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
      <form class="navbar-form navbar-right">
        <input type="text" class="form-control" placeholder="Search...">
      </form>
    </div>
  </div>
</nav> -->

<nav class="navbar navbar-theme-blue navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand comp-logo" href="scan.php"><strong><img src="assets/img/QSAM.png"  width="115" height="44"/></strong></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
          <ul class="nav navbar-nav nav-pad">
            <li><a href="scan.php">代码扫描</a></li>
            <li><a href="issues.php">漏洞视图</a></li>
            <li><a href="analytics.php">结果分析</a></li>
            <li><a href="history.php">历史记录</a></li>
            <li><a href="editrules.php">添加规则</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right user-info">
             <li><span class="user-info-core">你好，<?php echo $_SESSION['user_name'];?></span></li>
            <li><a href="logout.php">注销</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12 main">
          <h3 class="page-header">添加用户</h3>
<!--           <iframe src="rules.php" style="border: 0px; height: 580px; width: 100%"></iframe> -->
      </div>

<div class="containerw">
<form class="form-horizontal" role="form" action="" method="post">
  <div class="form-group">
    <label for="firstname" class="col-sm-4 control-label">用户名</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" name="username" required="" placeholder="请输入用户名">
    </div>
  </div>
  <div class="form-group">
    <label for="lastname" class="col-sm-4 control-label">密码</label>
    <div class="col-sm-6">
      <input type="password" class="form-control" name="password" required="" placeholder="请输入密码">
    </div>
  </div>
 <div class="form-group">
    <label for="lastname" class="col-sm-4 control-label">确认密码</label>
    <div class="col-sm-6">
      <input type="password" class="form-control" name="repassword" required="" placeholder="请确认密码">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-8">
      <input type="submit" name="submit" class="btn btn-primary" value="添加" />
    </div>
  </div>
</form>    
</div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="dist/js/jquery-1.11.1.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <script src="assets/js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>


<div id="global-zeroclipboard-html-bridge" class="global-zeroclipboard-container" style="position: absolute; left: 0px; top: -9999px; width: 15px; height: 15px; z-index: 999999999;">      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="global-zeroclipboard-flash-bridge" width="100%" height="100%">         <param name="movie" value="assets/flash/ZeroClipboard.swf?noCache=1417502908088">         <param name="allowScriptAccess" value="never">         <param name="scale" value="exactfit">         <param name="loop" value="false">         <param name="menu" value="false">         <param name="quality" value="best">         <param name="bgcolor" value="#ffffff">         <param name="wmode" value="transparent">         <param name="flashvars" value="">         <embed src="assets/flash/ZeroClipboard.swf?noCache=1417502908088" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="100%" height="100%" name="global-zeroclipboard-flash-bridge" allowscriptaccess="never" allowfullscreen="false" type="application/x-shockwave-flash" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="" scale="exactfit">                </object></div><svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200" preserveAspectRatio="none" style="visibility: hidden; position: absolute; top: -100%; left: -100%;"><defs></defs><text x="0" y="10" style="font-weight:bold;font-size:10pt;font-family:Arial, Helvetica, Open Sans, sans-serif;dominant-baseline:middle">200x200</text></svg>
<script src="dist/js/heartbeat.js"></script>
<?php
$pdo=NULL;
?>
