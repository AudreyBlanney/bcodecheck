<?php
header("Content-type:text/html;charset=utf-8");
/*
*@ _dir是被压缩的文件夹名称
*@ _zipName是压缩后的压缩包名称
**/

/*class creatZip {
    private $_dir;
    private $_zipDir;
    private $_zipName;

    public function __construct($dir,$zipName) {
        $this->_dir = $dir;
        $this->_zipDir = basename($dir);
        $this->_zipName = $zipName;
        $this->mkdirs();
        $this->creat();
    }
    //检测并生成目录
    private function mkdirs() {
        if (!is_dir(dirname($this->_zipName))) {
            $arr = explode('/', dirname($this->_zipName));
            $arrs = '';
            foreach($arr as $value) {
                if (!is_dir($arrs.$value)) {
                    if (!!mkdir($arrs.$value)) {
                        $arrs .= $value.'/';
                    }
                }
            }
        }
    }
    //生成Zip压缩包
    private function creat() {
        $zip = new ZipArchive;
        if (is_dir($this->_dir)) {
            $this->readDir($this->_dir,$files);
            if ($zip->open($this->_zipName,ZipArchive::CREATE)) {
                foreach ($files as $value) {
                    preg_match('/\/('.$this->_zipDir.'\/.*)/', $value, $match);
                    if (is_dir($value)) {
                        @$zip->addEmptyDir($value, $match[1]);
                    } else {
                        @$zip->addFile($value, $match[1]);
                    }
                }
                $zip->close();
            }
        } else {
            if ($zip->open($this->_zipName,ZipArchive::CREATE)) {
                $zip->addFile($this->_dir, basename($this->_dir));
            }
        }
    }
    //读取文件夹所有文件
    private function readDir($dir,&$arr) {
        if ($dirs = opendir($dir)) {
            while (($file=readdir($dirs)) != false) {
                if ($file == '.' || $file == '..') continue;
                $files = $dir .'/'. $file;
                if (is_dir($files) && $this->isEmpty($files)) {
                    $this->readDir($files,$arr);
                } else {
                    $arr[] = $files;
                }
            }
        }
        closedir($dirs);
    }
    //判断文件夹是否为空
    private function isEmpty($dir) {
        if ($dirs = opendir($dir)) {
            while (($file=readdir($dirs)) != false) {
                if($file != '.' && $file != '..') {
                    closedir($dirs);
                    return true;
                    break;
                }
            }
            closedir($dirs);
            return false;
        }
    }
}
    $file = $_FILES['file']['name']; //获取文件名称
    $file_name = explode('.',$file);
    $zip_file_name = $file_name[0]; //设置解压到指定目录中
    $file_zip = $file_name[1]; //获取文件格式
    $path_tmp = './temporary_file/'; //设置文件储存路径
    $path = $path_tmp.$zip_file_name.'/'; //解压后文件位置
    $filter_array = array('php');

    //判断是否是zip文件
    if($file_zip != 'zip'){
        ?>
        <script>
            alert("文件格式不对，请重新上传zip文件");
 //           location.href='scan.php';
        </script>
        <?php
//        exit;
    }

    // 实例化对象
    $zip=new ZipArchive();

    //创建临时目录
    if(!is_dir($path_tmp)){
        mkdir($path_tmp);
    	@chmod($path_tmp,0777);
    }
    move_uploaded_file($_FILES['file']['tmp_name'],$path_tmp.$file); //移动文件位置

    //打开zip文档，如果打开失败返回提示信息
    if ($zip->open($path_tmp.$file) === TRUE) {
        $zip->extractTo($path_tmp.$zip_file_name);
        //对文件进行筛选处理
        showdir($path);
        //获取每个文件的名称
        $f_arr = array();
        $file_array = @opendir($path);//打开目录
        //逐个文件读取
        while(($d = @readdir($file_array)) != false){
            if ($d == '.' || $d == '..') continue;
            $f_arr[] = $d;
        }
    }else{
        echo ("zip包打开失败");
    }

    //将压缩文件解压到指定的目录下
    $zip->extractTo($path_tmp);
    //关闭zip文档
    $zip->close();

    function showdir($path){
        global $filter_array;
        $dh = @opendir($path);//打开目录
        //逐个文件读取
        while(($d = @readdir($dh)) != false){
            //跳过 '.','..'目录
            if ($d == '.' || $d == '..') continue;
            $pathinfo = pathinfo($path.'/'.$d);
            if(is_file($path.'/'.$d) && !in_array($pathinfo['extension'],$filter_array)){
                unlink($path.'/'.$d);
            }
            if(is_dir($path.'/'.$d)){//如果为目录
                showdir($path.'/'.$d);//继续读取该目录下的目录或文件
            }
        }
    }

    foreach($f_arr as $k => $v){
        new creatZip($path.$v,'/var/raptor/uploads/'.$file);
    }*/

   /* $url = "/raptor/upload";
    $post_data = array (
        "usr" => $_POST['usr'],
        "scan_name" => $_POST['scan_name'],
        "upload" => '/var/raptor/uploads/'.$file
    );*/


$ch = curl_init(); 
/*$data = array('usr' => $_POST['usr'] , 'scan_name' => $_POST['scan_name'] , 'lfile' =>  new CURLFile('/var/raptor/uploads/'.$file)); 
curl_setopt($ch,CURLOPT_URL,'/raptor/upload');  
curl_setopt($ch,CURLOPT_POST,1);  
curl_setopt($ch,CURLOPT_SAFE_UPLOAD,false);  
curl_setopt($ch,CURLOPT_POSTFIELDS,$data);  
$abc = curl_exec($ch);  
echo $abc;*/



//$url = 'http://10.1.1.140/raptor/upload';
$url = 'http://10.1.1.140/issues.php';
$data = array(
	'file' =>  "@/var/raptor/uploads/php2.zip", 
//'file'=>'@'.realpath($_FILES['file']['tmp_name']).";type=".$_FILES['file']['type'].";filename=".$_FILES['file']['name']	
	);
curl_setopt($ch, CURLOPT_USERPWD, 'joe:secret' );
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true );
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$return_data = curl_exec($ch);
curl_close($ch);
