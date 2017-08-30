<?php
include 'session.php';
include 'header.php';
$host = $_SERVER['HTTP_HOST'];
define('PATH', '/var/raptor/scan_results');
define('ENDPOINT', 'http://'.$host.':8000'); #defined in gunicorn_config.py
$_SESSION['enter_path'] = '';
$path = PATH . '/' . $_SESSION['user_name'] . '/' . $_SESSION['scan_name'] . '/' . $_SESSION['upload_id'] . '/' . time() . '.json';
$enter_path = '/var/www/html/raptor/speed_progress/'.$_SESSION['user_name'].'.json';
//$url = ENDPOINT . '/zip/scan/?upload_id=' . $_SESSION['upload_id'] . '&p=' . $path . '&zip_name=' . $_SESSION['zip_name'].'&enter='.$enter_path;
$url = ENDPOINT . '/zip/scan/';
$_SESSION['enter_path'] = $enter_path;
$hd = fopen($enter_path,'w');
$str = "{'status':'3','progress':'0%','name':'".$_SESSION['scan_name']."'}";
fwrite($hd, $str);
fclose($hd);
?>
<script>
var url = "<?php echo $url ?>";
var upload_id = "<?php echo $_SESSION['upload_id']?>";
var p = "<?php echo $path?>";
var zip_name = "<?php echo $_SESSION['zip_name']?>";
var enter = "<?php echo $enter_path?>";
var user_name = "<?php echo $_SESSION['user_name']?>";
var file_size = "<?php echo $_SESSION['zip_size']?>";
var report_data = "<?php echo $path?>";
$.ajax({
	url: url,
	type: "post",
	data:{upload_id:upload_id,p:p,zip_name:zip_name,enter:enter,user_name:user_name,file_size:file_size,report_data:report_data},
	dataType:"json",
	success: function (data){},
});
setTimeout(function(){
	location.href = 'task.php';
},100);

</script>
