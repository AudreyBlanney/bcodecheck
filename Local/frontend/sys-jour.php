<?php
include "session.php";
include "header.php";
include "mysql_config.php";
include "history_data.php";

//记录下载文件数据
if(!empty($_POST['download_type'])){
	$res_his = history_data($_SESSION['user_name'],'系统日志'.$_POST['download_type'].'文件导出','成功',1,date('Y-m-d H:i:s'));
}
$jour_type = !empty($_GET['jour_type']) ? $_GET['jour_type'] : 1;

//查询所有用户数据
    $mysql_str = "select id,user_name,diction,be_record,be_res,data_time from {$tb_prefix}_history_data where jour_type = {$jour_type} order by data_time desc";
    $query = $pdo->prepare($mysql_str);
    $query->execute();
    $res = $query->fetchall(PDO::FETCH_ASSOC);
    $num = 1;
    foreach($res as $key => &$value){
    	$value['num'] = $num;	
	    $value['diction'] = $value['diction'] == 1 ? '系统管理员' : '代码审计';
	    $num++;
    }
    $json_res = json_encode($res);
?>
    <link rel="stylesheet" href="./dist/css/contact.css">
    <link rel="stylesheet" href="./dist/css/table.css">
    <link rel="stylesheet" href="./dist/css/pop-up.css">
<style>
    .btn{
        padding:6px 12px;
        border:1px solid #2a323d;
    }
</style>
<div class="container">
    <div class="row" style="margin-top: 10px">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 addtitle">
            <a href = "contact.php" >用户管理</a>
            <a href = "sys-tool.php" class= "secondT">网络管理</a>
            <a href = "sys-jour.php" class= "secondT" style=" background-color:rgba(34,41,48,.6);box-shadow: 2px 2px 3px #000;">系统日志</a>
            <a href = "message.php" class= "secondT">设备管理</a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 useMes">
             <ul class="thirdT">
                <li class="jour_type_1">
					<a href="sys-jour.php?jour_type=1" style="color:#fff">操作日志</a>
				</li>
                <li style="margin-left:10px" class="jour_type_2">
					<a href="sys-jour.php?jour_type=2" style="color:#fff">系统日志</a>
				</li>
                <li style="margin-left:10px" class="jour_type_3">
					<a href="sys-jour.php?jour_type=3" style="color:#fff">业务日志</a>
				</li>
            </ul>

            <div id="toolbar">
                    <select class="form-control">
                       <option value="all" disabled selected hidden>请选择导出范围</option> 
                       <option value="all">导出全部</option>
                       <option value="selected">导出勾选</option>
                    </select>
                </div>
            <table id = "table" class="table table-bordered" style="word-wrap:break-word;word-break:break-all;margin-top: 20px" 
                        data-maintain-selected="true"
                        data-search="true"
                        data-advanced-search="true"
                        data-id-table="advancedTable"
                        data-pagination="true"
                        data-click-to-select="true"
                        data-show-toggle="true"
                        data-show-columns="true"
                        data-toolbar="#toolbar"
                        data-show-export="true">
                    <!--表格-->
                </table>
            
        </div>
    </div>
</div>

    <script src="./dist/js/bootstrap-table.js"></script>
    <script src="./dist/js/bootstrap-table-export.js"></script>
    <script src="./dist/js/tableExport.js"></script>
    <script src="./dist/js/ga.js"></script>
    <script>
    // 三级标题点击样式改变
		$(function(){
			var jour_type = <?php echo $jour_type;?>;
			$('.jour_type_'+jour_type).css({"background-color":"#20262f","box-shadow":"1px 1px 2px #000"});
		});

        $('#table').bootstrapTable({

	         showExport: true,
	         toolbar: '#toolbar',
	         exportDataType: 'basic',
             pageNumber:1,
             pageSize: 10,
             pageList: [10,20],
            columns: [{
                field:'state',
                checkbox:true
                }, {
                    field: 'num',
                    title: '序号',
                    align:'center',
                    valign:'middle'

                }, {
                    field: 'user_name',
                    title: '操作者',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'diction',
                    title: '角色名',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'be_record',
                    title: '行为记录',
                    align:'center',
                    valign:'middle'
                },{
                    field:'be_res',
                    title:'行为结果',
                    align:'center',
                    valian:'middle'
                },{
                    field:'data_time',
                    title:'时间',
                    align:'center',
                    valian:'middle'
                }
            ],
		data:<?php echo $json_res?>

        });

        var $table = $('#table');
        $(function () {
            $('#toolbar').find('select').change(function () {

                $table.bootstrapTable('refreshOptions', {
                    exportDataType: $(this).val(),
                })

            });
        })
		
		// 文件下载
		
		$("body").on('click','.dropdown-menu>li',function(){
			var download_type = $(this).attr('data-type'); //获取下载文件类型
			$.ajax({
			   url: 'sys-jour.php',
			   type: 'POST',
			   data: {'download_type':download_type},
			   success:function(data){}
			});
		})
    </script>
</body>
</html>
