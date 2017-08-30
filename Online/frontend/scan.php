<?php
include 'session.php';
include 'mysql_config.php';
include 'header.php';
// include 'unload.php';
@$scan_name = $_REQUEST['scan_name'];
@$git_repo = $_REQUEST['git_repo'];
@$upload_id = $_REQUEST['upload_id'];
@$zip_name = $_REQUEST['zip_name'];
$path =  '/var/raptor/scan_results/'.$_SESSION['user_name'].'/'; //扫描结果文件路径
$GLOBALS['report_base']  = '/var/raptor/scan_results/';
$success = false;
//判断用户上传文件大小
function file_size(){
    global $pdo,$tb_prefix,$scan_name,$upload_id,$zip_name,$success;
    if (!empty($scan_name) && !empty($upload_id) && !empty($zip_name)) {
        $_SESSION['upload_id'] = $upload_id;
        $_SESSION['scan_name'] = $scan_name;
        $_SESSION['zip_name'] = $zip_name;
        if (!empty($_SESSION['upload_id'])) {
            $success = true;
        }
        $username = $_SESSION['user_name'];
        //判断用户是否上传过成功的数据
        $sql_hi = "select hi.id from {$tb_prefix}_user us left join {$tb_prefix}_upload_data up on us.id = up.user_id left join  {$tb_prefix}_upload_history hi on up.id = hi.data_id
                  where us.user_name = ? and upload_type = ? limit 1";
        $query_hi = $pdo->prepare($sql_hi);
        $query_hi->execute(array($username,1));
        $result_hi = $query_hi->fetch(PDO::FETCH_ASSOC);
        if ($result_hi) {
            $upload_type = "and hi.upload_type = 1";
        } else {
            $upload_type = '';
        }
        //获取数据信息

        $mysql_str = "select up.id,up.upload_size,up.upload_num,hi.upload_time,hi.upload_cs,hi.data_id,hi.upload_type from {$tb_prefix}_user us left join {$tb_prefix}_upload_data up
                  on us.id = up.user_id left join {$tb_prefix}_upload_history hi on up.id = hi.data_id where us.user_name = ? $upload_type order by hi.upload_cs desc";
        $query = $pdo->prepare($mysql_str);
        $query->execute(array($username));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $file_mb = 0;
        if(!empty($_GET['zip_size'])){
/*            if ($_GET['zip_size'] >= 1048576) {
                $file_size = round($_GET['zip_size'] / 1024 / 1024) . 'MB';
            } else {
                $file_size = round($_GET['zip_size'] / 1024) . 'KB';
            }*/

            $file_size = number_format($_GET['zip_size']/ 1024 / 1024, 3, '.', '');
            $_SESSION['zip_size'] = $file_size;
            $file_mb = round($_GET['zip_size'] / 1024 / 1024);
        }

        $current_time = date('Y-m-d');
        $upload_time = date('Y-m-d', time($result['upload_time']));
        //判断上传次数
        if ($upload_time == $current_time && $result['upload_cs'] > $result['upload_num'] && $result['upload_type'] == 1) {
            $success = false;
            exec("sudo rm -rf /var/raptor/uploads/$upload_id");
            exec("sudo rm -rf /var/raptor/uploads/$upload_id.zip");
            echo "<script>alert('已超出上传次数,请明天再传吧');location.href='scan.php';</script>";
        }
        //判断上传文件大小
        if ($file_mb > $result['upload_size']) {
            //记录上传错误文件信息
            $se_sql = "select id, upload_cs from {$tb_prefix}_upload_history where upload_type = ? and data_id = ? order by upload_cs desc";
            $se_query = $pdo->prepare($se_sql);
            $data_id = $result['data_id'] ? $result['data_id'] : 1;
            $se_query->execute(array(2, $data_id));
            $se_result = $se_query->fetch(PDO::FETCH_ASSOC);
            $upload_cs = $se_result['upload_cs'] ? $se_result['upload_cs'] + 1 : 1;

            $mysql_inert = "insert into {$tb_prefix}_upload_history(data_id,upload_fail,upload_cs,upload_type,upload_time) values(?,?,?,?,?)";
            $query_insert = $pdo->prepare($mysql_inert);
            $query_insert->execute(array($result['id'], $file_size, $upload_cs, 2, date('Y-m-d H:i:s')));
            $res_insert = $pdo->lastinsertid();
            if ($res_insert) {
                $success = false;
                exec("sudo rm -rf /var/raptor/uploads/$upload_id");
                exec("sudo rm -rf /var/raptor/uploads/$upload_id.zip");
                echo "<script>alert('上传文件请小于等于{$result['upload_size']}MB,您已超出范围');location.href='scan.php';</script>";
            }
        }
    }
}
//判断文件是否存在中文
function chinese_file($path){
    global $success,$upload_id;
    $dh = @opendir($path);
    while(($d = @readdir($dh)) != false){
        //跳过 '.','..'目录
        if ($d == '.' || $d == '..') continue;
        if(preg_match("/[\x7f-\xff]/", $d)){
            $success = false;
            exec("sudo rm -rf /var/raptor/uploads/$upload_id");
            exec("sudo rm -rf /var/raptor/uploads/$upload_id.zip");
            echo "<script>alert('上传文件{$d}文件为中文,处理后请重新上传');location.href='scan.php';</script>";
        }
        if(is_file($path.'/'.$d) && preg_match("/[\x7f-\xff]/", $path.'/'.$d)){
            $success = false;
            exec("sudo rm -rf /var/raptor/uploads/$upload_id");
            exec("sudo rm -rf /var/raptor/uploads/$upload_id.zip");
            $file = $path.'/'.$d;
            echo "<script>alert('上传文件中{$file}文件为中文,处理后请重新上传');location.href='scan.php';</script>";
        }

        if(is_dir($path.'/'.$d)){//如果为目录
            chinese_file($path.'/'.$d);//继续读取该目录下的目录或文件
        }
    }

}
/*if($upload_id && !is_dir("/var/raprot/uploads/$upload_id")){
     $locale = 'zh_CN.UTF-8';
     setlocale(LC_ALL, $locale);
     putenv('LC_ALL='.$locale);
     exec("sudo unzip /var/raptor/uploads/$upload_id.zip -d /var/raptor/uploads/$upload_id");
     exec("sudo chmod -R 777 /var/raptor/uploads/".$upload_id);

}*/

//==============================
$file_json = "''";
if($path && is_dir($path)){
    $dir = opendir($path);
    while($file_array[] = readdir($dir));
    $file_json = json_encode($file_array);
}

$dir_bas = @opendir($GLOBALS['report_base']);
while($file_array[] = @readdir($dir_bas));
foreach($file_array as $dir_key => $dir_value){
    if($dir_value == '.' || $dir_value == '..' || $dir_value == ''){
        unset($file_array[$dir_key]);
    }
}
$eng_num = 0;
$war_num = 0;
$scan_num = 0;
$lan_num = 0;
$de_num = 0;
if($file_array && !empty($file_array) && in_array($_SESSION['user_name'],$file_array)) {
//获取用户的所有操作数据
    function previous_scans($user_name){
        $root = $GLOBALS['report_base'];
		$scans = array();
		if(is_dir($root.$user_name)){
			foreach(scandir($root.$user_name) as $us_path){
				if($us_path != '.' && $us_path != '..' && is_dir($root.$user_name.'/'.$us_path)){
					foreach(scandir($root.$user_name.'/'.$us_path) as $path_value){
						if($path_value !== '.' && $path_value !== '..'){
							$scans[$us_path] = $root.$user_name.'/'.$us_path.'/'.$path_value;
						}
					}
				}
			}
		}

        $final_result = array(array());
        foreach ($scans as $scan_name => $path) {
            foreach (scandir($path) as $report) {
                if ($report[0] !== '.') {
                    $final_result[$scan_name]['report_path'] = $path . '/' . $report;
                    $git_path = str_replace($root, '', $path);
                    $git_path = str_replace($user_name, '', $git_path);
                    $git_path = substr($git_path, 1, strlen($git_path));
                    $temp = substr($git_path, 0, strpos($git_path, '/'));
                    $git_path = substr($git_path, strlen($temp) + 1, strlen($git_path));
                    $final_result[$scan_name]['git_path'] = $git_path;
                    $epoch = (int)explode('.json', $report)[0];
                    $dt = new DateTime("@$epoch");
                    $final_result[$scan_name]['scan_date'] = $dt->format('Y-m-d H:i:s');
                }
            }
        }
        unset($final_result[0]);
        $id = 1;
        $sx_array = array();
        foreach ($final_result as $key => &$value) {
            array_push($sx_array, $value['scan_date']); //获取时间
            $value['name'] = $key;
            $_SESSION['report_id'][$id] = $value['report_path'];
            $value['report_id'] = annlytics($value['report_path']);
            $_SESSION['delete_id'][$id] = $GLOBALS['report_base'] . $_SESSION['user_name'] . '/' . $key;
            $value['id'] = $id;
            $id += 1;
        }
        array_multisort($sx_array, SORT_DESC, $final_result); //按时间倒序排序
        return $final_result;
    }

    function annlytics($report_data)
    {
        if ($report_data) {
            $chart_codetypes_metrics = array();//代码分类
            $chart_vulntype_metrics = Array();//风险分类
            $fe_array = array();
            $se_array = array();
            $btn_codetype_name = array();
            $data = json_decode(file_get_contents($report_data), true);
            foreach ($data['warnings'] as $key => $value) {
                if (array_key_exists('file', $value)) {
                    array_push($fe_array, strtolower(trim(substr(strrchr($value['file'], '.'), 1))));
                    $chart_codetypes_metrics = array_count_values($fe_array);
                }
                array_push($se_array, $data['warnings'][$key]['warning_type']);
                $chart_vulntype_metrics = array_count_values($se_array);
            }
            foreach ($chart_codetypes_metrics as $lang_key => $langy_value) {
                $btn_codetype_name [] = $lang_key;
            }
        } else {
            $data = '';
        }
        $result_data = array(
            'security_warnings' => explode('(共计', $data['scan_info']['security_warnings']),
            'plugin' => $btn_codetype_name,
            'chart_severity_metrics' => $chart_vulntype_metrics
        );
        return $result_data;
    }

    $user_data = previous_scans($_SESSION['user_name']);
//获取用户的所有操作数据,数据整理
    $language_array = array();
    $defect_array = array();
    $war_num = '';
    $scan_num = '';
    foreach ($user_data as $user_key => $user_value) {
        //获取缺陷个数
        $war_num += $user_value['report_id']['security_warnings'][0];
        //获取扫描行数
        $scan = explode('行)', $user_value['report_id']['security_warnings'][1]);
        $scan_num += $scan[0];
        //获取语言种类
        foreach ($user_value['report_id']['plugin'] as $lan_key => $lan_value) {
            array_push($language_array, $lan_value);
        }
        //获取缺陷类型
        foreach ($user_value['report_id']['chart_severity_metrics'] as $de_key => $de_value) {
            array_push($defect_array, $de_key);
        }

    }
    $eng_num = count($user_data); //获取用户检测工程个数
    foreach ($language_array as $kg_key => $kg_value) {
        array_push($language_array, $kg_value);
        $lan_num_array = array_count_values($language_array);
    }
    $lan_num = !empty($lan_num_array) ? count($lan_num_array) : 0; //获取语言种类个数

    foreach ($defect_array as $de_key => $de_value) {
        array_push($defect_array, $de_value);
        $ke_num_array = array_count_values($defect_array);
    }
    $de_num = !empty($ke_num_array) ? count($ke_num_array) : 0; //获取缺陷类型种类个数
}
$enter_path = !empty($_SESSION['enter_path']) && $_SESSION['enter_path'] ? $_SESSION['enter_path'] : '';
?>
<link rel="stylesheet" href="./dist/css/codescan.css">
<div class="container">
    <div class="row ">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3>您创建了<span style="color: #65b6fc"><?php echo $eng_num ? $eng_num : 0;?></span>个检测工程</span></h3></div>
        <div class=" col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8 codemap">
             <p class="right-bottom"><span></span></p>
             <p class="left-bottom"><span></span></p>


             <div class="mt">
                <h4>检测工程统计</h4>
                <span style="width: 14px;height: 14px;margin-left: -7px;top:40px"></span>
                <span style="width: 10px;height: 10px;margin-left: -5px;top:60px"></span>
                <span style="width: 6px;height: 6px;margin-left: -3px;top:78px"></span>
                <span style="width: 4px;height: 4px;margin-left: -2px;top:94px"></span>
             </div>

            <div class="lt sxzy">
                <div class="sxzy-text" style="right: 30px;border-bottom: 1px solid #3f7178">
                    <label style="top:-4px;background-color: #1d4149;">总共缺陷个数</label>
                    <strong style="color: #1fd931;top:30px"><?php echo $war_num ? $war_num : 0;?>个</strong>
                </div>
                <span></span>

            </div>
            <div class="rt sxzy">
                <div class="sxzy-text" style="left:30px;border-bottom: 1px solid #156f9e">
                    <label style="top:-4px;background-color: #156189">总共代码检测行数</label>
                    <strong style="color: #c1d91f;top:30px"><?php echo $scan_num ? $scan_num : 0;?>行</strong>
                </div>
                <span></span>

            </div>
            <div class="lb sxzy">
                <div class="sxzy-text" style="right:30px;border-top:1px solid #156f9e">
                    <label style="bottom:-8px;background-color: #156189">监测语言种类</label>
                    <strong style="color: #c1d91f;top:-30px"><?php echo $lan_num ? $lan_num : 0;?>类</strong>
                </div>
                <span></span>

            </div>

            <div class="rb sxzy">
                <div class="sxzy-text" style="left:30px;border-top:1px solid #1d4149">
                    <label style="bottom:-8px;background-color: #1d4149;">发现缺陷种类</label>
                    <strong style="color: #1fd931;top:-30px"><?php echo $de_num ? $de_num : 0;?>种</strong>
                </div>
                <span></span>

            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h3>开始扫描---->请上传一个<span style="color: #14851f">zip</span>压缩包</span></h3>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12  uploading">
            <div class="file-container">


                <form action="/raptor/upload" method="POST" enctype="multipart/form-data" id="js-upload-form" class="form-inline" role="form"  onsubmit="return false" >
                    <input type="hidden" name="usr" value="1">
                    <span class="task-name">任务名称</span>
                    <input type="text" placeholder="任务名称" id="scan_name" name="scan_name" class="task-input" maxlength="20">


                    <div class="file-con">
                        <a class="file">选择文件
                            <input type="file" name="file" required="required" multiple="" class="form-control" id="f js-upload-files" accept="aplication/zip">
                        </a>
                        <p class="showfilemessage"></p>
                    </div>

                    <button type="submit" class="sub-button btn btn-sm btn-primary form-inline" id="js-upload-submit">上传扫描</button>
                </form>



            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bar-con">
            <!--<p>正在检测中....如果时间太长，可点击<a href="" style="color: #5ba8d0">这里</a>查看我的检测工程</p>-->
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" >

                </div>
            </div>
            <p class="percent" style="color: #8b8b8b;font-size: 1.2rem">0% </p>
        </div>
    </div>
</div>
<script src="./dist/js/bootstrap.min.js"></script>
<script>
//    上传文件input的样式
    var type = false;
    $(".file").on("change","input[type='file']",function(){
        var filePath=$(this).val();
        if(filePath.indexOf("zip")!=-1){
            var arr=filePath.split("\\")
            var fileName=arr[arr.length-1];
            $(".showfilemessage").html(fileName);
            type = true;
        }else{
            $('.form-inline').attr('onsubmit','return false');
            $(".showfilemessage").html("上传有误，请重新上传文件");
            $(".showfilemessage").css("color","red");
            type = false;
            return false
        }
    })
    //上传zip文件判断是否存在
    $('.btn-sm').click(function(){
		$.ajax({
			url: 'progrees.php',
			type: "get",
			async: false,
			dataType:"json",
			success: function (data){
				if(data == 2){
					type = false;
					alert('数据正在分析中,请稍后再传');
				}
			},
			error:function(){
				alert('获取失败');
			}
		});
        if(type){
            var file_json = <?php echo $file_json ;?>;
            if(file_json != ''){
                var file_array = new Array();
                for(var i=0;i < file_json.length;i++){
                    file_array.push(file_json[i]);
                }
                var scan_name = $("input[name = 'scan_name']").val();
                var scan_name = String(scan_name);
                var res = $.inArray(scan_name,file_array);
                if(res >= 0 ){
                    alert('任务名称已存在，请重新命名');
                }else{
                    jindu();
                    $('.form-inline').attr('onsubmit','return true');
                    $('.form-inline').submit();
                }
            }else{
                $('.form-inline').attr('onsubmit','return true');
                $('.form-inline').submit();
                jindu();
            }
        }
    });

    //进度条及百分比样式
    function jindu(){
        var per = 60+"%";
        $(".progress-bar").css("width",per);
        $(".percent").html(per);
    }
</script>
<?php
file_size();
/*if($upload_id){
    if($upload_id && !is_dir("/var/raprot/uploads/$upload_id")){
        $zip_file = '/var/raptor/uploads/'.$upload_id.'.zip';
        $zip=new ZipArchive();
        if ($zip->open($zip_file) === TRUE) {
            exec("sudo chmod -R 777 /var/raptor/uploads/".$upload_id);
            $zip->extractTo('/var/raptor/uploads/'.$upload_id);
        }else{
            echo ("zip包打开失败");
        }
    }
    chinese_file('/var/raptor/uploads/'.$upload_id);
};*/
if($success){ ?>
    <script>
        var per = 100+"%";
        $(".progress-bar").css("width",per);
        $(".percent").html(per);
        location.href='status.php';
    </script>
<?php }?>
</body>
</html>

