<?php
    include "history_data.php";
    include "mysql_config.php";
    session_start();

	//上传规则文件
	$names = $_FILES['files']['name'];
	$types = $_FILES['files']['type'];
	$tmpname = $_FILES['files']['tmp_name'];
	$errors = $_FILES['files']['error'];
	$sizes = $_FILES['files']['size'];

	$file_name = $_POST['filename'];

	//2判断上传文件错误
     //$file['error']为0表示上传成功
        if(!empty($errors)){
        	switch($errors){
        		case 1:
        			$flag = 3;
        		  //die('超过了 php.ini 中 upload_max_filesize 的限制');
                    break;
        		case 2:
        			$flag = 4;
        		  //die('超过了 HTML 表单 中隐藏域设置的 限制');
        		  break;
        		case 3:
        			$flag = 5;
        		  //die('文件只有部分被上传');
        		  break;
        		case 4:
        		    $flag = 6;
        		  //die('文件没有上传');
        		  break;
        		case 6:
        		    $flag = 7;
        		  //die('找不到临时目录');
        		  break;
        		case 7:
        		    $flag = 8;
        		  //die('文件写入失败');
        		  break;
			    default:
			        $flag = 9;//未知错误
                    break;
        	}
        }

        $dir = '/home/bcode/raptor-maste/backend/rules/';
        $dh = opendir($dir);

        while (($file = readdir($dh)) !== false){
        	$fname[] = $file;
    	}
        $ext=  pathinfo($names,PATHINFO_EXTENSION);
    if($ext == 'rulepack'){//判断后缀名
    	if(in_array($names,$fname)){//判断是否包含规则文件
    		if(is_uploaded_file($tmpname)){
    			$time = date('YmdHis',time());
                exec("sudo chmod  777 /home/bcode/raptor-maste/backend/rules/".$names);
                exec("sudo chmod  777 /home/bcode/raptor-maste/backend/rules/backup");

    			$cp = 'sudo cp /home/bcode/raptor-maste/backend/rules/'.$names.' /home/bcode/raptor-maste/backend/rules/backup/'.$time.'-'.$names;

    			exec($cp);//备份规则文件

	    		$status = move_uploaded_file($tmpname,$dir.$names);
	    	}else{
	    		$flag = 11;//不是通过POST机制过来的上传文件
	    	}
    	}else{
    		$flag = 10;//在规则文件中没有找到相应文件
    	}
    }else{//后缀名不是规则文件规定的
        $flag = 13;
    }
    	closedir($dh);

        $user_name = $_SESSION['user_name'];
        $time2 = date('Y-m-d H:i:s',time());

        //查询所有用户数据
    $mysql_str = "select diction from obsec_user where user_name='{$user_name}'";
    $query = $pdo->prepare($mysql_str);
    $query->execute();
    $res = $query->fetchall(PDO::FETCH_ASSOC);
    $diction = $res[0]['diction'];
    $be_record = "升级{$file_name}规则";

    if($status && !$flag){
        $be_res = '成功';
        $flag = 12;
    }else{
        $be_res = '失败';
    }
    history_data($user_name,$be_record,$be_res,$time2);

    header("location:sys-equipment.php?flag=".$flag);

?>