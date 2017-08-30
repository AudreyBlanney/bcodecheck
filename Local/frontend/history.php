<?php
include ("oneuser.php");
include("session.php");


$GLOBALS['report_base']  = '/var/raptor/scan_results/';  
$new_user = true;

function previous_scans($user_name) {

  $root = $GLOBALS['report_base'];
  
try {
  
  $iter = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($root.$user_name, RecursiveDirectoryIterator::SKIP_DOTS),
      RecursiveIteratorIterator::SELF_FIRST,
      RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
  );
  $new_user = false;

  $paths = array();
  foreach ($iter as $path => $dir) {
      if ($dir->isDir()) {
          $paths[] = $path;
      }
  }
  
  $projects = array();
  foreach (scandir($root.$user_name) as $project) {
    if ($project[0] !== '.') {
      $projects[] = $project;
    }
  }
  
  $scans = array();
  foreach ($projects as $project) {
    $scans[$project] = '';
    $interim_path = '';
    foreach($paths as $path) {
      if(strpos($path, $project) > 0 && strlen($path) > strlen($interim_path)) {
        $interim_path = $path;
      }
    $scans[$project] = $interim_path;
    }
  }
  
  $final_result = array(array());
  foreach($scans as $scan_name => $path) {
    foreach(scandir($path) as $report) {
      if ($report[0] !== '.') {
        $final_result[$scan_name]['report_path'] = $path . '/' . $report;
        $git_path = str_replace($root, '', $path);
        $git_path = str_replace($user_name, '', $git_path);
        $git_path = substr($git_path, 1, strlen($git_path));
        $temp = substr($git_path, 0, strpos($git_path, '/'));
        $git_path = substr($git_path, strlen($temp)+1, strlen($git_path));
        $final_result[$scan_name]['git_path'] = $git_path;
        $epoch = (int)explode('.json', $report)[0];
        $dt = new DateTime("@$epoch");
        $final_result[$scan_name]['scan_date'] = $dt->format('Y-m-d H:i:s');
      }
    }
  }
  
  unset($final_result[0]);
  
  return $final_result;
  
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'failed to open dir: No such file or directory') > 0) {
        $new_user = true;
    }
}}

?>
<!DOCTYPE html>
<html lang="zh"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>记录--源代码分析平台</title>

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

  <body>
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
            <li class="active"><a href="history.php">历史记录</a></li>
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
          <h3 class="page-header">历史扫描</h3>

          <!--<h2 class="sub-header">some/thing</h2>-->
                <?php
                  $reports = previous_scans($_SESSION['user_name']);
                  $id = 1;
                  //if ($new_user === false) {
                  if(isset($reports)){
                      echo '<div class="table-responsive">' .
                           '<table class="table table-striped">' .
                           '<thead>' .
                           '<tr>' .
                           '<th>#</th>' .
                           '<th>扫描名称</th>' .
                           '<th>知识库</th>' .
                           '<th>时间</th>' .
                           '<th>操作</th>' .
                           '</tr>' .
                           '</thead>' .
                           '<tbody>';

                      foreach($reports as $key => $value) {
                      $_SESSION['report_id'][$id] = $value['report_path'];
                      $_SESSION['delete_id'][$id] = $GLOBALS['report_base'] . $_SESSION['user_name'] . '/' . $key;
                      echo '<tr>' .
                           '<td>'. $id .'</td>' . 
                           '<td>'. $key .'</td>' . 
                           '<td>'. $value['git_path'] . '</td>' . 
                           '<td>'. $value['scan_date'] .'</td>' . 
                           '<td><a href="view_report.php?id='. $id . '">查看</a>' . '&emsp;' .
                           '<a href="delete_report.php?id=' . $id . '">删除</a></td>' .
                           '</tr>';
                        $id++;
                    }
                    echo '</tbody></table>';
                  } else {
                    echo '<div>没有可用的扫描报告.</div>';
                  }
                 /* } else {
                          echo '<tr>' .
                           '<td>'. $id .'</td>' . 
                           '<td>'. $key .'</td>' . 
                           '<td>'. $value['git_path'] . '</td>' . 
                           '<td>'. $value['scan_date'] .'</td>' . 
                           '<td><a href="view_report.php?id='. $id . '">View</a>' . '&emsp;' .
                           '<a href="delete_report.php?id=' . $id . '">Delete</a></td>' .
                           '</tr>';
                    } */
                ?>
          </div>
        </div>
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
  

<div id="global-zeroclipboard-html-bridge" class="global-zeroclipboard-container" style="position: absolute; left: 0px; top: -9999px; width: 15px; height: 15px; z-index: 999999999;">      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="global-zeroclipboard-flash-bridge" width="100%" height="100%">         <param name="movie" value="assets/flash/ZeroClipboard.swf?noCache=1417503694398">         <param name="allowScriptAccess" value="never">         <param name="scale" value="exactfit">         <param name="loop" value="false">         <param name="menu" value="false">         <param name="quality" value="best">         <param name="bgcolor" value="#ffffff">         <param name="wmode" value="transparent">         <param name="flashvars" value="">         <embed src="assets/flash/ZeroClipboard.swf?noCache=1417503694398" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="100%" height="100%" name="global-zeroclipboard-flash-bridge" allowscriptaccess="never" allowfullscreen="false" type="application/x-shockwave-flash" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="" scale="exactfit">                </object></div><svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200" preserveAspectRatio="none" style="visibility: hidden; position: absolute; top: -100%; left: -100%;"><defs></defs><text x="0" y="10" style="font-weight:bold;font-size:10pt;font-family:Arial, Helvetica, Open Sans, sans-serif;dominant-baseline:middle">200x200</text></svg>
<script src="dist/js/heartbeat.js"></script>
<?php
$pdo=NULL;
?>
</body></html>
