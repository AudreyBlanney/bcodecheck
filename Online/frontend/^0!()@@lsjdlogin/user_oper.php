<?php
include 'config/mysql_config.php';
include 'config/session.php';

//获取信息
$sql_hi = "select hi.id hid,us.register_type,us.user_name,us.corporate_name,us.email,us.phone,hi.upload_success,hi.upload_type,hi.code_line_num,hi.lang_type,hi.scan_time,hi.upload_time
          from {$tb_prefix}_user us left join {$tb_prefix}_upload_data up on us.id = up.user_id left join  {$tb_prefix}_upload_history hi on up.id = hi.data_id order by hi.upload_time desc";
$query_hi = $pdo->prepare($sql_hi);
$query_hi->execute();
$result_hi = $query_hi->fetchAll(PDO::FETCH_ASSOC);

//数据整理
$remark = 1;
foreach($result_hi as $res_key => &$res_value){
    $res_value['remark'] = $remark;
    if(empty($res_value['hid'])){
        unset($result_hi[$res_key]);
    }

    if($res_value['register_type'] == 1){
        $res_value['register_type'] = '个人用户';
    }elseif($res_value['register_type'] == 2){
        $res_value['register_type'] = '企业用户';
    }elseif($res_value['register_type'] == 3){
        $res_value['register_type'] = '系统用户';
    }

    if($res_value['upload_type'] == 1){
        $res_value['upload_type'] = '成功';
    }else{
        $res_value['upload_type'] = '失败';
    }

    $hour=floor((strtotime($res_value['upload_time'])-strtotime($res_value['scan_time']))%86400/3600);
    $minute=floor((strtotime($res_value['upload_time'])-strtotime($res_value['scan_time']))%86400/60);
    $second=floor((strtotime($res_value['upload_time'])-strtotime($res_value['scan_time']))%86400%60);
    $hour = $hour ? $hour : 0;
    $minute = $minute ? $minute : 0;
    $second = $second ? $second : 0;
    $res_value['code_long'] = $hour.'时'.$minute.'分'.$second.'秒';
    $remark++;
}
$data_json = json_encode($result_hi);
/*echo '<pre>';
print_r($result_hi);exit;*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>匠迪云</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="shortcut icon" href="images/favicon.ico"/>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/iframe-useopera.css">
    <link rel="stylesheet" href="css/table.css">

    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-table.js"></script>
    <script src="js/echarts.js"></script>
    <script src="js/dark.js"></script>

</head>
<body>

<!--------------滚动条----------->
<div class="scroll-container">
    <div class="scroll">
        <table class="table table-bordered" id = "table">
        </table>
    </div>
</div>
<script>
    $('#table').bootstrapTable({
        pagination: true,
        pageNumber:1,
        pageSize: 10,
        pageList: [10,20],
        columns: [
            {
                field: 'remark',
                title: '序号',
                align:'center',
                valign:'middle'
            },{
                field: 'user_name',
                title: '用户名',
                align:'center',
                valign:'middle'
            },{
                field: 'register_type',
                title: '用户类型',
                align:'center',
                valign:'middle'
            }
            ,{
                field: 'corporate_name',
                title: '公司名',
                align:'center',
                valign:'middle'
            },{
                field: 'email',
                title: '邮件',
                align:'center',
                valign:'middle'
            },{
                field: 'phone',
                title: '电话',
                align:'center',
                valign:'middle'
            },{
                field: 'upload_success',
                title: '扫描包大小',
                align:'center',
                valign:'middle'
            },{
                field: 'upload_type',
                title: '检测状态',
                align:'center',
                valign:'middle'
            },{
                field: 'code_line_num',
                title: '扫描代码行数',
                align:'center',
                valign:'middle'
            },{
                field: 'lang_type',
                title: '扫描语言种类',
                align:'center',
                valign:'middle'
            },{
                field: 'code_long',
                title: '扫描时长',
                align:'center',
                valign:'middle'
            },{
                field: 'upload_time',
                title: '时间',
                align:'center',
                valign:'middle'
            }],
        data:<?php echo $data_json?>
    });

</script>
</body>
</html>