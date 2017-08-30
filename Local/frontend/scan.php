<?php
include("oneuser.php");
include("session.php");

@$scan_name = $_REQUEST['scan_name'];
@$git_repo = $_REQUEST['git_repo'];
@$upload_id = $_REQUEST['upload_id'];
@$zip_name = $_REQUEST['zip_name'];


function normalize_git_path($git_repo) {  

if ( strstr($git_repo, $_SESSION['git_endpoint']['internal']) ) {
      $_SESSION['git_type'] = 'internal';
    } else if ( strstr($git_repo, $_SESSION['git_endpoint']['external']) ) {
      $_SESSION['git_type'] = 'external';
    }

    $git_repo = str_ireplace($_SESSION['git_endpoint']['external'], '', $git_repo = str_ireplace('.git', '', $git_repo));
    $git_repo = str_ireplace($_SESSION['git_endpoint']['internal'], '', $git_repo);
    
    if ($git_repo[strlen($git_repo)-1] === '/')
      $git_repo[strlen($git_repo)-1] = '';

    $git_repo = preg_replace( '/[^[:print:]]/', '',$git_repo);
    return $git_repo;
}

if (!empty($scan_name) && !empty($git_repo)) {

  $git_repo = normalize_git_path($git_repo);

  if (empty($_SESSION['git_repo'])) {

    $_SESSION['git_repo'] = $git_repo;
    $_SESSION['scan_name'] = $scan_name;
    $success = true;
    $message = '上传成功! 扫描已开始: ' . $git_repo;

  } else if ($_SESSION['scan_active'] === true && $_SESSION['git_repo'] === $git_repo) {
      $success = false;
      $message = '警告！扫描已经在进行中:  ' . $_SESSION['git_repo'];

  } else if (!empty($_SESSION['scan_active'] === true)) {
      $success = false;
      $message = '警告！扫描已经在进行中: ' . $_SESSION['git_repo'];
  }

}

if (!empty($scan_name) && !empty($upload_id) && !empty($zip_name)) {
  if (empty($_SESSION['upload_id'])) {

    $_SESSION['upload_id'] = $upload_id;
    $_SESSION['scan_name'] = $scan_name;
    $_SESSION['zip_name'] = $zip_name;
    $success = true;
    $message = '上传成功! 扫描已开始:  ' . $zip_name;

  } else if ($_SESSION['scan_active'] === true && $_SESSION['zip_name'] === $zip_name) {
      $success = false;
      $message = '警告！扫描已经在进行中: ' . $_SESSION['zip_name'];

  } else if (!empty($_SESSION['scan_active'] === true)) {
      $success = false;
      //$message = 'Warning! A scan is already in progress: ' . $_SESSION['zip_name'];
      $message = '警告！扫描已经在进行中: ' . $_SESSION['zip_name'];
  }
}

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

    <title>扫描--源代码分析平台</title>

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

<!--<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="scan.php">源代码扫描器</a>
  <a class="navbar-brand" href="#">
        <img alt="Brand" src="assets/img/QSAM1.png">
      </a>
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
            <li ><a href="issues.php">漏洞视图</a></li>
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
          <div class="col-sm-12 main">
          <h3 class="page-header">新建扫描</h3>
          <!--<h2 class="sub-header">/some/git/repo</h2>-->
          <?php
          $alert_type = 'danger';
          if (@$success) {
            $alert_type = 'success';
          }

          @$div_data = '<div class="alert alert-'. $alert_type .' alert-dismissible" role="alert">
                      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                      <strong>' . explode(':', $message)[0] . ':' . explode(':', $message)[1] . '</strong></div>
                      <script>window.setTimeout(function () {
                        location.href="status.php";
                      },2000);</script>'; 

          if ( !empty($scan_name) && ( !empty($git_repo) || !empty($zip_name) ) ) {
            echo $div_data;
          }
          ?>
<!--- container start -->
    <div class="container">
        <div id="content">
            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                <li class="active"><a href="#github" data-toggle="tab">代码链接</a></li>
                <li><a href="#upload" data-toggle="tab">上传代码</a></li>
            </ul>
            <div id="my-tab-content" class="tab-content">
            <div class="tab-pane active" id="github">
            <form method="POST" action="" role="form" style="margin-top: 2%"> 
              <label class="control-label">扫描名称</label>
              <input type="text" class="form-control" id="scan_name" name="scan_name" placeholder="扫描名称" /><br />
              <label class="control-label">链接地址</label>
              <input type="text" class="form-control" name="git_repo" placeholder="Git 仓库" /><br />
              <button type="submit" class="btn btn-default btn-primary">开始扫描</button>
          </form>
      </div>
                <div class="tab-pane" id="upload">
                    <p>
                        <!--Upload Form Start-->
                        <div class="container" style="margin-top: 2%">
                            <div class="panel panel-default">
                                <div class="panel-heading"><strong>请上传</strong> <small>一个zip压缩包</small></div>
                                <div class="panel-body">
                                    <!-- Standard Form -->
                                  


<form action="/raptor/upload" method="POST" enctype="multipart/form-data" id="js-upload-form" class="form-inline" role="form">
  <div class="form-group">
                                        
                                        <input type="hidden" name="usr" value="1">
                                        <label class="control-label">扫描名称</label>
                                        <input type="text" class="form-control" id="scan_name" name="scan_name" placeholder="扫描名称">
                                            <!--<h4>选择文件...</h4>-->
                                                                 <input type="file" name="file" id="js-upload-files" multiple="" class=" form-control"></div>

                                        
                                            <button type="submit" class="btn btn-sm btn-primary" id="js-upload-submit">上传扫描</button>
                                       

                                  </form> 
 
                                 <!-- <form action="/raptor/upload" method="POST" enctype="multipart/form-data" id="js-upload-form">
                                        <div class="form-inline">
                                        <input type="hidden" name="usr" value="?php echo $_SESSION['user_name']; ?>" />
                                        <label class="control-label">扫描名称</label>
                                        <input type="text" class="form-control" id="scan_name" name="scan_name" placeholder="扫描名称" /><br />
                                            <h4>选择文件...</h4>
                                            <div class="form-group">
                                                <input type="file" name="file" id="js-upload-files" multiple>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-primary" id="js-upload-submit">上传扫描</button>
                                        </div>
                                    </form>-->

                                </div>
                            </div>
                        </div>
                        <!-- /container -->
                        <!----Upload From End---->
                    </p>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#tabs').tab();
            });
        </script>
    </div>
    <!-- container -->
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  

<div id="global-zeroclipboard-html-bridge" class="global-zeroclipboard-container" style="position: absolute; left: 0px; top: -9999px; width: 15px; height: 15px; z-index: 999999999;">      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="global-zeroclipboard-flash-bridge" width="100%" height="100%">         <param name="movie" value="assets/flash/ZeroClipboard.swf?noCache=1417502908088">         <param name="allowScriptAccess" value="never">         <param name="scale" value="exactfit">         <param name="loop" value="false">         <param name="menu" value="false">         <param name="quality" value="best">         <param name="bgcolor" value="#ffffff">         <param name="wmode" value="transparent">         <param name="flashvars" value="">         <embed src="assets/flash/ZeroClipboard.swf?noCache=1417502908088" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="100%" height="100%" name="global-zeroclipboard-flash-bridge" allowscriptaccess="never" allowfullscreen="false" type="application/x-shockwave-flash" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="" scale="exactfit">                </object></div><svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200" preserveAspectRatio="none" style="visibility: hidden; position: absolute; top: -100%; left: -100%;"><defs></defs><text x="0" y="10" style="font-weight:bold;font-size:10pt;font-family:Arial, Helvetica, Open Sans, sans-serif;dominant-baseline:middle">200x200</text></svg>
<script src="dist/js/heartbeat.js"></script>
<script>

$(document).ready(function () {    
    $('scan_name').keyup(function() {
        var $th = $(this);
        $th.val($th.val().replace(/[^a-zA-Z0-9_]/g, function(str) { 
        BootstrapDialog.show({
            title: 'Error',
            message: 'Only alphabets, numbers & underscores are allowed.',
            size: 'size-small',
            buttons: [{
                label: 'Ok',
                action: function(dialog){
                    dialog.close();
                }
            }]
        }); 
          return ''; 
        }));
    });
});
</script>
<?php
$pdo=NULL;
?>
</body></html>
