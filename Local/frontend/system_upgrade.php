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
    <link rel="icon" href="assets/img/favicion-16.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="assets/img/favicion-16.ico" type="image/x-icon" />

    <title>规则--源代码分析平台</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
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
          <a class="navbar-brand comp-logo" href="scan.php"><strong><img src="assets/img/jd_logo.png"  width="200" height="60"/></strong></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
          <ul class="nav navbar-nav nav-pad">
            <li><a href="scan.php">代码扫描</a></li>
            <li><a href="issues.php">代码分析</a></li>
            <li><a href="analytics.php">风险总表</a></li>
            <li><a href="history.php">任务管理</a></li>
            <li class="active"><a href="system_upgrade.php">系统升级</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right user-info">
            <li><span class="user-info-core">您好，<?php echo $_SESSION['user_name'];?></span></li>
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>注销</a></li>
            <li><a href="adduser.php" class="glyphicon glyphicon-cog" title="添加用户"></a></ li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="separator-line"></div>
    <div class="container">
      <h3 class="page-header">上传升级</h3>
      <div class="file-select-upload">
        <form action="" method="POST" enctype="multipart/form-data" id="upgrade_form" class="form-inline" role="form">
        <div class="form-group">
          <input type="file" name="file" id="upgrade_upload_file" multiple="" class=" form-control">
        </div>
        <button type="submit" class="btn btn-sm btn-primary" id="upgrade_upload_submit">上传升级</button>
       </form>
      </div>

      <h3 class="page-header">历史纪录</h3>

      <div class="table-upgradelist">
        <table class="table table-striped table-hover">
          <thead>     
              <tr class="upgrade-title">
                <th>升级名称</th>
                <th>升级时间</th>
                <th>备注</th>
                <th>状态</th>
                <th>创建者</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>c#2017</td>
                <td>2017-02-22 18:10:21</td>
                <td>C++规则库更新</td>
                <td class="up-success">成功</td>
                <td>admin</td>
                <td><a class="glyphicon glyphicon-trash" disabled></a></td>
              </tr>
            </tbody>
        </table>
      </div>
    </div>
    <script src="dist/js/jquery-1.11.1.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <script src="assets/js/docs.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="dist/js/heartbeat.js"></script>
    <?php
    $pdo=NULL;
    ?>
  </body>
</html>
