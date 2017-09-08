<?php
include 'session.php';
include 'header.php';
?>

<link rel="stylesheet" href="./dist/css/proman.css">
<div class="container">
    <div class="row rowpiece">
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" ><a href ="history.php" >工程列表</a></div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" ><a href = "task.php" style=" background-color:rgba(34,41,48,.6);box-shadow: 2px 2px 3px #000;">任务列表</a></div>
    </div>
	<?php if(empty($_SESSION['enter_path']) || !$_SESSION['enter_path']){?>
		<div class="notice">暂无扫描数据</div>
	<?php }?>
    <div class="row rowpiece pro_area"></div>
</div>
<?php if(!empty($_SESSION['enter_path']) && is_file($_SESSION['enter_path']) == true){?>

<script>
var autoplay = setInterval(function(){
	var request=null;
	if(window.XMLHttpRequest){
		request=new XMLHttpRequest();
	}else if(window.ActiveXObject){
		request=new ActiveXObject("Microsoft.XMLHTTP");
	}
	if(request){
		request.open("GET",'/speed_progress/<?php echo $_SESSION['user_name']?>.json',false);
		request.onreadystatechange=function(){
			if(request.readyState===4){
				if (request.status == 200 || request.status == 0 || request.status == 304){
					//获取数据
					var op_text = request.responseText;
					var jsonobj=eval('('+op_text+')'); 
					if(jsonobj.status == 3){
						var scan_satus = jsonobj.progress;
					}else if(jsonobj.status == 1){ 
						var scan_satus = '扫描成功';
					}else{
						var scan_satus = '扫描失败';
					}
					var proElement = "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 bar-con'>" +
								  "<span class='task_name' title = "+jsonobj.name+">"+jsonobj.name+"</span>" +
								  "<div class='progress'>" +
									  "<div class='progress-bar' role='progressbar' aria-valuemin='0' aria-valuemax='100' style='width:"+jsonobj.progress+"'></div>" +
								  "</div>" +
								  "<p class='percent'>"+scan_satus +"</p>" +
								  "</div>"
					$(".pro_area").html(proElement);
					if(jsonobj.status == 1 || jsonobj.status == 2){
						$.ajax({
							url: 'progrees.php',
							type: "get",
							async: false,
							dataType:"json",
							success: function (data){
								if(data == 1){
									clearInterval(autoplay);
								}
							},
							error:function(){
								alert('获取失败');
							}
						});
					}
				}
			}
		}
		request.send(null);
	}else{
		alert("error");
	}
},1000)
</script>
<?php }?>
</body>
</html>