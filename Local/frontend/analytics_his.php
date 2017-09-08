<?php
include("session.php");

$GLOBALS['report_base']  = '/var/raptor/scan_results/';
$dir_bas = opendir($GLOBALS['report_base']);
while($file_array[] = readdir($dir_bas));
foreach($file_array as $dir_key => $dir_value){
    if($dir_value == '.' || $dir_value == '..' || $dir_value == ''){
        unset($file_array[$dir_key]);
    }
}
if(!$file_array || empty($file_array) || !in_array($_SESSION['user_name'],$file_array)){
    exit;
}
$new_user = true;
function previous_scans($user_name) {
	$root = $GLOBALS['report_base'];
	$scans = array();
	if(is_dir($root.$user_name)){
		foreach(scandir($root.$user_name) as $us_path){
			if($us_path != '.' && $us_path != '..' && is_dir($root.$user_name.'/'.$us_path)){
				foreach(scandir($root.$user_name.'/'.$us_path) as $path_value){
					if($path_value !== '.' && $path_value !== '..'){
						$scans[$us_path] = $root.$user_name.'/'.$us_path.'/'.$path_value;
					}
				}
			}
		}
	}
	
    $final_result = array(array());
    foreach($scans as $scan_name => $path) {
        foreach(scandir($path) as $report) {
            if ($report !== '.' && $report !== '..') {
                $final_result[$scan_name]['report_path'] = $path . '/' . $report;
                $git_path = str_replace($root, '', $path);
                $git_path = str_replace($user_name, '', $git_path);
                $git_path = substr($git_path, 1, strlen($git_path));
                $temp = substr($git_path, 0, strpos($git_path, '/'));
                $git_path = substr($git_path, strlen($temp)+1, strlen($git_path));
                $final_result[$scan_name]['git_path'] = $git_path;
                $epoch = (int)explode('.json', $report)[0];
                $final_result[$scan_name]['scan_date'] = date('Y-m-d H:i:s',$epoch);
            }
        }
    }
    unset($final_result[0]);
    $id = 1;
    $sx_array = array();
    foreach($final_result as $key => &$value){
        array_push($sx_array,$value['scan_date']); //获取时间
        $value['name'] = $key;
        $_SESSION['report_id'][$id] = $value['report_path'];
        $value['report_id'] = annlytics($value['report_path']);
        $_SESSION['delete_id'][$id] = $GLOBALS['report_base'] . $_SESSION['user_name'] . '/' . $key;
        $value['id'] = $id;
        $id+=1;
    }
    array_multisort($sx_array, SORT_DESC, $final_result); //按时间倒序排序
    return $final_result;
}

function annlytics($report_data){
    if($report_data){
        $chart_codetypes_metrics = array();//代码分类
        $fe_array = array();
        $btn_codetype_name = '';
        $data = json_decode(file_get_contents($report_data), true);
        foreach ($data['warnings'] as $key => $value) {
            if (array_key_exists('file', $value)) {
                array_push($fe_array, strtolower(trim(substr(strrchr($value['file'], '.'), 1))));
                $chart_codetypes_metrics = array_count_values($fe_array);
            }
        }
        foreach ($chart_codetypes_metrics as $lang_key => $langy_value) {
            $btn_codetype_name .= $lang_key.',';
        }
        $btn_codetype_name = trim($btn_codetype_name,',');
    }else{
        $data = '';
    }
    $result_data = array('security_warnings' => $data['scan_info']['security_warnings'],'plugin' => $btn_codetype_name);
    return $result_data;
}

$final_result = previous_scans($_SESSION['user_name']);
//数据整理
if($final_result){
    $analy_data = array();
    foreach($final_result as $res_key => $res_value){
        $res_data['name'] = $res_value['name'];
        $_SESSION['word_name'] = $res_value['name'];
        $res_data['remark'] = !empty($res_value['report_id']['plugin']) ? $res_value['report_id']['plugin'] : '未发现问题';
        $res_data['date'] = $res_value['scan_date'];
        $res_data['test_type'] = '成功';
        $res_data['defect_num'] = $res_value['report_id']['security_warnings'];
        $res_data['found_name'] = $_SESSION['user_name'];
		if(!empty($res_value['report_id']['plugin'])){
			$res_data['dw_pre'] = "<a class='dw_pre' id='{$res_value['id']}' name='{$res_value['name']}'><span class='download' title='word下载'>下载</span></a>&nbsp;
                                <a href='view_report.php?id={$res_value['id']}'><span class='look' title='查看'>查看</span></a>&nbsp;
                                <a href='delete_report.php?id={$res_value['id']}'><span class='del' title='删除'>删除</span></a>
                                ";
		}else{
			$res_data['dw_pre'] = "<a><span class='download' title='word下载'>下载</span></a>&nbsp;
                                <a href='javascript:;'><span class='look' title='查看'>查看</span></a>&nbsp;
                                <a href='delete_report.php?id={$res_value['id']}'><span class='del' title='删除'>删除</span></a>
                                ";
		}
        
        array_push($analy_data,$res_data);
    }
}
/*echo '<pre>';
print_r($analy_data);exit;*/
die(json_encode($analy_data));