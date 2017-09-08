<?php
/*
*定时获取cpu/内存使用率，每1分钟获取一次
*/

include 'mysql_config.php';
$del_res = true;

//获取cup利用率
exec("sudo  mpstat | awk 'NR==4{print $6}'", $cpu);
$cpu_size = $cpu[0].'%';
//end

//获取内存使用率 
exec("sudo sar -r 1 1 | awk 'NR==4{print $9}'", $memory_lv);
$memory_size = $memory_lv[0].'%';
//end

//获取cpu/内存警告阈值，写入系统日志
$mysql_notice = "select id,cpu_sy_rate,memory_sy_rate from {$tb_prefix}_notice";
$query_notice = $pdo->prepare($mysql_notice);
$query_notice->execute();
$re_notice= $query_notice->fetch(PDO::FETCH_ASSOC);
if(!empty($re_notice['id'])){
	//cpu警告阈值
	if(trim($cpu_size,'%') >= $re_notice['cpu_sy_rate']){
		$mysql_cpu = "insert into {$tb_prefix}_history_data(user_name,diction,be_record,be_res,jour_type,data_time) values(?,?,?,?,?,?)";
		$query_cpu = $pdo->prepare($mysql_cpu);
		$query_cpu->execute(array('cpu警告阈值',1,'代码审查系统cpu使用率过高','请处理',2,date('Y-m-d H:i:s')));
		$res_cpu = $pdo->lastinsertid();
		if(!$res_cpu){
			$del_res = false;
		}
	}
	//内存警告阈值
	if(trim($memory_size,'%') >= $re_notice['memory_sy_rate']){
		$mysql_memory = "insert into {$tb_prefix}_history_data(user_name,diction,be_record,be_res,jour_type,data_time) values(?,?,?,?,?,?)";
		$query_memory = $pdo->prepare($mysql_memory);
		$query_memory->execute(array('内存警告阈值',1,'代码审查系统内存使用率过高','请处理',2,date('Y-m-d H:i:s')));
		$res_memory = $pdo->lastinsertid();
		if(!$res_memory){
			$del_res = false;
		}
	}
}

//end

//获取第一条数据时间，清楚30天的数据
$mysql_se = "select id,date from {$tb_prefix}_resource limit 1";
$query_se = $pdo->prepare($mysql_se);
$query_se->execute();
$re_se = $query_se->fetch(PDO::FETCH_ASSOC);

if(!empty($re_se['date'])){
	$dq_time = date("Y-m-d H:i", strtotime("+1 months", strtotime($re_se['date'])));
	if(strtotime(date('Y-m-d H:i')) > strtotime($dq_time)){
		$del_sql = "truncate table {$tb_prefix}_resource";
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
	$mysql_inert = "insert into {$tb_prefix}_resource(cpu_rate,memory_rate,date,date_nyr) values(?,?,?,?)";
	$query_insert = $pdo->prepare($mysql_inert);
	$query_insert->execute(array($cpu_size,$memory_size,date('Y-m-d H:i'),date('Y-m-d')));
	$res_insert = $pdo->lastinsertid();
}

