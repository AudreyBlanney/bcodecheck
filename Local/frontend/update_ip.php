<?php
	$ip = $_POST['ip'];//ip地址
	$gateway = $_POST['gateway'];//网关
	$netcard = $_POST['netcard'];//网卡
	$netmask = $_POST['netmask'];//子网掩码、
    
    $pattern = "/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/";//判断ip地址是否正确
    preg_match($pattern,$ip,$num);
    $ip_arr = explode('.',$ip);
    $ip_count = count($ip_arr);

    if($num && $ip_count == 4){
		foreach($ip_arr as $key => $value){
			if($value > 255 || $value < 0){
				$flag = 14;
				header("location:sys-tool.php?flag=".$flag);
				die();
			}
		}
	}else{
		$flag = 14;
		header("location:sys-tool.php?flag=".$flag);
		die();
	}

	if($gateway){
		preg_match($pattern,$gateway,$num);//判断网关是否正确格式
		$g_arr = explode('.',$gateway);
		$g_count = count($g_arr);
	    if($num && $g_count == 4){
			foreach($g_arr as $key => $value){
				if($value > 255 || $value < 0){
					$flag = 15;
					header("location:sys-tool.php?flag=".$flag);
					die();
				}
			}
		}else{
			$flag = 15;
			header("location:sys-tool.php?flag=".$flag);
			die();
		}
	}


	preg_match($pattern,$netmask,$num);//判断子网掩码格式是否正确
	$n_arr = explode('.',$netmask);
	$n_count = count($n_arr);
    if($num && $n_count == 4){
		foreach($n_arr as $key => $value){
			if($value > 255 || $value < 0){
				$flag = 16;
				header("location:sys-tool.php?flag=".$flag);
				die();
			}
		}
	}else{
		$flag = 16;
		header("location:sys-tool.php?flag=".$flag);
		die();
	}

	$flag = '';
	if(!$ip){	//判断所有选项是否为空
		$flags = 3;
		header("location:sys-tool.php?flags=".$flags);
		die();
	}
	if(!$netcard){
		$flags = 4;
		header("location:sys-tool.php?flags=".$flags);
		die();
	}
	if(!$netmask){
		$flags = 5;
		header("location:sys-tool.php?flags=".$flags);
		die();
	}
	//查看网关是否存在
	if($gateway){
		$gateway = 'gateway '.$gateway;
	}else{
		$gateway = '';
	}

	$str = file_get_contents("/etc/network/interfaces");
	$data = explode(PHP_EOL,$str);
	// echo '<pre>';
	// print_r($data);
	// die();

	foreach($data as $key => $value){
		if($value == 'auto '.$netcard){
			$flag = 1;
			$data[$key+2] = 'address '.$ip;
			$data[$key+3] = 'netmask '.$netmask;
			if($gateway){
				$data[$key+4] = $gateway;
			}
		}
	}


	if($flag != 1){
		$flag = 2;
		array_push($data,"auto ".$netcard,"iface ".$netcard." inet static","address ".$ip,"netmask ".$netmask,$gateway,PHP_EOL);
	}
	$str2 = implode(PHP_EOL,$data);

// 	print_r($data);
// echo '<hr/>';
// var_dump($str2);
// echo '<hr/>';
// die();

	exec("sudo chmod  777 /etc/network/interfaces");
$test = file_put_contents("/etc/network/interfaces",$str2);
	// exec("sudo chmod  644 /etc/network/interfaces");
	// $file = fopen('/var/www/html/raptor/interfaces','r+');
	// fwrite($file,'测试写入功能');
	// fclose($file);
	header("location:sys-tool.php?flag=".$flag);
?>