<?php
session_start();
include 'mysql_config.php';
include "history_data.php";
//记录历史数据
$res_his = history_data($_GET['user_name'],'扫描软件代码项目','成功',1,date('Y-m-d H:i:s'));
//end
if($res_his){
    return true;
}else{
    return false;
}


