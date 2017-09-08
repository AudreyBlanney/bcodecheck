<?php
include("session.php");

$data = '';
if (!empty($_SESSION['current_scan_report'])) {

if (file_exists($_SESSION['current_scan_report'])) {
  $data = json_decode(file_get_contents($_SESSION['current_scan_report']), true);
} else {
  $_SESSION['current_scan_report'] = '';
}} else {
error_log("[ERROR] session: current_scan_report is null.");
}

$chart_codetypes_metrics = array();//代码分类
$chart_severity_metrics = array();//程度分类
$chart_vulntype_metrics = array();//风险分类
$se_array = array();
$vu_array = array();
$fe_array = array();
if($data){
    foreach ($data['warnings'] as $key => $value) {
        if (array_key_exists('file', $value)) {
            array_push($fe_array, strtolower(trim(substr(strrchr($value['file'], '.'), 1))));
            $chart_codetypes_metrics = array_count_values($fe_array);
        }
    }
    for($i=0; $i < count($data['warnings']); $i++) {
        @$rule_id = !empty($data['warnings'][$i]['warning_code']) ? $data['warnings'][$i]['warning_code'] : '-';
        array_push($se_array,$data['warnings'][$i]['severity']);
        $chart_severity_metrics = array_count_values($se_array);

        array_push($vu_array,$data['warnings'][$i]['warning_type']);
        $chart_vulntype_metrics = array_count_values($vu_array);
    }
}

?>
<!DOCTYPE html>
<html lang="zh">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="assets/img/favicion-16.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="assets/img/favicion-16.ico" type="image/x-icon" />
  
  <title>结果--源代码分析平台</title>

  <link href="dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/dashboard.css" rel="stylesheet">
   <link href="dist/css/dataTable.css" rel="stylesheet">
  <link rel="stylesheet" href="dist/css/xcode.css">
  <link href="dist/css/bootstrap-dialog.min.css" rel="stylesheet" type="text/css">
  
  <script src="assets/js/ie-emulation-modes-warning.js"></script>
  <script src="dist/js/jquery-1.11.1.min.js"></script>
  <script src="dist/js/bootstrap.min.js"></script>
  <script src="dist/js/bootstrap-dialog.min.js"></script>
  <script src="assets/js/echarts.min.js"></script>
  <script src="assets/js/dark.js"></script>
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
          <a class="navbar-brand comp-logo" href="scan.php"><strong><img src="assets/img/jd_logo.png"  width="200" height="60"/></strong></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
          <ul class="nav navbar-nav nav-pad">
            <li><a href="scan.php">代码扫描</a></li>
            <li><a href="issues.php">代码分析</a></li>
            <li class="active"><a href="analytics.php">风险总表</a></li>
            <li><a href="history.php">任务管理</a></li>
            <li><a href="system_upgrade.php">系统升级</a></li>
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
      <div class="row">
        <div class="col-md-12">
          <h3 class="page-header">
            风险列表
          </h3>
          <div class="panel-group" id="accordion">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <table style="width: 100%">
                    <tbody>
                      <tr><td>
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" style="text-decoration: none">
                      详细列表
                    </a>
                        </td>
                        <td>
                          <button type="button" class="btn btn-success export" style="margin: 0px; float: right; text-decoration: none"> 导出 </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </h4>
              </div>
              <div id="collapseTwo" class="panel-collapse collapse">
                <h2 id="lblScanSource" class="sub-header">
                  <?php 
                    if (!empty($_SESSION['current_scan_report'])) {
                      echo $data['scan_info']['app_path'];
                    }
                  ?>
                </h2>
                <h4 id="lblIssueCount">
                  <?php
                    if (!empty($_SESSION['current_scan_report'])) {
                      echo '总警告数: ' . $data['scan_info']['security_warnings'];
                    }
                  ?>
                </h4>
    <div class="table-responsive">
      <table class="table table-condensed table-bordered" id="issues_table">
        <thead>
          <tr>
            <th>安全级别</th>
            <th>类型</th>
            <th>文件</th>
            <th>描述</th>
            <th>片段</th>
            <th>参考</th>
          </tr>
        </thead>
          <tfoot>
            <tr>
            <th>安全级别</th>
            <th>类型</th>
            <th>文件</th>
            <th>描述</th>
            <th>片段</th>
            <th>参考</th>
            </tr>
          </tfoot>
        <tbody>
  <?php
    $git_url = '';
    if (!empty($_SESSION['current_scan_report'])) {
      if ($_SESSION['git_type'] === 'internal' || $data['scan_info']['repo_type'] === 'internal') {
          $git_url = $_SESSION['git_endpoint']['internal'];
        } elseif ($_SESSION['git_type'] === 'external' || $data['scan_info']['repo_type'] === 'external') {
          $git_url = $_SESSION['git_endpoint']['external'];
        }

      for($i=0; $i < count($data['warnings']); $i++) {
        @$rule_id = !empty($data['warnings'][$i]['warning_code']) ? $data['warnings'][$i]['warning_code'] : '-';

      if ( strstr(ltrim($data['warnings'][$i]['file']), '.zip') ) {
          $line_content = '<td><a target="_blank" href="#">' . ltrim($data['warnings'][$i]['file'], '/') . '#L' . $data['warnings'][$i]['line'] . '</a></td>';
      } else {
         $line_content = '<td><a target="_blank" href="' . $git_url . $data['scan_info']['app_path'] . 
           '/blob/master/' . ltrim($data['warnings'][$i]['file'], '/') . '#L' . $data['warnings'][$i]['line'] . '">' . 
           ltrim($data['warnings'][$i]['file'], '/') . '#L' . $data['warnings'][$i]['line'] . '</a></td>';
      }
      /*
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
      */
      
      echo '<tr>'.
            '<td>' . $data['warnings'][$i]['severity'] . '</td>'.
           '<td>'.$data['warnings'][$i]['warning_type'].'</td>' . 
           $line_content .
           '<td>' . $data['warnings'][$i]['message'] . '</td>' .
           '<td><pre><code style="white-space:nowrap" class="'. pathinfo($data['warnings'][$i]['file'], PATHINFO_EXTENSION) .'">' . htmlentities($data['warnings'][$i]['code']) . '</code></pre></td>'.'<td><a target="_blank" href="' . $data['warnings'][$i]['link'] . '">'. $data['warnings'][$i]['link'] .'</a></td></tr>';
    }
      $_SESSION['scan_active'] = false;
      $_SESSION['git_repo'] = '';
      $_SESSION['zip_name'] = '';
      $_SESSION['upload_id'] = '';
  } else {
      for($i=0; $i < 6; $i++) {
        echo '<td></td>';
    }}
?>
              </tbody>
            </table>
            <a class="control pre" href="javascript:;">&lt;</a>
            <a class="control next" href="javascript:;">&gt;</a>
      </div>
          </div>
            </div>
              </div>
        </div>
      </div>
    </div>

    <!-- 代码种类分布 -->
    <div id = 'codes_type_w'><div id="codes_type" style="display: block"></div></div>
    <!-- 程度分布 -->
    <div id = 'grades_type_w'><div id="grades_type" style="display: block"></div></div>
    <!-- 风险分布 -->
    <div id = 'risks_type_w'><div id="risks_type" style="display: block"></div></div>
    <!-- Bootstrap core JavaScript
================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="dist/js/dataTable.js"></script>
    <script>
      $(document).ready(function () {
        $('#issues_table tfoot th').each( function () {
          var title = $('#issues_table thead th').eq($(this).index()).text();
          $(this).html('<input type="text"  class="txtSearchFilter" placeholder="Search ' + title + '" />');
        });

        var table = $('#issues_table').DataTable();
          //到处csv
/*        $(".export").on("click",function(e) {
          var report_export_dialog_config = {
            type: 'type-primary',
            title: '导出报告',
            message: '确认导出这份报告吗?',
            buttons: [
            {
              icon: '',
              label: '当前页面报告',
              cssClass: 'btn-primary',
              autospin: true,
              autodestroy: true,
              action: function(dialogRef) {
                exportTableToCSV.apply($(this),[$("#issues_table"), "source-scan-report-view.csv"]);
                dialogRef.close();
              }
            }, {
              icon: '',
              label: '全部报告',
              cssClass: 'btn-warning',
              autospin: true,
              autodestroy: true,
              action: function(dialogRef) {
                table.columns().eq(0).each(function(colIdx) {
                    table.column(colIdx).search('').draw();
                });
                var prev_len = table.page.len();
                table.page.len(10000).draw();
                exportTableToCSV.apply($(this),[$("#issues_table"), "source-scan-report-all.csv"]);
                table.page.len(prev_len).draw();
                dialogRef.close();
              }
            }, {
              label: '关闭',
              cssClass: 'btn-danger',
              action: function(dialogRef) {
                dialogRef.close();
              }
            }]
          };

          BootstrapDialog.show(report_export_dialog_config);
        });*/
          //设置容器高宽
          var worldcodes_type_w = $('#codes_type_w');
          function resizeWorldcodes_type_w() {
              worldcodes_type_w.width(window.innerWidth+'px');
              worldcodes_type_w.height(window.innerHeight-300+'px');
          };
          resizeWorldcodes_type_w();

          var worldgrades_type_w = $('#grades_type_w');
          function resizeWorldgrades_type_w() {
              worldgrades_type_w.width(window.innerWidth+'px');
              worldgrades_type_w.height(window.innerHeight-300+'px');
          };
          resizeWorldgrades_type_w();

          var worldrisks_type_w = $('#risks_type_w');
          function resizeWorldrisks_type_w() {
              worldrisks_type_w.width(window.innerWidth+'px');
              worldrisks_type_w.height(window.innerHeight-300+'px');
          };
          resizeWorldrisks_type_w();

          function resizeWorldMapContainer(worldMapContainer) {
              worldMapContainer.width('100%');
              worldMapContainer.height('80%');
          };
          //到处word
          $(".export").on("click",function(e) {
              codes_type();//代码种类
              grades_type();//程度分类
              risks_type();//风险种类分布
             location.href="word.php";

          });

            //代码种类
          function codes_type(){
              var worldMapContainer = $('#codes_type');
              resizeWorldMapContainer(worldMapContainer);
              var codeTypeChart = echarts.init(document.getElementById('codes_type'),'dark');
              codeTypeChart.setOption({
                  animation : false,
                  title: {
                      show:true,
                      text: '代码分类视图',
                      x:'center'
                  },
                  legend: {
                      orient: 'vertical',
                      right:'3%',
                      align: 'left',
                      y:'center',
                      data:[<?php
              $lang_metrics_name = "";
              $chart_codetype_name = '';
              foreach ($chart_codetypes_metrics as $key => $value) {
                $chart_codetype_name .= "'" . $key ."',";
              }
              echo $chart_codetype_name;
            ?>]
                  },
                  series: [{
                      name: '问题分布',
                      type: 'pie',
                      radius: ['30%', '70%'],
                      roseType : 'area',
                      avoidLabelOverlap: true,
                      itemStyle:{
                          normal:{
                              label:{
                                  show: true,
                                  formatter: '{b} : {c} ({d}%)'
                              },
                              labelLine :{show:true}
                          }
                      },
                      data: [<?php
                $codetypes_metrics = '';
                foreach ($chart_codetypes_metrics as $key => $value) {
                  $codetypes_metrics .= "{name:'" . $key ."',value:".$value."},";
                }
                echo $codetypes_metrics;
              ?>]
                  }]
              });
              var picInfo = codeTypeChart.getDataURL();
              if(picInfo) {
                  $.ajax({
                      type: "post",
                      data: {baseimg:picInfo},
                      url: 'download_image.php?action=codes_type',
                      async: false,
                      success: function (data) {

                      } ,
                      error: function (err) {
                          alert('图片保存失败');
                      }
                  });
              }
              $('#codes_type_w').hide();
          }

            //程度分类
          function grades_type(){
              var worldMapContainer = $('#grades_type');
              resizeWorldMapContainer(worldMapContainer);
              var gradeTypeChart = echarts.init(document.getElementById('grades_type'),'dark');
              gradeTypeChart.setOption({
                  animation : false,
                  title: {
                      show:true,
                      text: '程度分布视图',
                      x:'center'
                  },
                  legend: {
                      orient: 'vertical',
                      right:'3%',
                      align: 'left',
                      y:'center',
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
                      radius: ['30%', '70%'],
                      avoidLabelOverlap: true,
                      itemStyle:{
                          normal:{
                              label:{
                                  show: true,
                                  formatter: '{b} : {c} ({d}%)'
                              },
                              labelLine :{show:true}
                          }
                      },
                      data: [<?php
                $sev_metrics = "";
                foreach ($chart_severity_metrics as $key => $value) {
                  if($key == '高'){
                    $sev_metrics .= "{name:'" . $key ."',value:".$value.",itemStyle:{normal:{color:'#dd6b66'}}},";
                  }else if($key == '中'){
                    $sev_metrics .= "{name:'" . $key ."',value:".$value.",itemStyle:{normal:{color:'#fece56'}}},";
                  }else{
                    $sev_metrics .= "{name:'" . $key ."',value:".$value.",itemStyle:{normal:{color:'#93d665'}}},";
                  }
                }
                echo $sev_metrics;
              ?>]
                  }]
              });
              var picInfo = gradeTypeChart.getDataURL();
              if(picInfo) {
                  $.ajax({
                      type: "post",
                      data: {baseimg:picInfo},
                      url: 'download_image.php?action=grades_type',
                      async: false,
                      success: function (data) {

                      } ,
                      error: function (err) {
                          alert('图片保存失败');
                      }
                  });
              }
              $('#grades_type_w').hide();
          }

            //风险种类分布
          function risks_type(){
              var worldMapContainer = $('#risks_type');
              resizeWorldMapContainer(worldMapContainer);
              var risksTypeChart = echarts.init(document.getElementById('risks_type'),'dark');
              risksTypeChart.setOption({
                  animation : false,
                  title: {
                      show:true,
                      text: '风险分布视图',
                      x:'center'
                  },
                  tooltip: {
                      trigger: 'item',
                      formatter: "{a} <br/>{b}: {c} ({d}%)"
                  },
                  legend: {
                      right:'3%',
                      align: 'left',
                      orient: 'vertical',
                      x:'right',
                      y:'center',
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
                      radius: ['30%', '70%'],
                      center:['35%','50%'],
                      roseType : 'area',
                      avoidLabelOverlap: true,
                      itemStyle:{
                          normal:{
                              label:{
                                  show: true,
                                  formatter: '{b} : {c} ({d}%)'
                              },
                              labelLine :{show:true}
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
              var picInfo = risksTypeChart.getDataURL();
              if(picInfo) {
                  $.ajax({
                      type: "post",
                      data: {baseimg:picInfo},
                      url: 'download_image.php?action=risks_type',
                      async: false,
                      success: function (data) {

                      } ,
                      error: function (err) {
                          alert('图片保存失败');
                      }
                  });
              }
             $('#risks_type_w').hide();
          }
        
        // Apply the search
        table.columns().eq(0).each(function(colIdx) {
          $('input', table.column(colIdx).footer()).on('keyup change', function () {
            table.column(colIdx).search(this.value).draw();
          });
        });
      })
        
        function exportTableToCSV($table, filename) {
        var csv = [];
        var table_id = '#' + $table.attr('id') + ' tr';
        rows = $(table_id);
        var output = "";
        for(var i =0;i < rows.length;i++) {
          cells = $(rows[i]).find('td,th');
          csv_row = [];
          for (var j=0;j<cells.length;j++) {
            txt = cells[j].innerText;
            txt = '"' + txt.replace('n/a','') + '"';
            txt = txt.replace(/\n$/, "");
            csv_row.push(txt);
          }
            csv.push(csv_row.join(","));
            if (csv_row.join(",") !== '"","","","",""') {
              output += csv_row.join(",") + "\r\n";
            }
        }
        var csvContent = output;
        var pom = document.createElement('a');
        var blob = new Blob([csvContent], {
          type: 'text/csv;charset=utf-8;'
        });
        var url = URL.createObjectURL(blob);
        pom.href = url;
        pom.setAttribute('download', filename);
        pom.click();
      }
    </script>
    <script src="dist/js/bootstrap.min.js">
    </script>
    <script src="assets/js/docs.min.js">
    </script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js">
    </script>
  <!--  
   <script>
     var delete_timeout_id = setTimeout(function () {
       var master_search = document.getElementById('issues_table_filter');
       if (master_search.parentNode.removeChild(master_search)) {
         clearTimeout(delete_timeout_id)
       }
     }, 10);
   </script> -->
    <script src="dist/js/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <script src="dist/js/heartbeat.js"></script>
    <script>
    function deleteFilterView() {
      var filterFlag = false;
      
      $('.txtSearchFilter').each(function() {
        if($(this).val() != '') {
          filterFlag = true;
        }
      });

      if (!filterFlag) {
        var response = confirm('是否真的要删除所有的结果?');
        if(!response) {
          return 0;
        }
      }

      var issuesTable =$('#issues_table').dataTable();
      //get all nodes of the datatable it returns all rows
      var allNodes = issuesTable.fnGetNodes();
      //get filtered rows of datatable            
      var filteredRows = issuesTable._('tr', {"filter":"applied"});
      var filteredArray= [];
  
      for(var i=0; i<filteredRows.length; i++) {
        var item = filteredRows[i];
        //compare the github linenumber column 
        var column= item[2];
        filteredArray.push(column);
      }
  
      for(var i=0; i < allNodes.length; i++) {
        var rowData = allNodes[i];
        var columnData= $(rowData).find('td:eq(2)').html();
        //if the node is in filtered list remove it
        if(jQuery.inArray(columnData, filteredArray) > -1 ) {
          //here i am removing the row
          var rowData = issuesTable.fnDeleteRow(rowData, null, true);
        }
      }
      
      $('.txtSearchFilter').each(function() {
        $(this).val('');
      });
      
      var table = $('#issues_table').DataTable();
      table.search( '' ).columns().search( '' ).draw();

      var newIssuesCount = $('#issues_table_info').text().split('of')[1].trim().split(' ')[0];
      $("#lblIssueCount").text('警告总数: ' + newIssuesCount);

    }

    $(document).ready(function () {
      var btnFilterDeleteHTML = '<button type="button" id="btnFilterDelete" class="glyphicon glyphicon-trash btn btn-info btnFilterView" style="margin-left: 10px; float: left; text-decoration: none" onClick="deleteFilterView()"> </button>';
      $(btnFilterDeleteHTML).insertAfter("#issues_table_info");
    });
  </script>
  <script type="text/javascript">
     window.onscroll = function(){ 
         var t = document.documentElement.scrollTop || document.body.scrollTop; 
         if( t >= 280 ) { 
          $(".control").fadeIn(); 
         } else { 
          $(".control").fadeOut(); 
         } 
        } 

        $(".pre").on("click",function(){
          var $scro_div = $("#issues_table_wrapper");
          $scro_div.scrollLeft($scro_div.scrollLeft()-60);
        });
        $(".next").on("click",function(){
          var $scro_div = $("#issues_table_wrapper");
          $scro_div.scrollLeft($scro_div.scrollLeft()+60);
        });
 </script>
<?php
$pdo=NULL;
?>
  </body>
</html>
