<?php
    include 'config/mysql_config.php';
    include 'config/session.php';
//获取一周的日期
$one_date = date('Y-m-d',strtotime("-1 day")); //获取前一天日期
$two_date = date('Y-m-d',strtotime("-2 day")); //获取前两天日期
$three_date = date('Y-m-d',strtotime("-3 day")); //获取前三天日期
$four_date = date('Y-m-d',strtotime("-4 day")); //获取前四天日期
$five_date = date('Y-m-d',strtotime("-5 day")); //获取前五天日期
$six_date = date('Y-m-d',strtotime("-6 day")); //获取前六天日期
$seven_date = date('Y-m-d',strtotime("-7 day")); //获取前七天日期
$x_data = array($seven_date,$six_date,$five_date,$four_date,$three_date,$two_date,$one_date);

//获取列表信息
$sql_hi = "select us.register_type,us.user_name,us.corporate_name,hi.upload_cs,hi.upload_success
          from {$tb_prefix}_user us left join {$tb_prefix}_upload_data up on us.id = up.user_id left join  {$tb_prefix}_upload_history hi on up.id = hi.data_id where hi.upload_type = 1";
$query_hi = $pdo->prepare($sql_hi);
$query_hi->execute();
$result_hi = $query_hi->fetchAll(PDO::FETCH_ASSOC);

//数据整理
$fe_array = array();
$code_user = array();
foreach($result_hi as $res_key => &$res_value){
    if (array_key_exists('user_name', $res_value)) {
        array_push($fe_array, $res_value['user_name']);
        $code_user = array_count_values($fe_array);
    }
    if($res_value['register_type'] == 1){
        $res_value['register_type'] = '个人用户';
    }elseif($res_value['register_type'] == 2){
        $res_value['register_type'] = '企业用户';
    }elseif($res_value['register_type'] == 3){
        $res_value['register_type'] = '系统用户';
    }
    $res_value['upload_success'] = $res_value['upload_success'];
}
$static_array = array();
foreach($code_user as $co_key => $co_value){
    $static_array[$co_key]['upload_success'] = '';
    foreach($result_hi as $re_key => $re_value){
        $mx_ar = array();
        if($co_key == $re_value['user_name']){
            $static_array[$co_key]['upload_success'] += $re_value['upload_success'] ;
            array_push($mx_ar,$re_value['upload_cs']);
            $static_array[$co_key]['upload_cs'] =  max($mx_ar);
            $static_array[$co_key]['register_type'] = $re_value['register_type'];
            $static_array[$co_key]['corporate_name'] = $re_value['corporate_name'];
            $static_array[$co_key]['user_name'] = $re_value['user_name'];
        }
    }
}
$co_array = array();
$data_json = '';
foreach($static_array as $key => &$value){
    array_push($co_array,$value['upload_success']);
    $value['upload_success'] = $value['upload_success'];
/*    if($value['upload_success'] >= 1024){
        $value['upload_success'] = ($value['upload_success'] / 1024).'MB';
    }else{
        $value['upload_success'] = $value['upload_success'].'KB';
    }*/
    $data_json .= json_encode($value).',';
}
array_multisort($co_array, SORT_DESC, $static_array); //按时间倒序排序
$data_json = trim($data_json,',');

//获取每天扫描的总次数
$sql_data_hi = "select  sum(upload_success) num ,
              max(upload_cs) upload_cs,date(upload_time) upload_time from {$tb_prefix}_upload_history  where date(upload_time) between '{$seven_date}' and  '{$one_date}'
              and upload_type = 1 group by date(upload_time) order by date(upload_time) ASC";
$query_data = $pdo->prepare($sql_data_hi);
$query_data->execute();
$result_data = $query_data->fetchAll(PDO::FETCH_ASSOC);
$str = '';
$res_str = '';
$str_c = '';
$str_upload_sc = '';
foreach($x_data as $x_key =>$x_value){
    $str = '';
    $str_c = '';
    foreach($result_data as $res_key => $res_value){
        if($x_value == $res_value['upload_time']){
            $str = $res_value['num'];
            $str_c = $res_value['upload_cs'];
        }
    }
    $str = $str ? $str : 0;
    $res_str .= $str.',';
    $str_c = $str_c ? $str_c : 0;
    $str_upload_sc .= $str_c.',';
}
$res_str = trim($res_str,','); //获取扫描大小
$str_upload_sc = trim($str_upload_sc,','); //获取扫描次数

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
    <link rel="stylesheet" href="css/iframe-stati-analy.css">
    <link rel="stylesheet" href="css/table.css">
    <!--<link rel="stylesheet" href="css/pop-up.css">-->

</head>
<body>

<div class="ecmap" id = "option"></div>

<!--------------滚动条----------->
<div class="scroll-container">
    <div class="scroll">
        <table class="table table-bordered" id = "table">
        </table>
    </div>
</div>




<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-table.js"></script>
<script src="js/echarts.js"></script>
<script src="js/dark.js"></script>
<script>
    //    ec图
    $(window).resize(function(){
        mainmap0.resize();
    })
    mainmap0 = echarts.init(document.getElementById('option'),'dark');
    option = {
        backgroundColor:'#2a323d',
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['扫描大小','扫描次数']
        },
        xAxis: [
            {
                type: 'category',
                data : [<?php echo "'{$seven_date}'".','."'$six_date'".','."'{$five_date}'".','."'{$four_date}'".','."'{$three_date}'".','."'{$two_date}'".','."'{$one_date}'"?>],
                axisPointer: {
                    type: 'shadow'
                }
            }
        ],
        yAxis: [
            {
                type: 'value',
                name: '大小',
                axisLabel: {
                    formatter: '{value} M'
                }
            },
            {
                type: 'value',
                name: '次数',
                axisLabel: {
                    formatter: '{value} 次'
                }
            }
        ],
        series: [
            {
                name:'扫描大小',
                type:'bar',
                data:[<?php echo $res_str;?>]
            },
            {
                name:'扫描次数',
                type:'line',
                yAxisIndex: 1,
                data:[<?php echo $str_upload_sc?>]
            }
        ]
    };

    mainmap0.setOption(option);

</script>
<script>
    $('#table').bootstrapTable({
        pagination: true,
        pageNumber:1,
        pageSize: 10,
        pageList: [10,20],
        columns: [
            {
                field: 'user_name',
                title: '用户名',
                align:'center',
                valign:'middle'
            }, {
                field: 'register_type',
                title: '用户类型',
                align:'center',
                valign:'middle'
            }, {
                field: 'corporate_name',
                title: '企业名',
                align:'center',
                valign:'middle'
            },{
                field: 'upload_cs',
                title: '总次数',
                align:'center',
                valign:'middle'
            },{
                field: 'upload_success',
                title: '总大小(M)',
                align:'center',
                valign:'middle'
            }],
        data:[<?php echo $data_json?>]
    });

</script>
<script>
    //删除操作
    $(document).on("click",".del",function(event){
        var targetName = $(event.target);
        var parentsTr = targetName.parents("tr");
        console.log(parentsTr)
    })


</script>
</body>
</html>
