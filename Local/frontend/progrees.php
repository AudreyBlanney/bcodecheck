<?php
include 'session.php';
include 'mysql_config.php';
$data = '';
$data_status = 1;
if(!empty($_SESSION['enter_path'])){
	if(is_file($_SESSION['enter_path'])){
		$hd = fopen($_SESSION['enter_path'],'r');  
		$get_content = fgets($hd); 
		$data= json_decode($get_content,true);
		if($data['status'] == 1 || $data['status'] == 2){
            exec("sudo rm ".$_SESSION['enter_path']."");
            unset($_SESSION['enter_path']);
            $data_status = 1;
		}else{
			$data_status = 2;
		}
	}else{
		$data_status = 1;
	}

}else{
	$data_status = 1;
}
die(json_encode($data_status));
		
?>

