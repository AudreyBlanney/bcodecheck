<?php
    include("session.php");

    if (!empty($_SESSION['current_scan_report'])) {
        if (file_exists($_SESSION['current_scan_report'])) {
          $data = json_decode(file_get_contents($_SESSION['current_scan_report']), true);
        } else {
          $_SESSION['current_scan_report'] = '';
        }
    } else {
        error_log("[ERROR] session: current_scan_report is null.");
    }

    $ownerViewData = Array();//分览总数据{1.程度视图[高：1，中：2，低：3]，2.风险视图[Xss：1，sql注入：2，低：3]，3.$data}
    $codetype_param = $_GET['codetype'];//java、php
    $level = !empty($_GET['level']) ? $_GET['level'] :'';
    $g_array = array();
    $r_array = array();
    $gradeType = array();
    $riskType = array();
    for ($i=0; $i < count($data['warnings']); $i++) {
        if($level != null && $level != 'undefined' && strtolower(trim(substr(strrchr($data['warnings'][$i]['file'],'.'),1))) == $codetype_param && $level == $data['warnings'][$i]['severity']){
            array_push($r_array,$data['warnings'][$i]['warning_type']);
            $riskType = array_count_values($r_array);
            //$riskType[$data['warnings'][$i]['warning_type']]++;//风险视图
        }else if(strtolower(trim(substr(strrchr($data['warnings'][$i]['file'],'.'),1))) == $codetype_param && $level == null){
            array_push($g_array,$data['warnings'][$i]['severity']);
            $gradeType = array_count_values($g_array);
            array_push($r_array,$data['warnings'][$i]['warning_type']);
            $riskType = array_count_values($r_array);

            //$gradeType[$data['warnings'][$i]['severity']]++;//程度视图
           //$riskType[$data['warnings'][$i]['warning_type']]++;//风险视图
           //$ownerViewData[] = $data['warnings'][$i];//分览数据
        }
    }
    $result = [
        'owner_grade' => $gradeType,
        'owner_risk' => $riskType,
       // 'owner_data' => $ownerViewData
    ];

    echo json_encode($result);

    //请求参数过滤$data的json返回$code_data的json
    //根据传过来的参数来展示视图、列表

 ?>