<?php
include("oneuser.php");
include("session.php");
if ( ( !empty($_SESSION['git_repo']) || !empty($_SESSION['zip_name']) ) && empty($_SESSION['scan_active']) ) {
  $_SESSION['scan_active'] = true;
}

?>
<!DOCTYPE html>
<html lang="zh"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>源代码分析平台</title>

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
    </style>

  </head>
  <body>
<!--    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
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
            <li><a href="">Dashboard</a></li>
            <li><a href="">Settings</a></li>
            <li><a href=""><php echo $_SESSION['user_name']; ?></a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
        </div>
      </div>
    </nav>-->


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
            <li class="active"><a href="scan.php">代码扫描</a></li>
            <li><a href="issues.php">漏洞视图</a></li>
            <li><a href="analytics.php">结果分析</a></li>
            <li><a href="history.php">历史记录</a></li>
            <li><a href="editrules.php">添加规则</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right user-info">
          <li><span class="user-info-core">您好，<?php echo $_SESSION['user_name'];?></span></li>
            <li><a href="logout.php">注销</a></li>
<li><a href="adduser.php" class="glyphicon glyphicon-cog"></a></ li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
          <div class="col-md-12 main">
          <h1 class="page-header">
            <?php
              $loader_html = '';
              if (!empty($_SESSION['scan_active']) && ( !empty($_SESSION['git_repo']) || !empty($_SESSION['zip_name']) ) ) { 
                  if (!empty($_SESSION['git_repo'])) {
                    $scan_type  =  $_SESSION['git_repo'];
                  } else {
                    $scan_type = $_SESSION['zip_name'];
                  }
                    
                  echo "正在扫描: " . htmlentities($scan_type);
?>
<div id = "pre">
</div>
<?php
                  $loader_html = '<center><img src="assets/img/loader.gif" id="loader_img" style="margin-bottom: 10%; margin-top: 5%;" ><br />
                  <div style="width: 50%;" class="alert alert-danger" role="alert" id="notify">
                  <strong>请不要关闭当前窗口,直到扫描工作完成！</strong></div></center>';
              } else {
                echo "Idle";
              }
            ?>
          </h1>
 
          <br /><br />
          
          <?php
              echo $loader_html;
          ?>


        </div>
      </div>
    </div>
        <?php
          if( (!empty($_SESSION['git_repo']) || !empty($_SESSION['zip_name']) ) && !empty($_SESSION['scan_active']) ) {
              $ajax_element = '<script>
              
              function start_scan() {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "proxy.php", true);
                xhr.withCredentials = "true";
                xhr.onreadystatechange = function(e) {
                  if (this.readyState == 4 && this.status == 200) {
                    var resp = this.responseText;
                    console.log("proxy.php: Scan " + resp);
                    var notify_div = document.getElementById("notify");
                    var loader_img = document.getElementById("loader_img");
                    loader_img.parentNode.removeChild(loader_img);
                    notify_div.setAttribute("class", "alert alert-success");
                    notify_div.innerHTML = "<strong>扫描完成!</strong>";
                    setTimeout(function (){
                      location.href = "issues.php";
                    },2000);
                  }
                };
                xhr.send(); 
              }
              start_scan();

              </script>';

              echo $ajax_element;
            }
          ?>
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

<script>

var preid = setInterval("heartbeat()",1000);
function heartbeat() {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '/num.html',false);
  xhr.onreadystatechange = function(e) {
    if (this.readyState !== 4 && this.status !== 200) {
      BootstrapDialog.show(dialog_config);
  }};
  xhr.send();
  document.getElementById("pre").innerHTML="("+xhr.response+"%)"
  if (xhr.response == '扫描进度:100'){
        document.getElementById("pre").innerHTML="扫描完成，请等待跳转！"
	clearInterval(preid);
	location.href="issues.php";
  }

}
</script>
<?php
$pdo=NULL;
?>

</body></html>
