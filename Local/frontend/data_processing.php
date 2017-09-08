<?php
include('session.php');
/**
 * User: maaidong
 * Date: 2017/4/1
 * Time: 13:08
 */
//总数据处理
function data_total(){
    $data = '';
    if (!empty($_SESSION['current_scan_report'])) {
        if (file_exists($_SESSION['current_scan_report'])) {
            $data = json_decode(file_get_contents($_SESSION['current_scan_report']), true);
        } else {
            $_SESSION['current_scan_report'] = '';
        }
    } else {
        error_log("[ERROR] session: current_scan_report is null.");
    }
    return $data;
}

//代码种类数据处理
function data_type(){
    $data = data_total();
    $chart_codetypes_metrics = array();
    $data_chart = ''; //语言类型
    $data_total_number = '';//代码总条数
    $data_total_number_html = '';//html代码数据整理
    $warning_number = '';//为题个数
    if(!empty($data)){
        $fe_array = array();
        foreach ($data['warnings'] as $key => $value) {
            if (array_key_exists('file', $value)) {
                array_push($fe_array,strtolower(trim(substr(strrchr($value['file'],'.'),1))));
                $chart_codetypes_metrics = array_count_values($fe_array);
            }
        }
        $data_chart_array = array_keys($chart_codetypes_metrics);
        $data_chart = implode(',',$data_chart_array);
        $data_total_number = $data['scan_info']['security_warnings'];
        $total_c = explode('计',$data_total_number);
        $total_cz = explode('行',$total_c[1]);
        $data_total_number = $total_cz['0'];
        $warning_c = explode('(共',$total_c[0]);
        $warning_number = $warning_c[0];
        if($data_total_number > 10000){
            $data_total_number = $data_total_number/10000 .'w';
        }
        //html整理
        return '<table class="MsoNormalTable" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100.0%;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:
                 0cm 5.4pt 0cm 5.4pt">
                     <tbody>
                         <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:18.0pt">
                              <td width="100%" nowrap="" colspan="2" style="width:100.0%;border:solid windowtext 1.0pt;
                              mso-border-alt:solid windowtext .5pt;background:#4472C4;padding:0cm 5.4pt 0cm 5.4pt;
                              height:18.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">项目基本信息表<span lang="EN-US"><o:p></o:p></span></span></b></p>
                              </td>
                         </tr>
                         <tr style="mso-yfti-irow:1;height:18.0pt">
                              <td width="58%" nowrap="" style="width:58.44%;border:solid windowtext 1.0pt;
                              border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
                              solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;background:
                              #4472C4;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">扫描代码名称<span lang="EN-US"><o:p></o:p></span></span></b></p>
                              </td>
                              <td width="41%" nowrap="" style="width:41.56%;border-top:none;border-left:none;
                              border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                              mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
                              padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="font-size:11.0pt;font-family:等线;color:black">'."{$_SESSION['word_name']}".'<o:p></o:p></span></p>
                              </td>
                         </tr>
                         <tr style="mso-yfti-irow:2;height:18.0pt">
                              <td width="58%" nowrap="" style="width:58.44%;border:solid windowtext 1.0pt;
                              border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
                              solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;background:
                              #4472C4;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">问题代码文件类型<span lang="EN-US"><o:p></o:p></span></span></b></p>
                              </td>
                              <td width="41%" nowrap="" style="width:41.56%;border-top:none;border-left:none;
                              border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                              mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
                              padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="color:black">'.$data_chart.'<o:p></o:p></span></p>
                              </td>
                         </tr>
                         <tr style="mso-yfti-irow:3;height:18.0pt">
                              <td width="58%" nowrap="" style="width:58.44%;border:solid windowtext 1.0pt;
                              border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
                              solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;background:
                              #4472C4;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">代码总行数<span lang="EN-US"><o:p></o:p></span></span></b></p>
                              </td>
                              <td width="41%" nowrap="" style="width:41.56%;border-top:none;border-left:none;
                              border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                              mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
                              padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="color:black">'.$data_total_number.'<o:p></o:p></span></p>
                              </td>
                         </tr>
                         <tr style="mso-yfti-irow:4;mso-yfti-lastrow:yes;height:18.0pt">
                              <td width="58%" nowrap="" style="width:58.44%;border:solid windowtext 1.0pt;
                              border-top:none;mso-border-left-alt:solid windowtext .5pt;mso-border-bottom-alt:
                              solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;background:
                              #4472C4;padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><b><span style="font-family:&quot;微软雅黑&quot;,sans-serif;color:white">发现问题总数<span lang="EN-US"><o:p></o:p></span></span></b></p>
                              </td>
                              <td width="41%" nowrap="" style="width:41.56%;border-top:none;border-left:none;
                              border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                              mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
                              padding:0cm 5.4pt 0cm 5.4pt;height:18.0pt">
                              <p class="MsoNormal" align="center" style="text-align:center"><span lang="EN-US" style="color:black">'.$warning_number.'</span><span style="color:black">个<span lang="EN-US"><o:p></o:p></span></span></p>
                              </td>
                         </tr>
                    </tbody>
                </table>';
    }
}
$data_total_number_html = data_type();

//风险情况详述
function risk_description(){
    //获取总数据
    $data = data_total();
    //处理数据
    $risk_array = array(); //筛选问题数据类型重组
    $risk_des_array = array(); //帅选问题数据类型重复数据
    $risk_description = array(); //封装数据
    $file_array = array(); //文件类型筛选重组
    $file_des_array = array();//筛选文件类型重复数据
    if(!empty($data)){
        //筛选重复的数据
        foreach($data['warnings'] as $key => $value){
            array_push($risk_array,$value['warning_type']);
            $risk_des_array = array_count_values($risk_array);

            array_push($file_array,strtolower(trim(substr(strrchr($value['file'],'.'),1))));
            $file_des_array = array_count_values($file_array);
        }
        //筛选重复数据，合并数据
        foreach($data['warnings'] as $ky => $lue){
            foreach($file_des_array as $file_key => $file_value){
                foreach($risk_des_array as $ke => $ve){
                    if(in_array($ke,$lue) && $file_key == strtolower(trim(substr(strrchr($lue['file'],'.'),1)))){
                        $risk_description[$file_key][$ke][] = $data['warnings'][$ky];
                    }
                }
            }
        }
    }

    if(!empty($risk_description)) {
        $host = $_SERVER['HTTP_HOST'];
        $url = "http://".$host.'/';
        $table = '';$content = '';$table_end = '';$risk_content = '';$res_content = '';$file_title_num = '';$title_num = '';$file_link = '';
        foreach ($risk_description as $file_title_key => $file_title_value) {
            $file_title_num +=1;
            $file_title = '<h3><span lang="EN-US">2.2.'.$file_title_num.'  '.$file_title_key.'</span> 问题详情</h3>
                            <div align="center"><img width="553" height="243" src="'.$url.'temporary_file_mulu/branch_view_cd_'.$file_title_key.'.png"  width="553" height="245"></div>
                            <div align="center"><img width="553" height="243" src="'.$url.'temporary_file_mulu/branch_view_fx_'.$file_title_key.'.png"  width="553" height="245"></div>
                        ';
            $risk_content = '';
            $title_num = 1;
            foreach($file_title_value as $risk_key => $risk_value) {
                $title = '<div><p><h4><span style="line-height:150%;font-size:16pt" face="宋体"><b>2.2.'.$file_title_num.'.' . $title_num . '</b></span><span  style="line-height:150%;font-size:16pt" face="宋体"><b>' . $risk_key . ' &nbsp;</b></span></h4></p></div>';
                $table = '
                <div style="width: 584px" align="center">
                    <table width="584" border=1 cellspacing=0 cellpadding=0 width=534 style="word-wrap:break-word;word-break:break-all;border-collapse:collapse;border:none">
                        <tbody>
                        <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:58.5pt">
                            <td colspan="2" style="border-top:windowtext 1.5pt;border-left:windowtext 1.5pt;
                            border-bottom:windowtext 1.0pt;border-right:black 1.0pt;border-style:solid;
                            background:#4472C4;padding:0cm 5.4pt 0cm 5.4pt;height:58.5pt">
                                <p class="MsoNormal" align="center" style="text-align:center"><b><span style="color:white">风险名称<span lang="EN-US"><o:p></o:p></span></span></b></p>
                            </td>
                            <td style="border-top:solid #00000A 1.5pt;border-left:none;border-bottom:
                            solid #00000A 1.0pt;border-right:solid #00000A 1.0pt;background:#4472C4;
                            padding:0cm 5.4pt 0cm 5.4pt;height:58.5pt">
                                <p class="MsoNormal" align="center" style="text-align:center"><b><span style="color:white">' . $risk_key . '<span lang="EN-US"><o:p></o:p></span></span></b></p>
                            </td>
                            <td style="border-top:solid #00000A 1.5pt;border-left:none;border-bottom:
                            solid #00000A 1.0pt;border-right:solid #00000A 1.0pt;background:#4472C4;
                            padding:0cm 5.4pt 0cm 5.4pt;height:58.5pt">
                                <p class="MsoNormal" align="center" style="text-align:center"><b><span style="color:white">问题数量<span lang="EN-US"><o:p></o:p></span></span></b></p>
                            </td>
                            <td style="border-top:solid #00000A 1.5pt;border-left:none;border-bottom:
                            solid #00000A 1.0pt;border-right:solid #00000A 1.0pt;background:#4472C4;
                            padding:0cm 5.4pt 0cm 5.4pt;height:58.5pt">
                                <p class="MsoNormal" align="center" style="text-align:center"><b><span lang="EN-US" style="color:white">' . count($risk_value) . '<o:p></o:p></span></b></p>
                            </td>
                            <td style="border-top:solid #00000A 1.5pt;border-left:none;border-bottom:
                            solid #00000A 1.0pt;border-right:solid #00000A 1.0pt;background:#4472C4;
                            padding:0cm 5.4pt 0cm 5.4pt;height:58.5pt">
                                <p class="MsoNormal" align="center" style="text-align:center"><b><span style="color:white">风险等级<span lang="EN-US"><o:p></o:p></span></span></b></p>
                            </td>
                            <td style="border-top:solid #00000A 1.5pt;border-left:none;border-bottom:
                            solid #00000A 1.0pt;border-right:solid #00000A 1.5pt;background:#4472C4;
                            padding:0cm 5.4pt 0cm 5.4pt;height:58.5pt">
                                <p class="MsoNormal" align="center" style="text-align:center"><b><span style="color:white">' . $risk_value[0]['severity'] . '<span lang="EN-US"><o:p></o:p></span></span></b></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" width="110" height="24" style="border-top: 1px solid #00000a; border-bottom: 1px solid #00000a; border-left: 1.50pt solid #00000a; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.09in; padding-right: 0.08in">
                                <p align="justify"><span lang="zh-CN"><font size="3" style="font-size: 12pt">风险描述</font></span></p>
                            </td>
                            <td colspan="5" width="441" style="word-wrap:break-word;word-break:break-all;border-top: 1px solid #00000a; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1.50pt solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.09in; padding-right: 0.08in">
                                <p align="justify" style="margin-left: 0.03in; margin-right: 0.07in">
                                    <font size="2" style="font-size: 9pt" face="宋体">' . $risk_value['0']['message'] . '</font>
                                </p>
                            </td>
                        </tr>';
                $content = '';
                $number = '';
                foreach ($risk_value as $data_key => $data_value) {
                    $number = $data_key + 1;
                    $file_link = '<tr>
                            <td colspan="2" width="110" height="22" style="border-top: 1px solid #00000a; border-bottom: 1.50pt solid #00000a; border-left: 1.50pt solid #00000a; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.09in; padding-right: 0.08in">
                                <p align="justify"><span lang="zh-CN"><font size="3" style="font-size: 12pt">修复建议</font></span></p>
                            </td>
                            <td colspan="5" width="441" style="word-wrap:break-word;word-break:break-all;border-top: 1px solid #00000a; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1.50pt solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.09in; padding-right: 0.08in">
                                <p align="justify" style="margin-left: 0.03in; margin-right: 0.07in">
                                <font size="2" style="font-size: 9pt">
                                    <span style="font-weight: normal">
                                        <a target="_blank" href="' . $data_value['link'] . '">' . $data_value['link'] . '</a>
                                    </span>
                                </font>
                                </p>
                            </td>
                        </tr>';
                    //html代码整理
                    $content .= '
                        <tr>
                            <td rowspan="2" width="16" height="24" style="border-top: 1px solid #00000a; border-bottom: 1px solid #00000a; border-left: 1.50pt solid #00000a; border-right: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.09in; padding-right: 0.08in">
                                <p align="justify"><span lang="zh-CN"><font size="3" style="font-size: 12pt">第' . $number . '处</font></span></p>
                            </td>
                            <td width="80" style="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.09in; padding-right: 0.08in">
                                <p align="justify"><span lang="zh-CN"><font size="3" style="font-size: 12pt">代码位置</font></span></p>
                            </td>
                            <td colspan="5" width="441" style="word-wrap:break-word;word-break:break-all;border-top: 1px solid #00000a; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1.50pt solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.09in; padding-right: 0.08in">
                                <p align="justify" style="margin-left: 0.03in; margin-right: 0.07in">
                                <font size="2" style="font-size: 9pt"><span style="font-weight: normal">' . $data_value['file'] . '#L' . $data_value['line'] . '</span></font></p>
                            </td>
                        </tr>
                        <tr>
                            <td width="80" style="word-wrap:break-word;word-break:break-all;border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.09in; padding-right: 0.08in">
                                <p align="justify" style="margin-left: 0.03in; margin-right: 0.07in">
                                <font face="宋体"><font size="2" style="font-size: 9pt"><span lang="zh-CN"><font face="宋体"><font size="3" style="font-size: 12pt"><span style="font-weight: normal">源代码</span></font></font></span></font></font></p>
                            </td>

                            <td colspan="5" width="441" style="border-top: 1px solid #00000a; border-bottom: 1px solid #00000a; border-left: 1px solid #00000a; border-right: 1.50pt solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.09in; padding-right: 0.08in">
                                <p align="justify" style="margin-left: 0.03in; margin-right: 0.07in">
                                <font size="2" style="font-size: 9pt"><span style="font-weight: normal">
                                    ' . htmlentities($data_value['code']) . '</span>
                                </font>
                                </p>
                            </td>
                        </tr>';
                }
                $risk_content .=   $title . $table . $file_link .  $content . ' </tbody></table></div><br>';
                $title_num += 1;
            }
            $res_content .=  $file_title.$risk_content;
        }
        return $res_content;
    }
}
$risk_description = risk_description();

