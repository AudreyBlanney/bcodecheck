<?php
	$network = $_POST['name'];//网卡
	$status = "";
	if($_POST['describe']){
		$describe = $_POST['describe'];//网卡描述  写在配置文件的注释里
	}else{
		$describe = '';
	}

	$pattern = "/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/";//判断ip地址是否正确
	if(!empty($_POST['mask']) && $_POST['mask'] != 'false'){
		$mask = $_POST['mask'];//子网掩码

		preg_match($pattern,$mask,$num);//判断子网掩码格式是否正确
		$n_arr = explode('.',$mask);

		$n_count = count($n_arr);

	    if($num && $n_count == 4){
			foreach($n_arr as $key => $value){
				if($value > 255 || $value < 0){
					$flag = 1;
					die(json_encode($flag));
				}
			}
		}else{
			$flag = 1;
			die(json_encode($flag));
		}
	}else{
		$mask ='';
	}
	

	if(!empty($_POST['mask']) && $_POST['mask'] != 'false' && !empty($_POST['interfaceIP']) && $_POST['interfaceIP'] != 'false'){ //判断是否在同一网段.如果在同一网段，告警提示不可以
			$str = file_get_contents("/etc/network/interfaces");
			$data = explode(PHP_EOL,$str);

			foreach ($data as $key => $value) {
				if($value == "address ".$_POST['interfaceIP']){
					$ipstatus = 1;
				}
			}

			foreach ($data as $key => $value) {
				if($value == "netmask ".$_POST['mask']){

					$arrm = explode(".",$_POST['mask']);

						$str_ip2 = substr($data[$key-1],8);
						$arr_ip2 = explode('.',$str_ip2);

						$arr_ip3 = explode('.',$_POST['interfaceIP']);

					if($arrm[0] && $arrm[1] && $arrm[2]){

						if($arr_ip2[0] == $arr_ip3[0] && $arr_ip2[1] == $arr_ip3[1] && $arr_ip2[2] == $arr_ip3[2] && $ipstatus != 1){

							$flag = 6;
							die(json_encode($flag));
						}
					}else if($arrm[0] && $arrm[1]){

						if($arr_ip2[0] == $arr_ip3[0] && $arr_ip2[1] == $arr_ip3[1] && $ipstatus != 1){

							$flag = 6;
							die(json_encode($flag));
						}
					}else if($arrm[0]){

						if($arr_ip2[0] == $arr_ip3[0] && $ipstatus != 1){

							$flag = 6;
							die(json_encode($flag));
						}
					}
					
				}
			}
	}

	if(!empty($_POST['interfaceIP']) && $_POST['interfaceIP'] != 'false'){
		$interfaceIP = $_POST['interfaceIP'];//ip地址

		
	    preg_match($pattern,$_POST['interfaceIP'],$num);
	    $ip_arr = explode('.',$_POST['interfaceIP']);
	    $ip_count = count($ip_arr);

	    if($num && $ip_count == 4){
	        foreach($ip_arr as $key => $value){
	            if($value > 255 || $value < 0){
	                $flag = 2;
	                die(json_encode($flag));
	            }
	        }
	    }else{
	        $flag = 2;
	        die(json_encode($flag));
	        $interfaceIP = '';
	    }
	}else{
		$interfaceIP = '';
	}

	if(!empty($_POST['gateway']) && $_POST['gateway'] != 'false'){
		$gateway = "gateway ".$_POST['gateway'];//网关
		preg_match($pattern,$gateway,$num);//判断网关是否正确格式
		$g_arr = explode('.',$gateway);
		$g_count = count($g_arr);
	    if($num && $g_count == 4){
			foreach($g_arr as $key => $value){
				if($value > 255 || $value < 0){
					$flag = 3;
					die(json_encode($flag));
				}else{
					$gateway = "gateway ".$_POST['gateway'];//网关
				}
			}
		}else{
			$flag = 3;
			die(json_encode($flag));
		}
	}else{
		$gateway = '';
	}

	if($_POST['port']){
		$port = $_POST['port'];//端口   默认端口就行 

		$str_port = file_get_contents("/etc/nginx/sites-available/raptor");
		$data_port = explode(PHP_EOL,$str_port);

		if(substr($data_port[1],8) != $port.";"){
			$data_port[1] = "	listen ".$port.";";
			$str_port = implode(PHP_EOL,$data_port);

			exec("sudo chmod  777 /etc/nginx/sites-available/raptor");
			file_put_contents("/etc/nginx/sites-available/raptor",$str_port);
		//exec("sudo /etc/init.d/apache2 restart");
			exec("sudo /etc/init.d/nginx restart");
		}	
	}

	if(!empty($_POST['add']) && $_POST['add'] != 'false'){
		$add = $_POST['add'];//MAC地址sudo /sbin/ifconfig eth1 hw ether 00:0c:29:b8:12:5d

		$pattern2 = '/\w{2}:\w{2}:\w{2}:\w{2}:\w{2}:\w{2}/';//判断mac地址是否正确
		$dd = preg_match($pattern2,$add);

		if($dd){
			exec("sudo /sbin/ifconfig ".$network." hw ether ".$add);
		}else{
			$flag = 5;
			die(json_encode($flag));
		}
			
	}else{
		$add = '';
	}

	if(!empty($_POST['mtu'])){
		$mtu = $_POST['mtu'];//MTU

		if($mtu > 9000 || $mtu < 0){
			$flag = 4;
			die(json_encode($flag));//mtu值超出范围
		}else{
			exec("sudo ifconfig ".$network." mtu ".$mtu." up");
		}
	}else{
		$mtu = '';
	}


		$str = file_get_contents("/etc/network/interfaces");
		$data = explode(PHP_EOL,$str);


		foreach($data as $key => $value){
			if($value == 'auto '.$network){
				$status = 1;//网卡已经配置，做修改
				$data[$key-1] = '#'.$describe;
				$data[$key+2] = "address ".$interfaceIP;
				$data[$key+3] = "netmask ".$mask;
				$data[$key+4] = $gateway;
			}
		}
		if($status != 1){
			$status = 2;
		}

		if($status == 2){
			array_push($data,PHP_EOL.'#'.$describe,'auto '.$network,"iface ".$network." inet static","address ".$interfaceIP,"netmask ".$mask,$gateway);
			$str2 = implode(PHP_EOL,$data);
		}else if($status == 1){
			$str2 = implode(PHP_EOL,$data);
		}

		if(!empty($_POST['interfaceIP']) && $_POST['interfaceIP'] != 'false'){
			exec("sudo ifconfig ".$network." ".$interfaceIP." netmask 255.255.255.0");
		}

		exec("sudo chmod  777 /etc/network/interfaces");
		$test = file_put_contents("/etc/network/interfaces",$str2);

		exec("sudo /etc/init.d/networking restart");
		//exec("sudo /etc/init.d/nginx restart");	