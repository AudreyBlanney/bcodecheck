<?php
    include 'config/mysql_config.php';
    include 'config/session.php';

    //删除用户信息
    if($_POST['user_id']){
        $user_id = $_POST['user_id'];
    }else{
        header('Location: usecreat.php');
    }
    if(!empty($_POST) && $user_id){
        $de_sql = "delete us,up,hi from {$tb_prefix}_user us left join {$tb_prefix}_upload_data up on us.id = up.user_id left join {$tb_prefix}_upload_history hi on up.id = hi.data_id where us.id in($user_id)";
        $de_query = $pdo->prepare($de_sql);
        $de_res = $de_query->execute();
        if($de_res){
            if(is_dir("/var/raptor/scan_results/{$_POST['user_name']}")){
                exec("sudo chmod -R 777 /var/raptor/scan_results/{$_POST['user_name']}");
                exec("sudo rm -rf /var/raptor/scan_results/{$_POST['user_name']}",$res_data,$res);
                if($res === 0){
                    $prompt = array('success' => true);
                    die(json_encode($prompt));
                }else{
                    $prompt = array('success' => false);
                    die(json_encode($prompt));
                }
            }else{
                $prompt = array('success' => true);
                die(json_encode($prompt));
            }
        }else{
            $prompt = array('success' => false);
            die(json_encode($prompt));
        }
    }