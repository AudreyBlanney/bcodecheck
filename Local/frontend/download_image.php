<?php
include('session.php');
$action = !empty($_GET['action']) ? $_GET['action'] : '';
$path_tmp = 'temporary_file_mulu/'; //设置文件临时存储目录
$host = $_SERVER['HTTP_HOST'];
$url = "http://".$host.'/';
if(!is_dir($path_tmp)){
    mkdir($path_tmp);
    @chmod($path_tmp,0777);
}
$picInfo = $_POST['baseimg'];
if(!empty($_GET['file_class_type'])){
    $streamFileRand = 'branch_view_'.$_GET['file_class_type']; //图片名
    $picType ='.png';//图片后缀
    $streamFilename = $path_tmp.$streamFileRand .$picType; //图片保存地址
    preg_match('/(?<=base64,)[\S|\s]+/',$picInfo,$picInfoW);//处理base64文本
    file_put_contents($streamFilename,base64_decode($picInfoW[0]));//文件写入
}else{
    $streamFileRand = $action; //图片名
    $picType ='.png';//图片后缀
    $streamFilename = $path_tmp.$streamFileRand .$picType; //图片保存地址
    preg_match('/(?<=base64,)[\S|\s]+/',$picInfo,$picInfoW);//处理base64文本
    file_put_contents($streamFilename,base64_decode($picInfoW[0]));//文件写入
    if($action == 'codes_type'){
        $_SESSION['codes_type'] = $url.$path_tmp.$streamFileRand.$picType;
    }
    if($action == 'grades_type'){
        $_SESSION['grades_type'] = $url.$path_tmp.$streamFileRand.$picType;
    }
    if($action == 'risks_type'){
        $_SESSION['risks_type'] = $url.$path_tmp.$streamFileRand.$picType;
    }
    /*文件类型整理
    */
    //获取视图数据
    $chart_codetypes_metrics    = Array();//文件分类
    $chart_severity_metrics = Array();//程度分类
    $chart_vulntype_metrics = Array();//风险分类
    $data =  @json_decode(file_get_contents($_SESSION['current_scan_report']),true);
    if($data){
        $se_array = array();
        $vu_array = array();
        for($i=0; $i < count($data['warnings']); $i++) {
            @$rule_id = !empty($data['warnings'][$i]['warning_code']) ? $data['warnings'][$i]['warning_code'] : '-';
            array_push($se_array,$data['warnings'][$i]['severity']);
            $chart_severity_metrics = array_count_values($se_array);

            array_push($vu_array,$data['warnings'][$i]['warning_type']);
            $chart_vulntype_metrics = array_count_values($vu_array);
        }
    }

    /*风险代码视图种类*/
    if(!empty($data)){
        $fe_array = array();
        foreach ($data['warnings'] as $key => $value) {
            if (array_key_exists('file', $value)) {
                array_push($fe_array,strtolower(trim(substr(strrchr($value['file'],'.'),1))));
                $chart_codetypes_metrics = array_count_values($fe_array);
            }
        }
    }
    //end
    //文件分类视图
    $file_class_view_count = array_sum($chart_codetypes_metrics); //获取总数
    $file_class_view_tile = '（1）  文件分类视图<br>';
    $file_class_view_table = '<table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:
                             0cm 5.4pt 0cm 5.4pt">
                                 <tbody>
                                     <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:28.95pt">
                                          <td width="100%" colspan="3" style="width:100.0%;border:solid windowtext 1.0pt;
                                          background:#0070C0;padding:0cm 5.4pt 0cm 5.4pt;height:28.95pt">
                                            <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">一、文件分类视图（<span lang="EN-US">'.$file_class_view_count.'</span>）<span lang="EN-US"><o:p></o:p></span></span></b></p>
                                          </td>
                                     </tr>
                                     <tr style="mso-yfti-irow:1;height:27.3pt">
                                          <td width="35%" style="width:35.8%;border:solid windowtext 1.0pt;border-top:
                                          none;background:#0070C0;padding:0cm 5.4pt 0cm 5.4pt;height:27.3pt">
                                            <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">文件类型<span lang="EN-US"><o:p></o:p></span></span></b></p>
                                          </td>
                                          <td width="27%" style="width:27.38%;border-top:none;border-left:none;
                                          border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                                          background:#0070C0;padding:0cm 5.4pt 0cm 5.4pt;height:27.3pt">
                                            <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">数量（个）<span lang="EN-US"><o:p></o:p></span></span></b></p>
                                          </td>
                                          <td width="36%" style="width:36.82%;border-top:none;border-left:none;
                                          border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                                          background:#0070C0;padding:0cm 5.4pt 0cm 5.4pt;height:27.3pt">
                                            <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">占比（<span lang="EN-US">%</span>）<span lang="EN-US"><o:p></o:p></span></span></b></p>
                                          </td>
                                     </tr>';
    $file_class_str = '';
    foreach($chart_codetypes_metrics as $file_class_key => $file_calss_value){
        $file_class_str .= '<tr style="mso-yfti-irow:2;height:18.0pt">
                          <td width="35%" style="width:35.8%;border:solid windowtext 1.0pt;border-top:
                          none;background:white;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                            <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="font-family:&quot;微软雅黑&quot;,sans-serif;color:black">'.$file_class_key.'<o:p></o:p></span></p>
                          </td>
                          <td width="27%" style="width:27.38%;border-top:none;border-left:none;
                          border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                          padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                             <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="font-family:&quot;微软雅黑&quot;,sans-serif;color:black">'.$file_calss_value.'<o:p></o:p></span></p>
                          </td>
                          <td width="36%" style="width:36.82%;border-top:none;border-left:none;
                          border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                          padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                            <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="font-family:&quot;微软雅黑&quot;,sans-serif;color:black">'.round($file_calss_value/$file_class_view_count*100,2).'%'.'<o:p></o:p></span></p>
                          </td>
                     </tr>';

    }

    $file_class_view = $file_class_view_tile.$file_class_view_table.$file_class_str.'</tbody></table>';
    //end
    //程度分类视图
    $degree_class_view_count = array_sum($chart_severity_metrics);
    $degree_class_view_title = '（2）程度分类视图<br>';
    $degree_class_view_table = '<table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:
                             0cm 5.4pt 0cm 5.4pt">
                                 <tbody>
                                     <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:28.95pt">
                                          <td width="100%" colspan="3" style="width:100.0%;border:solid windowtext 1.0pt;
                                          background:#0070C0;padding:0cm 5.4pt 0cm 5.4pt;height:28.95pt">
                                            <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">二、程度分类视图（<span lang="EN-US">'.$degree_class_view_count.'</span>）<span lang="EN-US"><o:p></o:p></span></span></b></p>
                                          </td>
                                     </tr>
                                     <tr style="mso-yfti-irow:1;height:27.3pt">
                                          <td width="35%" style="width:35.8%;border:solid windowtext 1.0pt;border-top:
                                          none;background:#0070C0;padding:0cm 5.4pt 0cm 5.4pt;height:27.3pt">
                                            <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">文件类型<span lang="EN-US"><o:p></o:p></span></span></b></p>
                                          </td>
                                          <td width="27%" style="width:27.38%;border-top:none;border-left:none;
                                          border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                                          background:#0070C0;padding:0cm 5.4pt 0cm 5.4pt;height:27.3pt">
                                            <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">数量（个）<span lang="EN-US"><o:p></o:p></span></span></b></p>
                                          </td>
                                          <td width="36%" style="width:36.82%;border-top:none;border-left:none;
                                          border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                                          background:#0070C0;padding:0cm 5.4pt 0cm 5.4pt;height:27.3pt">
                                            <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">占比（<span lang="EN-US">%</span>）<span lang="EN-US"><o:p></o:p></span></span></b></p>
                                          </td>
                                     </tr>';
    $degree_class_str = '';
    foreach($chart_severity_metrics as $degree_class_key => $degree_class_value){
        $degree_class_str .= '<tr style="mso-yfti-irow:2;height:18.0pt">
                                  <td width="35%" style="width:35.8%;border:solid windowtext 1.0pt;border-top:
                                  none;background:white;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                                    <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="font-family:&quot;微软雅黑&quot;,sans-serif;color:black">'.$degree_class_key.'<o:p></o:p></span></p>
                                  </td>
                                  <td width="27%" style="width:27.38%;border-top:none;border-left:none;
                                  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                                  padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                                  <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="font-family:&quot;微软雅黑&quot;,sans-serif;color:black">'.$degree_class_value.'<o:p></o:p></span></p>
                                  </td>
                                  <td width="36%" style="width:36.82%;border-top:none;border-left:none;
                                  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                                  padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                                    <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="font-family:&quot;微软雅黑&quot;,sans-serif;color:black">'.round($degree_class_value/$degree_class_view_count*100,2).'%'.'<o:p></o:p></span></p>
                                  </td>
                             </tr>';
    }
    $degree_class_view = $degree_class_view_title.$degree_class_view_table.$degree_class_str.'</tbody></table>';
    //end
    //风险分类视图
    $risk_class_count = array_sum($chart_vulntype_metrics);
    $risk_class_view_title = '（3）风险分类视图<br>';
    $risk_class_view_table = '<table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:
                             0cm 5.4pt 0cm 5.4pt">
                                 <tbody>
                                     <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:28.95pt">
                                          <td width="100%" colspan="3" style="width:100.0%;border:solid windowtext 1.0pt;
                                          background:#0070C0;padding:0cm 5.4pt 0cm 5.4pt;height:28.95pt">
                                            <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">三、风险分类视图（<span lang="EN-US">'.$risk_class_count.'</span>）<span lang="EN-US"><o:p></o:p></span></span></b></p>
                                          </td>
                                     </tr>
                                     <tr style="mso-yfti-irow:1;height:27.3pt">
                                          <td width="35%" style="width:35.8%;border:solid windowtext 1.0pt;border-top:
                                          none;background:#0070C0;padding:0cm 5.4pt 0cm 5.4pt;height:27.3pt">
                                            <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">文件类型<span lang="EN-US"><o:p></o:p></span></span></b></p>
                                          </td>
                                          <td width="27%" style="width:27.38%;border-top:none;border-left:none;
                                          border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                                          background:#0070C0;padding:0cm 5.4pt 0cm 5.4pt;height:27.3pt">
                                            <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">数量（个）<span lang="EN-US"><o:p></o:p></span></span></b></p>
                                          </td>
                                          <td width="36%" style="width:36.82%;border-top:none;border-left:none;
                                          border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                                          background:#0070C0;padding:0cm 5.4pt 0cm 5.4pt;height:27.3pt">
                                            <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">占比（<span lang="EN-US">%</span>）<span lang="EN-US"><o:p></o:p></span></span></b></p>
                                          </td>
                                     </tr>';
    $risk_class_str = '';
    foreach($chart_vulntype_metrics as $risk_class_key  => $risk_class_value){
        $risk_class_str .= ' <tr style="mso-yfti-irow:2;height:18.0pt">
                                  <td width="35%" style="width:35.8%;border:solid windowtext 1.0pt;border-top:
                                  none;background:white;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                                    <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="font-family:&quot;微软雅黑&quot;,sans-serif;color:black">'.$risk_class_key.'<o:p></o:p></span></p>
                                  </td>
                                  <td width="27%" style="width:27.38%;border-top:none;border-left:none;
                                  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                                  padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                                    <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="font-family:&quot;微软雅黑&quot;,sans-serif;color:black">'.$risk_class_value.'<o:p></o:p></span></p>
                                  </td>
                                  <td width="36%" style="width:36.82%;border-top:none;border-left:none;
                                  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                                  padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                                    <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="font-family:&quot;微软雅黑&quot;,sans-serif;color:black">'.round($risk_class_value/$risk_class_count*100,2).'%'.'<o:p></o:p></span></p>
                                  </td>
                             </tr>';
    }

    $risk_class_view = $risk_class_view_title.$risk_class_view_table.$risk_class_str.'.</tbody></table>';
    //end
    //审计结果统计
     if(!empty($_SESSION['codes_type']) && !empty($_SESSION['grades_type']) && !empty($_SESSION['risks_type'])){
         $img_info = getimagesize($_SESSION['risks_type']);
     	$_SESSION['audit_results'] = ''.$file_class_view.'<div align="center"><img src='."{$_SESSION['codes_type']}".' width="553" height="245"></div>
                                '.$degree_class_view.'<div align="center"><img src='."{$_SESSION['grades_type']}".'  width="553" height="245"></div>
                                '.$risk_class_view.'<div align="center"><img src='."{$_SESSION['risks_type']}".'  width="553" height="245"></div>';

     }
}
?>
