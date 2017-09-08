<?php
/*
*$user_name 用户名
*$diction 用户角色
*$be_record 用户行为记录
*$be_res 用户行为结果
*$jour_type 日志类型 1:操作日志 ，2:系统日志 ，3:业务日志
*$data_time 用户行操作时间
*/
function history_data($user_name,$be_record,$be_res,$jour_type,$data_time){
	global $pdo,$tb_prefix;
	$mysql_his = "select id,diction from {$tb_prefix}_user where user_name = ?";
    $query_his = $pdo->prepare($mysql_his);
    $query_his->execute(array($user_name));
    $row_his = $query_his->fetch(PDO::FETCH_ASSOC);
	
	$mysql_inert = "insert into {$tb_prefix}_history_data(user_name,diction,be_record,be_res,jour_type,data_time) values(?,?,?,?,?,?)";
	$query_insert = $pdo->prepare($mysql_inert);
	$query_insert->execute(array($user_name,$row_his['diction'],$be_record,$be_res,$jour_type,$data_time));
	$res_insert = $pdo->lastinsertid();
	if($res_insert){
		return true;
	}else{
		return false;
	}
}
?>