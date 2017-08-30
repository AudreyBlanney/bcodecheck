<?php
include ("oneuser.php");
include("session.php");

$_SESSION['upload_id']=NULL;
$_SESSION['scan_name']=NULL;
$_SESSION['zip_name']=NULL;
$_SESSION['scan_active']=false;

$file = fopen('num.html','r');
$con = fread($file,filesize('num.html'));
fclose($file);
#var_dump($con == '扫描进度:100');
if ($con == '扫描进度:100')
{
 $file = fopen('num.html','w');
 fwrite($file,'0');
 fclose($file);
}

if (!empty($_SESSION['current_scan_report'])) {

if (file_exists($_SESSION['current_scan_report'])) {
  $data = json_decode(file_get_contents($_SESSION['current_scan_report']), true);
} else {
  $_SESSION['current_scan_report'] = '';
}} else {
error_log("[ERROR] session: current_scan_report is null.");
}

$chart_plugin_metrics = Array();
$chart_severity_metrics = Array();
$chart_vulntype_metrics = Array();


?>
<!DOCTYPE html>
<html lang="zh">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="/favicon.ico">
  
  <title>统计图--源代码分析平台</title>

  <!-- Bootstrap core CSS -->
  <link href="dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Custom styles for this template -->
  <link href="assets/css/dashboard.css" rel="stylesheet">
  
  <!-- CSS for highlight.js syntax highlighting library -->
  <link rel="stylesheet" href="dist/css/xcode.css">
  <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
  <!--[if lt IE 9]>
<script src="../../assets/js/ie8-responsive-file-warning.js">
</script>
<![endif]-->
  <script src="assets/js/ie-emulation-modes-warning.js">
  </script>
  
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js">
</script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js">
</script>
<![endif]-->
  <script src="assets/js/echarts.min.js"></script>
    
    <script src="dist/js/jquery-1.11.1.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>

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
            <li class="active"><a href="issues.php">漏洞视图</a></li>
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
          <h3 class="page-header">
            详细报告
              </h3>
              <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="text-decoration: none">
                        漏洞统计
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="panel-body">
                      <div class="row placeholders">
                        <!--<div class="col-sm-12 placeholder">-->
                          <div id="chart_vulntype" style="width:100%;height: 400px;">
                            加载中...
                          </div>
                        <!--</div>-->
                        <div class="col-sm-6 placeholder">
                          <div id="chart_lang" style="width: 100%; height: 400px;">
                            加载中...
                          </div>
                        </div>
                        <div class="col-sm-6 placeholder">
                          <div id="chart_severity" style="width: 100%; height: 400px;">
                            加载中...
                          </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
   
  <?php
    if (!empty($_SESSION['current_scan_report'])) {
      
      if ($_SESSION['git_type'] === 'internal' || $data['scan_info']['repo_type'] === 'internal') {
          $git_url = $_SESSION['git_endpoint']['internal'];
        } elseif ($_SESSION['git_type'] === 'external' || $data['scan_info']['repo_type'] === 'external') {
          $git_url = $_SESSION['git_endpoint']['external'];
        }

      for($i=0; $i < count($data['warnings']); $i++) {
        @$rule_id = !empty($data['warnings'][$i]['warning_code']) ? $data['warnings'][$i]['warning_code'] : '-';

      if (array_key_exists($data['warnings'][$i]['plugin'], $chart_plugin_metrics)) {
        $chart_plugin_metrics[$data['warnings'][$i]['plugin']] += 1;
      } else {
          $chart_plugin_metrics[$data['warnings'][$i]['plugin']] = 0;
      }

      if (array_key_exists($data['warnings'][$i]['severity'], $chart_severity_metrics)) {
        $chart_severity_metrics[$data['warnings'][$i]['severity']] += 1;
      } else {
          $chart_severity_metrics[$data['warnings'][$i]['severity']] = 0;
      }

      if (array_key_exists($data['warnings'][$i]['warning_type'], $chart_vulntype_metrics)) {
        $chart_vulntype_metrics[$data['warnings'][$i]['warning_type']] += 1;
      } else {
          $chart_vulntype_metrics[$data['warnings'][$i]['warning_type']] = 0;
      }

      if (!function_exists('highlight_type')) {
        function highlight_type($plugin) {
        $type = '';
        switch ($plugin) {
          case 'android':
            $type = 'java';
          case 'php':
            $type = 'php';
          case 'actionscript':
            $type = 'actionscript';
          case 'fsb_android':
            $type = 'java';
          case 'fsb_injection':
            $type = 'java';
          case 'fsb_crypto':
            $type = 'java';
          case 'fsb_endpoint':
            $type = 'java';
        }
        return $type;
      }}
    }
  }
?>
            </div>
              </div>
        </div>
      </div>
    <script type="text/javascript">
    //Issue Type Metrics
        var myChart1 = echarts.init(document.getElementById('chart_vulntype'));
        myChart1.setOption({
            title: {
              show:true,
                text: '问题类型指标',
                x:'center'
            },
            tooltip: {
              trigger: 'item',
              formatter: "{a} <br/>{b}: {c} ({d}%)"
            },  
            toolbox: {
                feature: {
                    dataView: {},
                    saveAsImage : {
                        show : true,
                        title : '保存为图片',
                        type : 'png',
                        lang : ['点击保存']
                    }
                }
            },
            legend: {
              orient: 'vertical',
              x: 'left',
              data:[<?php
            $vuln_metrics_name = "";
            foreach ($chart_vulntype_metrics as $key => $value) {
              $vuln_metrics_name .= "'" . $key ."',";
            }
            echo $vuln_metrics_name;
          ?>]
            },
            series: [{
                name: '问题分布',
                type: 'pie',
                radius: ['50%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: true,
                        textStyle: {
                            fontSize: '12',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data: [<?php
            $vuln_metrics = "";
            foreach ($chart_vulntype_metrics as $key => $value) {
              $vuln_metrics .= "{name:'" . $key ."',value:".$value."},";
            }
            echo $vuln_metrics;
          ?>]
            }]
        });

        //Plugin Metrics
        var myChart2 = echarts.init(document.getElementById('chart_lang'));
        myChart2.setOption({
            title: {
              show:true,
                text: '插件测量',
                 x:'center'
            },
            tooltip: {
              trigger: 'item',
              formatter: "{a} <br/>{b}: {c} ({d}%)"
            },  
            toolbox: {
                feature: {
                    dataView: {},
                    saveAsImage : {
                        show : true,
                        title : '保存为图片',
                        type : 'png',
                        lang : ['点击保存']
                    }
                }
            },
            legend: {
              orient: 'vertical',
              x: 'left',
              data:[<?php
            $lang_metrics_name = "";
            foreach ($chart_plugin_metrics as $key => $value) {
              $lang_metrics_name .= "'" . $key ."',";
            }
            echo $lang_metrics_name;
          ?>]
            },
            series: [{
                name: '问题分布',
                type: 'pie',
                radius: ['50%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: true,
                        textStyle: {
                            fontSize: '12',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data: [<?php
            $lang_metrics = "";
            foreach ($chart_plugin_metrics as $key => $value) {
              $lang_metrics .= "{name:'" . $key ."',value:".$value."},";
            }
            echo $lang_metrics;
          ?>]
            }]
        });

        //Severity Metrics
        var myChart3 = echarts.init(document.getElementById('chart_severity'));
        myChart3.setOption({
            title: {
                show:true,
                text: '安全性程度',
                x:'center'
            },
            tooltip: {
              trigger: 'item',
              formatter: "{a} <br/>{b}: {c} ({d}%)"
            },  
            toolbox: {
                feature: {
                    dataView: {},
                    saveAsImage : {
                        show : true,
                        title : '保存为图片',
                        type : 'png',
                        lang : ['点击保存']
                    }
                }
            },
            legend: {
              orient: 'vertical',
              x: 'left',
              data:[<?php
            $sev_metrics_name = "";
            foreach ($chart_severity_metrics as $key => $value) {
              $sev_metrics_name .= "'" . $key ."',";
            }
            echo $sev_metrics_name;
          ?>]
            },
            series: [{
                name: '问题分布',
                type: 'pie',
                radius: ['50%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: true,
                        textStyle: {
                            fontSize: '12',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data: [<?php
            $sev_metrics = "";
            foreach ($chart_severity_metrics as $key => $value) {
              $sev_metrics .= "{name:'" . $key ."',value:".$value."},";
            }
            echo $sev_metrics;
          ?>]
            }]
        });

    </script>
    <script src="dist/js/bootstrap.min.js">
    </script>
    <script src="assets/js/docs.min.js">
    </script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js">
    </script>
    
    
    <div id="global-zeroclipboard-html-bridge" class="global-zeroclipboard-container" style="position: absolute; left: 0px; top: -9999px; width: 15px; height: 15px; z-index: 999999999;">
      
      <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="global-zeroclipboard-flash-bridge" width="100%" height="100%">
        
        <param name="movie" value="assets/flash/ZeroClipboard.swf?noCache=1417503608910">
        
        <param name="allowScriptAccess" value="never">
        
        <param name="scale" value="exactfit">
        
        <param name="loop" value="false">
        
        <param name="menu" value="false">
        
        <param name="quality" value="best">
        
        <param name="bgcolor" value="#ffffff">
        
        <param name="wmode" value="transparent">
        
        <param name="flashvars" value="">
        
        <embed src="assets/flash/ZeroClipboard.swf?noCache=1417503608910" loop="false" menu="false" quality="best" bgcolor="#ffffff" width="100%" height="100%" name="global-zeroclipboard-flash-bridge" allowscriptaccess="never" allowfullscreen="false" type="application/x-shockwave-flash" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="" scale="exactfit">
        
      </object>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200" preserveAspectRatio="none" style="visibility: hidden; position: absolute; top: -100%; left: -100%;">
      <defs>
      </defs>
      <text x="0" y="10" style="font-weight:bold;font-size:10pt;font-family:Arial, Helvetica, Open Sans, sans-serif;dominant-baseline:middle">
        200x200
      </text>
    </svg>
    <script src="dist/js/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <script src="dist/js/heartbeat.js"></script>
<?php
$pdo=NULL;
?>
  </body>
</html>
