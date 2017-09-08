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

    $ownerViewData = Array();
    $codetype_param = $_GET['codetype'];//语言类型java、php...
    $risk_type = $_GET['risk_type'];//warning类型
	foreach($data['warnings'] as $key => $value){
		if(trim($value['warning_type']) == $risk_type && trim(strtolower(trim(substr(strrchr($value['file'],'.'),1)))) == $codetype_param){
			$data['warnings'][$key]['code'] = htmlentities($data['warnings'][$key]['code']);
			$ownerViewData[] = $data['warnings'][$key];
		}
	}
	echo json_encode($ownerViewData);

 ?>