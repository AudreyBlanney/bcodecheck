<?php
include "session.php";
include "header.php";
include "mysql_config.php";

//查询所有用户数据
    $mysql_str = "select id,user_name,diction,register_time from {$tb_prefix}_user order by register_time desc";
    $query = $pdo->prepare($mysql_str);
    $query->execute();
    $res = $query->fetchall(PDO::FETCH_ASSOC);
    $num = 1;
    foreach($res as $key => &$value){
    	$value['num'] = $num;	
	    $value['diction'] = $value['diction'] == 1 ? '系统管理员' : '代码审计';
	    $value['caozuo'] = "<span class='del' title='删除' id={$value['id']}></span>";
	    $num++;
    }
    $json_res = json_encode($res);
?>
<?php
	if(!empty($_GET['flags'])){
		$flags = $_GET['flags'];
	}else{
		$flags = '';
	}

?>
    <link rel="stylesheet" href="./dist/css/contact.css">
    <link rel="stylesheet" href="./dist/css/equipment.css">
    <link rel="stylesheet" href="./dist/css/table.css">
    <link rel="stylesheet" href="./dist/css/pop-up.css">
<script type="text/javascript">
    var flag = '<?php echo $_GET['flag']?$_GET['flag']:'' ?>';
    if(flag==1)
    {
        alert('修改ip地址成功');
    }else if(flag==2)
    {
        alert('设置新ip地址成功');
    }else if(flag==3)
    {
        alert('升级失败,文件超过了php中设置的最大限制');
    }else if(flag==4)
    {
        alert('升级失败,文件超过了表单设置的最大限制');
    }else if(flag==5)
    {
        alert('升级失败,文件只有部分被上传');
    }else if(flag==6)
    {
        alert('升级失败,文件没有被上传');
    }else if(flag==7)
    {
        alert('升级失败,请重新上传');
    }else if(flag==8)
    {
        alert('升级失败,请重新上传');
    }else if(flag==9)
    {
        alert('升级失败,请重新上传');
    }else if(flag==10)
    {
        alert('升级失败,规则中没有找到相应规则文件');
    }else if(flag==11)
    {
        alert('升级失败,请正确上传规则文件');
    }else if(flag==12)
    {
        alert('规则升级成功');
    }else if(flag==13)
    {
        alert('请上传后缀名为rulepack的正确格式的规则文件');
    }
</script>
<div class="container">
    <div class="row" style="margin-top: 10px">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 addtitle">
            <a href = "contact.php" >用户管理</a>
            <a href = "sys-tool.php" class= "secondT" >网络管理</a>
            <a href = "sys-jour.php" class= "secondT">系统日志</a>
            <a href = "message.php" class= "secondT" style = "background-color:#20262f;box-shadow:2px 2px 3px #000">设备管理</a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
           <ul class="thirdT">
                <li>
                    <a href="message.php">设备信息</a>
                </li>
                <li style="margin-left:10px">
                    <a href="cpu.php">CPU/内存统计</a>
                </li>
                <li style="margin-left:10px">
                    <a href="harddisk.php">硬盘资源统计</a>
                </li>
                <li style="margin-left:10px;background-color:#20262f;box-shadow:1px 1px 2px #000">
                    <a href="sys-equipment.php">特征库升级</a>
                </li>
                <li style="margin-left:10px">
                    <a href="close.php">重启/关机</a>
                </li>
            </ul>

            <div class = "fourT">规则升级</div>
            <div class = "toolMess">
	            <form action="rule_file.php" method="POST" enctype="multipart/form-data">
	            	<div class="messCon">
	            		<span class = "messName">规则名:</span>
	            		<input type="text" name="filename" class = "messInp">
	            		<i class = "messNotice"></i>
	            	</div>
	            	<div class="messCon">
	            		<span class = "messName">选择文件:</span>
	            		<div class="messInp">
	                        <a class="file">选择文件
	                            <input type="file" name="files" id="f" class="form-control">
	                        </a>
                            <p class="showfilemessage"></p>
                        </div>
                        <i class = "messNotice"></i>
	            	</div>
	            	<div class="messCon">
	            		<button type="submit" class="sub">提交</button>
	            	</div>
	            </form>
            </div>



           <!-- <div class = "fourT">关机 | 重启</div>
            <div class = "toolMess">
                <form>
	            	<div class="messCon">
	            	    <a href="reboot.php" style="color:#FFF" ><button type="button" class="sub restart">重启</button></a>
	            		<a href="shutdown.php" style="color:#FFF" ><button type="button" class="sub shutdown">关机</button></a>
	            	</div>
	            </form>
            </div> -->
        </div>
    </div>
</div>



<script src="./dist/js/jquery-3.1.1.min.js"></script>
<script src="./dist/js/bootstrap.min.js"></script>
   
</body>
</html>

<script type="text/javascript"> //异步根据网卡信息获取到ip地址、网关、子网掩码等信息
        var sele = $('.sel').val();

        $('.sel').on('change',function(){
            $.ajax({
                url:'updateIpAjax.php',
                type:'POST',
                cache:false,
                data:{selval:$('.sel').val()},
                error:function(){
                    //alert('失败');
                },
                success:function(data){
                    // alert(data);
                    var data = JSON.parse(data);//字符串转JSON格式
                    // alert(data[1]);
                    if(data){
                        $('#ip').attr('value',data[0]);
                        $('#netmask').attr('value',data[1]);
                    }else{
                        $('#ip').removeAttr('value');
                        $('#netmask').removeAttr('value');
                    }
                }
            });
        });


        $(".form-control").on("change",function(){
			 var filePath=$(this).val();
			 var arr=filePath.split("\\");
		     var fileName=arr[arr.length-1];
		     $(".showfilemessage").html(fileName);
		})
</script>
