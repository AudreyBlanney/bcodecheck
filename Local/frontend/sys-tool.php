
<?php
    // exec("sudo cat /proc/net/dev | grep eth1 | awk -F'[: ]+' '{print $3}'",$a);
    // exec("sudo cat /proc/net/dev | grep eth1 | awk -F'[: ]+' '{print $11}'",$b);
    // $old_inbw = $a[0];
    // $old_outbw = $b[0];
    // sleep(3);
    // exec("sudo cat /proc/net/dev | grep eth1 | awk -F'[: ]+' '{print $3}'",$c);
    // exec("sudo cat /proc/net/dev | grep eth1 | awk -F'[: ]+' '{print $11}'",$d);
    // $new_inbw = $c[0];
    // $new_outbw = $d[0];
    // $inbw = ($new_inbw-$old_inbw)/3;
    // $outbw = ($new_outbw-$old_outbw)/3;
    // echo $new_inbw.'----'.$old_inbw.'<br>';
    // echo "IN:$inbw bytes OUT:$outbw bytes";

include "session.php";
include "header.php";
include "mysql_config.php";

if(!empty($_GET['flags'])){
	$flags = $_GET['flags'];
}else{
	$flags = '';
}

?>
    <link rel="stylesheet" href="./dist/css/contact.css">
    <link rel="stylesheet" href="./dist/css/table.css">
    <link rel="stylesheet" href="./dist/css/pop-up.css">
    <link rel="stylesheet" href="./dist/css/bootstrap-editable.css">
<script type="text/javascript">
    var flag = "<?php echo !empty($_GET['flag']) ? $_GET['flag'] : "''"; ?>";
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
    }else if(flag==14)
    {
        alert('请填写正确格式的ip地址');
    }else if(flag==15)
    {
        alert('请填写正确格式的网关');
    }else if(flag==16)
    {
        alert('请填写正确格式的子网掩码');
    }else if(flag==17)
    {
        alert('请注意:不要关闭正在使用的网卡');
    }
</script>
<div class="container">
    <div class="row" style="margin-top: 10px">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 addtitle">
            <a href = "contact.php" >用户管理</a>
            <a href = "javascript:void(0);" class= "secondT" id="networks" style=" background-color:rgba(34,41,48,.6);box-shadow: 2px 2px 3px #000;">网络管理</a>
            <a href = "sys-jour.php" class= "secondT">系统日志</a>
            <a href = "sys-equipment.php" class= "secondT">设备管理</a>

        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <ul class="thirdT">
				<li style = "background-color:#20262f;box-shadow:1px 1px 2px #000">网络设置</li>
				<li style="margin-left:10px">以太网口</li>
	        </ul>

			<div class="message" style="display:block">
			     <table id = "table0" class="table"> 
								
                 </table>
			</div>
			<div class="message">
				 <table id = "table1" class="table"></table>
			</div>
        </div>
    </div>
</div>



<script src="./dist/js/jquery-3.1.1.min.js"></script>
<script src="./dist/js/bootstrap.min.js"></script>
<script src="./dist/js/bootstrap-table.js"></script>
<script src="./dist/js/bootstrap-table-editable.js"></script>
<script src="./dist/js/bootstrap-editable.js"></script>
</body>
</html>
<?php 
    exec("lspci | egrep -i --color 'network|ethernet'",$arr);//物理网卡数量

    $num = 0;
    foreach($arr as $key => $value){
        $ress[] = array('name' => 'eth'.$num);
        $ajaxdata[] = 'eth'.$num;

        $num++;
    }

    $str = file_get_contents("/etc/network/interfaces");
    $data = explode(PHP_EOL,$str);

    $str2 = file_get_contents("/etc/nginx/sites-available/raptor");
    $data2 = explode(PHP_EOL,$str2);

    
    //网络设置
    foreach($data as $key => $value){
        foreach($ress as $k => $val){
            if($value == 'auto '.$val['name']){
                $ress[$k]['describe'] = substr($data[$key-1],1);
                $ress[$k]['interfaceIP'] = substr($data[$key+2],8);
                $ress[$k]['mask'] = substr($data[$key+3],8);
                $ress[$k]['gateway'] = substr($data[$key+4],8);
                
                $flag = 1;
            }
                $ress[$k]['port'] = substr(substr($data2[1],8),0,strlen(substr($data2[1],8))-1);
                exec("cat /sys/class/net/".$val['name']."/address",$arr2[$val['name']]); //mac地址
                foreach($arr2 as $k_arr2 => $v_arr2){
                    if($k_arr2 == $val['name'] && $v_arr2){
                       $ress[$k]['add'] = $v_arr2[0];
                    }
                }

                exec("cat /sys/class/net/".$val['name']."/mtu",$arr3[$val['name']]); //MTU值
                foreach($arr3 as $k_arr3 => $v_arr3){
                    if($k_arr3 == $val['name'] && $v_arr3){
                       $ress[$k]['mtu'] = $v_arr3[0];
                    }
                }
        }
    }

    $json_res = json_encode($ress); 

//网卡流量
$strs = @file("/proc/net/dev"); 

    //以太网口
    $num = 0;
    foreach($arr as $key => $value){
        $result[] = array('name' => 'eth'.$num);
        $num++;
    }

    foreach($data as $key => $value){
        foreach($result as $k => $val){
            $result[$k]['enter'] = '0 KB/s';
         $result[$k]['exit'] = '0 KB/s';
            if($value == 'auto '.$val['name']){
                $result[$k]['describe'] = substr($data[$key-1],1);

for ($i = 2; $i < count($strs); $i++ ) : 
preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );
                
                $aaa = $inbw / 1024;
                //$result[$k]['enter'] = $aaa.' KB/s';
endfor;
                $bbb = $outbw / 1024;
                //$result[$k]['exit'] = $bbb.' KB/s';
                $result[$k]['light'] = '电口';

                exec("sudo ethtool ".$val['name'],$shuang[$val['name']]); //双工模式
                foreach($shuang as $k_shuang => $v_shuang){
                    if(@$v_shuang[6] && $k_shuang == $val['name']){

                        $abc = 'Supports auto-negotiation: Yes';
                        if(trim($v_shuang[6]) == trim($abc)){
                            $result[$k]['pattern'] = '自适应';
                        }else if(trim($v_shuang[9]) == trim(" Advertised auto-negotiation: No")){
                            if(trim($v_shuang[11]) == trim("Duplex: Full")){
                                $result[$k]['pattern'] = '全双工';
                            }else if(trim($v_shuang[11]) == trim("Duplex: Half")){
                                $result[$k]['pattern'] = '半双工';
                            }
                        }
                    }
                }
            }
        }
    }
    
    foreach($result as $k_res => &$v_res){
        $network_status = '';
        exec("sudo mii-tool {$v_res['name']}",$network_status);
        if(!empty($network_status[0])){
            $link_ok = explode(',',$network_status[0]);
            if(trim(@$link_ok[1]) == 'link ok'){
                $v_res['status'] = 'up';
            }else{
                $v_res['status'] = 'down';
            }
        }else{
             $v_res['status'] = 'down';
        }

    }
 
    $json_result = json_encode($result); 

?>
<script>
//标题样式出现与否
$(".thirdT>li").each(function(i){
    $(this).click(function(){
        $(this).css({"background-color":"#20262f","box-shadow":"1px 1px 2px #000"});
        $(this).siblings().css({"background-color":"#2a323d","box-shadow":"0px 0px 0px #000"});
        $(".message").css("display","none");
        $(".message").eq(i).css("display","block");
    })
})


//网路设置表格
    $('#table0').bootstrapTable({
        columns: [
            {
                field: 'name',
                title: '接口名称', 
                align:'center',
                valign:'middle'
            },{
                field: 'describe',
                title: '接口描述',  //要可编辑
                editable: true,
                align:'center',
                valign:'middle'
              
            },{
                field: 'interfaceIP',
                title: '接口IP',
                 editable: true,
                align:'center',
                valign:'middle'
            },{
                field: 'mask',
                title: '掩码',
                editable: true,
                align:'center',
                valign:'middle'
            },{
                field: 'gateway',
                title: '网关',
                editable: true,
                align:'center',
                valign:'middle'
            },{
                field: 'port',
                title: '登陆端口',
                editable: true,
                align:'center',
                valign:'middle'
            },{
                field: 'add',
                title: 'MAC地址',
                editable: true,
                align:'center',
                valign:'middle'
            },{
                field: 'mtu',
                title: 'MTU',
                editable: true,
                align:'center',
                valign:'middle'
            },{
                field: 'statuss',  
                title: '状态',
                align:'center',
                valign:'middle',
                events:'state',
                formatter:delFormatter
            }],

        data:<?php echo $json_res; ?>,

            onEditableSave: function (field, row, oldValue, $el) {
                $.ajax({
                    type: "post",
                    url: "./editEth.php",
                    data: row,
                    // dataType: 'json',
                    success: function (data, status) {
                        if (status == "success") {
                                var flag = data;

                                if(flag==1)
                                {
                                    alert('请填写正确格式的子网掩码');
                                }else if(flag==2)
                                {
                                    alert('请填写正确格式的ip地址');
                                }else if(flag==3)
                                {
                                    alert('请填写正确格式的网关');
                                }else if(flag==4)
                                {
                                    alert('mtu值超出范围,请设置9000以内的mtu值');
                                }else if(flag==5)
                                {
                                    alert('请输入正确格式的MAC地址');
                                }else if(flag==6)
                                {
                                    alert('ip地址处在同一网段，请重新设置ip地址或子网掩码');
                                }                        
                        }
                    },
                    error: function () {
                        alert("未知的网络错误，请重试");
                    },
                    complete: function () {
                        
                    }

                });
            }

    });

    $('.open').on('click',function(){
        var jk_name = $(this).parent().parent().find("td:first-child").html();
        
        window.location.href = "up_network.php?work=" + jk_name;
    });

    $('.clo').on('click',function(){
        var jk_name = $(this).parent().parent().find("td:first-child").html();

       window.location.href = "down_network.php?work=" + jk_name;
    });
    
 
    function delFormatter(value, row, index) {
        return [
            '<span class="operation open" title="开启">开启</span>&nbsp;&nbsp;<span class="operation clo" title="关闭">关闭</span>'
        ]}
//以太网口表格

   $('#table1').bootstrapTable({
        columns: [
            {
                field: 'name',
                title: '接口名称', 
                align:'center',
                valign:'middle'
            },{
                field: 'describe',
                title: '接口描述',  //要可编辑
               
                align:'center',
                valign:'middle'
            },{
                field: 'enter',
                title: '入端速率',
               
                align:'center',
                valign:'middle'
            },{
                field: 'exit',
                title: '出端速率',
                
                align:'center',
                valign:'middle'
            },{
                field: 'pattern',
                title: '双工模式',
                
                align:'center',
                valign:'middle'
            },{
                field: 'light',
                title: '光电模式',
               
                align:'center',
                valign:'middle'
            },{  
                field: 'status',    
                title: '状态',
                align:'center',
                valign:'middle',
            }],
            data:<?php echo $json_result; ?>
    });


   //$("#networks").on('click',function(){
        var ajaxdata = <?php echo json_encode($ajaxdata); ?>;
        $.ajax({
            type: "post",
            url: "getRate.php",
            data: {ajaxdata:ajaxdata},
            dataType: 'json',
            success: function (data) { 
                // alert(data);

                $.each(data[0],function(i,n){//入端速率
                    $("#table1 tbody>tr").find("td:first-child").each(function(){
                        // alert($(this).html());
                        if($(this).html() == i){
                           $(this).parent().find('td:nth-child(3)').html(n/5 + ' KB/s');
                        }
               
                    });
                });

                $.each(data[1],function(m,k){//出端速率
                    $("#table1 tbody>tr").find("td:first-child").each(function(){
                        // alert($(this).html());
                        if($(this).html() == m){
                            $(this).parent().find('td:nth-child(4)').html(k/5 + ' KB/s');
                        }
               
                    });
                });

                 var eth = $("#table1 tbody>tr").find("td:first-child").html();
                
            },
            error: function () {
                //alert("失败");    
            },
        });
  // });

</script>

