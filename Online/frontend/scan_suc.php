<?php
session_start();
include 'mysql_config.php';
if(empty($_GET)) return false; //判断参数是否为空
$date_time = date('Y-m-d H:i:s',$_GET['creatime']); //获取扫描之前时间
$upload_time = date('Y-m-d H:i:s',$_GET['scan_sutime']);//获取扫描成功时间
$username = $_GET['user_name']; //获取用户名
$file_size = $_GET['file_size']; //获取文件大小
$report_data = $_GET['report_data']; //获取json文件路径
$chart_codetypes_metrics = array();//代码分类
$fe_array = array();
$se_array = array();
$btn_codetype_name = array();
$data = json_decode(file_get_contents($report_data), true);
$security = explode('(',$data['scan_info']['security_warnings']);
$code = explode('行)',$security[1]);
$code_line = explode('共计',$code[0]);

foreach ($data['warnings'] as $key => $value) {
    if (array_key_exists('file', $value)) {
        array_push($fe_array, strtolower(trim(substr(strrchr($value['file'], '.'), 1))));
        $chart_codetypes_metrics = array_count_values($fe_array);
    }
}
$lang_type = '';
foreach ($chart_codetypes_metrics as $lang_key => $langy_value) {
    $lang_type .= $lang_key.',';
}

$lang_type = trim($lang_type,','); //获取代码种类
$code_line_num = $code_line[1]; //获取代码行数
//判断用户是否上传过成功的数据
$sql_hi = "select hi.id from {$tb_prefix}_user us left join {$tb_prefix}_upload_data up on us.id = up.user_id left join  {$tb_prefix}_upload_history hi on up.id = hi.data_id
                  where us.user_name = ? and upload_type = ? limit 1";
$query_hi = $pdo->prepare($sql_hi);
$query_hi->execute(array($username,1));
$result_hi = $query_hi->fetch(PDO::FETCH_ASSOC);
if($result_hi){
    $upload_type = "and hi.upload_type = 1";
}else{
    $upload_type = '';
}
//获取数据信息
$mysql_str = "select up.id,up.upload_size,up.upload_num,hi.upload_time,hi.upload_cs,hi.upload_type from {$tb_prefix}_user us left join {$tb_prefix}_upload_data up
              on us.id = up.user_id left join {$tb_prefix}_upload_history hi on up.id = hi.data_id where us.user_name = ? $upload_type order by hi.upload_cs desc" ;
$query = $pdo->prepare($mysql_str);
$query->execute(array($username));
$result = $query->fetch(PDO::FETCH_ASSOC);

//记录上传成功文件信息
$upload_cs = $result['upload_cs'] && $result['upload_type'] == 1 ? $result['upload_cs'] + 1 : 1;
$mysql_inert = "insert into {$tb_prefix}_upload_history(data_id,upload_success,upload_cs,upload_type,scan_time,upload_time,lang_type,code_line_num) values(?,?,?,?,?,?,?,?)";
$query_insert = $pdo->prepare($mysql_inert);
$query_insert->execute(array($result['id'],$file_size,$upload_cs,1,$date_time,$upload_time,$lang_type,$code_line_num));
$res_insert = $pdo->lastinsertid();
if($res_insert){
    return true;
}else{
	return false;
}


