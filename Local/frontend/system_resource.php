<?php
include "mysql_config.php";
//获取硬盘大小
exec("sudo fdisk -l | grep Disk", $output);
$disk_1 = explode(',',$output[0]);
$disk_2 = explode(':',$disk_1[0]);
$disk_size = $disk_2[1];
//end

//获取内存大小
exec("sudo cat /proc/meminfo |grep MemTotal", $memory);
$memory_1 = explode(':',$memory[0]);
$memory_size = $memory_1[1];
//end


//获取系统时间
exec('sudo date +%Y-%m-%d" "%T","%Z', $date);
$system_time = $date[0];
//end

//获取磁盘已用空间
exec("sudo df -k | awk 'NR != 1{a+=$3}END{print a}'", $disk_y_space);
$disk_y_space_size = round($disk_y_space[0]/1024/1024,2).' GB';
//end

//获取磁盘可用空间
$disk_s_space = round($disk_size - $disk_y_space_size,2).' GB';
//end

//获取系统阈值
$mysql_notice = "select id,system_name,cpu_sy_rate,memory_sy_rate,disk_sy_rate from {$tb_prefix}_notice";
$query_notice = $pdo->prepare($mysql_notice);
$query_notice->execute();
$re_notice= $query_notice->fetch(PDO::FETCH_ASSOC);
//end

//明天任务
/*1.后台执行获取内存使用率脚本 （5分钟） y
2.后台执行获取cpu利用率脚本 （5分钟）	 y
3.后台执行获取磁盘内存脚本  （每天）	 y
4.建数据库                               y
*/