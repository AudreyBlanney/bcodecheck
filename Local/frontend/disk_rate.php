<?php
/*
*定时获取磁盘使用情况，每天获取一次
*/

include 'mysql_config.php';
$del_res = true;

//获取硬盘大小
exec("sudo fdisk -l | grep Disk", $output);
$disk_1 = explode(',',$output[0]);
$disk_2 = explode(':',$disk_1[0]);
$disk_size = $disk_2[1];
//end

//获取磁盘已用空间
exec("sudo df -k | awk 'NR != 1{a+=$3}END{print a}'", $disk_y_space);
$disk_y_space_size = round($disk_y_space[0]/1024/1024,2).' GB';
//end

//获取磁盘可用空间
$disk_s_space = round($disk_size - $disk_y_space_size,2).' GB';
//end

//获取磁盘已用百分比
$disk_perc = round(rtrim($disk_y_space_size,'GB')/rtrim($disk_size,'GB')*100,2).'%';
//end

//获取磁盘警告阈值，写入系统日志
$mysql_notice = "select id,disk_sy_rate from {$tb_prefix}_notice";
$query_notice = $pdo->prepare($mysql_notice);
$query_notice->execute();
$re_notice= $query_notice->fetch(PDO::FETCH_ASSOC);
if(!empty($re_notice['id'])){
	//cpu警告阈值
	if(trim($disk_perc,'%') >= $re_notice['disk_sy_rate']){
		$mysql_disk = "insert into {$tb_prefix}_history_data(user_name,diction,be_record,be_res,jour_type,data_time) values(?,?,?,?,?,?)";
		$query_disk = $pdo->prepare($mysql_disk);
		$query_disk->execute(array('磁盘警告阈值',1,'代码审查系统磁盘使用率过高','请处理',2,date('Y-m-d H:i:s')));
		$res_disk = $pdo->lastinsertid();
		if(!$res_disk){
			$del_res = false;
		}
	}
}

//end

//获取第一条数据时间，清楚30天的数据
$mysql_se = "select id,date from {$tb_prefix}_disk limit 1";
$query_se = $pdo->prepare($mysql_se);
$query_se->execute();
$re_se = $query_se->fetch(PDO::FETCH_ASSOC);

if(!empty($re_se['date'])){
	$dq_time = date("Y-m-d H:i", strtotime("+1 months", strtotime($re_se['date'])));
	if(strtotime(date('Y-m-d H:i')) > strtotime($dq_time)){
		$del_sql = "truncate table {$tb_prefix}_disk";
		$del_query = $pdo->prepare($del_sql);
		$del_query->execute();
		$row = $del_query->rowCount();
		if($row == 0){
			$del_res = true;
		}else{
			$del_res = false;
		}
	}
}
//end
if($del_res = true){
	$mysql_inert = "insert into {$tb_prefix}_disk(disk_z,disk_y,disk_k,disk_y_rate,date) values(?,?,?,?,?)";
	$query_insert = $pdo->prepare($mysql_inert);
	$query_insert->execute(array($disk_size,$disk_y_space_size,$disk_s_space,$disk_perc,date('Y-m-d H:i')));
	$res_insert = $pdo->lastinsertid();
}
