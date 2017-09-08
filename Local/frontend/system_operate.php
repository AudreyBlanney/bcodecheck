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
    <link rel="stylesheet" href="./dist/css/contact.css">
    <link rel="stylesheet" href="./dist/css/table.css">
    <link rel="stylesheet" href="./dist/css/pop-up.css">
<script type="text/javascript">
    var flag = <?php echo $_GET['flag'] ?>;
    if(flag==1)
    {
        alert('修改ip地址成功');
    }else if(flag==2)
    {
        alert('修改ip地址失败，请确认网卡信息是否正确');
    }
</script>
<div class="container">
    <div class="row rowpiece">
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" ><span><a href="contact.php" style=" background-color:rgba(34,41,48,.6); text-align:center;">用户列表</a></span><span class="adduser btn" data-toggle = "modal"  data-target="#creatrole_add_modal"></span></div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead"><a href="issues_bro.php">系统工具22222222222</a></div>
    </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <table id = "table" class="table"></table>
        </div>
    <!-- </div> -->
</div>
    <form action="update_ip.php" method="POST">
       <b>ip地址管理</b><br/>
        IP地址:<input type="text" value="ip" name='ip' style="color:red;" /> <br/>  
        网  卡:<input type="text" value="eth0" name='netcard' style="color:red;" /> <br/>  
        网  关:<input type="text" value="网关" name='gateway' style="color:red;" /> <br/>  
        子网掩码:<input type="text" value="255.255.255.0" name='netmask' style="color:red;" /> <br/>  
       <!--  端口号:<input type="text" value="端口号" name='port' style="color:red;" /><br/> -->
        <input type="submit" value="确定提交" style="background-color:red" /> 
        <a href="reboot.php"><input type="button" value="重启" style="background-color:red" /> </a>
        <a href="shutdown.php"><input type="button" value="关机" style="background-color:red" /></a><br/>
    </form>
 
</body>
</html>
