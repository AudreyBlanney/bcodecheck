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
//个人注册
$user_sql = "select count(register_time) num,date(register_time) register_time from {$tb_prefix}_user where
            date(register_time)  between '{$seven_date}' and  '{$one_date}' and register_type = 1 group by date(register_time) order by register_time ASC";
$user_query = $pdo->prepare($user_sql);
$user_query->execute();
$user_res = $user_query->fetchAll(PDO::FETCH_ASSOC);
$user_str = '';
$str = '';
foreach($x_data as $x_key =>$x_value){
    $str = '';
    foreach($user_res as $us_key => $us_value){
        if($x_value == $us_value['register_time']){
            $str = $us_value['num'];
        }
    }
    $str = $str ? $str : 0;
    $user_str .= $str.',';
}
$user_str = trim($user_str,',');

//获取公司注册
$gs_sql = "select count(register_time) num,date(register_time) register_time from {$tb_prefix}_user where
            date(register_time)  between '{$seven_date}' and  '{$one_date}' and register_type in(2,3) group by date(register_time) order by date(register_time) ASC";
$gs_query = $pdo->prepare($gs_sql);
$gs_query->execute();
$gs_res = $gs_query->fetchAll(PDO::FETCH_ASSOC);
$gs_str = '';
$gstr = '';
foreach($x_data as $x_key =>$x_value){
    $gstr = '';
    foreach($gs_res as $gs_key => $gs_value){
        if($x_value == $gs_value['register_time']){
            $gstr = $gs_value['num'];
        }
    }
    $gstr = $gstr ? $gstr : 0;
    $gs_str .= $gstr.',';
}
$gs_str = trim($gs_str,',');

//获取用户全部信息
$user_sql = "select us.id,us.user_name,us.corporate_name,us.email,us.phone,us.register_type,us.register_time,us.content,us.switch_type,up.upload_size,up.upload_num from {$tb_prefix}_user us left join {$tb_prefix}_upload_data
                up on us.id = up.user_id order by us.register_time desc";
$query = $pdo->prepare($user_sql);
$query->execute();
$user_result = $query->fetchAll(PDO::FETCH_ASSOC);
$remark = 1;
foreach($user_result as $user_key => &$user_value){
    $user_value['remark'] = $remark;
    $user_value['caozuo'] = '
    <span class="opera del btn" data-toggle = "modal"  data-target="#del_modal" title="删除" id='."'{$user_value['id']}'".' user_name='."'{$user_value['user_name']}'".'></span>
    <span class="opera edit btn" data-toggle = "modal"  data-target="#edit_modal" title="编辑" id='."'{$user_value['id']}'".' user_name='."'{$user_value['user_name']}'".'
    corporate_name='."'{$user_value['corporate_name']}'".' email='."'{$user_value['email']}'".' phone='."'{$user_value['phone']}'".'
    register_type='."'{$user_value['register_type']}'".' upload_num='."'{$user_value['upload_num']}'".' upload_size='."'{$user_value['upload_size']}'".'
    switch_type='."'{$user_value['switch_type']}'".'
    content='."'{$user_value['content']}'".'></span>';

    if($user_value['register_type'] == 1){
        $user_value['register_type'] = '个人用户';
    }elseif($user_value['register_type'] == 2){
        $user_value['register_type'] = '企业用户';
    }elseif($user_value['register_type'] == 3){
        $user_value['register_type'] = '系统用户';
    }
    if($user_value['switch_type'] == 1){
        $user_value['switch_type'] = '启用';
    }else{
        $user_value['switch_type'] = '未启用';
    }
    $remark++;
}
$data_json = json_encode($user_result);

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
    <link rel="stylesheet" href="css/iframe-useview.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/pop-up.css">

    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-table.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/echarts.js"></script>

</head>
<body class="modal-open">

<div class="ecmap" id = "main"></div>

<!--------------滚动条----------->
<div class="scroll-container">
    <div class="scroll">
        <table class="table table-bordered" id = "table">
        </table>
    </div>
</div>

<!--删除弹框-->
<div class="modal fade" id="del_modal"  aria-labelledby="del_title" aria-hidden="true">
    <div class="modal-dialog">
        <!-- <form action=""> -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="del_title">
                    信息提示
                </h4>
            </div>
            <form action="" type=""  onsubmit="return false">
                <div class="modal-body" style="margin-top: 20px">
                    <p>
                        是否删除该用户？
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-pri-def del_ok">
                        确定
                    </button>
                    <button type="button" class="btn btn-default btn-pri-def" data-dismiss="modal">
                        取消
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--编辑用户弹窗-->
<div class="modal fade" id="edit_modal"  aria-labelledby="edit_title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="edit_title">
                    编辑
                </h4>
            </div>
            <form action="" type="" id = "form" onsubmit="return false">
                <div class="modal-body" style="margin-top: 20px">
                    <p class="scan_cs">
                        <span>扫描次数</span>
                        <input type="hidden" class="user_id" name="user_id" value="">
                        <input type="number" name="upload_size" value="" placeholder="(次/天)" class="upload_size">

                    </p>
                    <p>
                        <span>扫描大小</span>
                        <input type="number" name="upload_num" value="" placeholder="(M/次)" class="upload_num">
                    </p>
                    <p>
                        <span>是否启用</span>
                        <select name="switch_type">
                            <option value="1" class="switch_type_y">是</option>
                            <option value="2" class="switch_type_n">否</option>
                        </select>
                    </p>
                    <div class="remark">
                        <span >备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注</span>
                        <textarea name="content" class="content"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-pri-def user_form">
                        确定
                    </button>
                    <button type="button" class="btn btn-default btn-pri-def" data-dismiss="modal">
                        取消
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var myChart = echarts.init(document.getElementById('main'),'dark');
    option = {
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['企业用户','个人用户'],
            itemWidth:50,
            textStyle:{
                color:'#fff'

            }
        },
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : [<?php echo "'{$seven_date}'".','."'$six_date'".','."'{$five_date}'".','."'{$four_date}'".','."'{$three_date}'".','."'{$two_date}'".','."'{$one_date}'"?>],
                axisLabel: {
                    show: true,
                    textStyle: {
                        color: '#fff'
                    }
                }
            }
        ],
        yAxis : [
            {
                type: 'value',
                axisLabel: {
                    formatter: '{value} 人',
                    show: true,
                    textStyle: {
                        color: '#fff'
                    }
                }
            }
        ],
        series : [
            {
                name:'个人用户',
                type:'line',
                stack: '总量',
                smooth: true,
                symbolSize: 8,
                symbol:'circle',
                itemStyle: {
                    normal: {
                        color: "#0b613c",
                        lineStyle: {
                            color: '#0b613c'
                        }
                    }
                },
                areaStyle : {
                    normal : {
                        color : new echarts.graphic.LinearGradient(0, 0, 0, 1,
                            [
                                {
                                    offset : 0,
                                    color : 'rgba(29, 65, 73,1)'
                                },
                                {
                                    offset : 1,
                                    color : 'rgba(43, 84, 92,0.5)'
                                } ], false)
                    }
                },
                data:[
                    <?php echo $user_str?>
                ]
            },
            {
                name:'企业用户',
                type:'line',
                stack: '总量',
                symbolSize: 8,
                symbol:'circle',
                itemStyle: {
                    normal: {
                        color: '#10a0b5'
                    }
                },
                data:[
                    <?php echo $gs_str?>
                ]
            }

       ]
    };
    myChart.setOption(option);
</script>
<script>
    //表格
    $('#table').bootstrapTable({
        pagination: true,
        pageNumber:1,
        pageSize: 10,
        pageList: [10,20],
        search:true,  //搜索框
        strictSearch:true, //全局搜索
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
            },{
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
                field: 'content',
                title: '备注',
                align:'center',
                valign:'middle'
            },{
                field: 'switch_type',
                title: '是否启用',
                align:'center',
                valign:'middle'
            },{
                field: 'register_time',
                title: '创建时间',
                align:'center',
                valign:'middle'
            },{
                field: 'caozuo',
                title: '操作',
                align:'center',
                valign:'middle'

            }],
        data:<?php echo $data_json?>
    });

    //删除用户
    var user_id = '';
    var user_name = '';
    $('.del').click(function(){
        user_id = $(this).attr('id');
        user_name = $(this).attr('user_name');
    });

    $('.del_ok').click(function(){
        $.ajax({
            url: 'delete_user.php',
            type: "post",
            dataType:'json',
            data: {'user_id':user_id,'user_name':user_name},
            success: function (data) {
                if(data.success){
                    location.href = 'user_view.php';
                }else{
                    alert('删除失败，请重新删除');
                }
            },
            error: function (err) {
                alert('删除失败，请重新删除');
            }
        });
    });

    var reg = '<p class="user_name war_not">' +
        '<span>用&nbsp;&nbsp;户&nbsp;&nbsp;名</span>' +
        '<input type="hidden" name="register_type" class="register_type" value=""> ' +
        '<input type="text" name="user_name" class="user_name" value=""> ' +
        '</p> ' +
        '<p class="corporate_name war_not"> '+
        '<span>公&nbsp;&nbsp;司&nbsp;&nbsp;名</span> ' +
        '<input type="text" name="corporate_name" class="corporate_name" value=""> ' +
        '</p> ' +
        '<p class="password war_not"> ' +
        '<span>新&nbsp;&nbsp;密&nbsp;&nbsp;码</span> ' +
        '<input type="password" name="password"> ' +
        '</p> ' +
        '<p class="password_ok war_not"> ' +
        '<span>确认密码</span> ' +
        '<input type="password" name="password_ok"> ' +
        '<label class="password_ok" style="display:none;color: red"></label> ' +
        '</p> ' +
        '<p class="email war_not"> ' +
        '<span>邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;件</span> ' +
        '<input type="email" name="email" class="email" value=""> ' +
        '</p> ' +
        '<p class="phone war_not"> ' +
        '<span>电&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;话</span> ' +
        '<input type="tel" name="phone" class="phone" value=""> ' +
        '</p>';

    //修改用户信息
    $('.opera').click(function(){
        var register_type = $(this).attr('register_type'); //获取用户类型
        if(register_type != 3){
            $('.war_not').remove();
        }else{
            $('.war_not').remove();
            $('.scan_cs').before(reg);
        }
        //获取数据
        var corporate_name = $(this).attr('corporate_name'); //获取公司名称
        var user_name = $(this).attr('user_name'); //获取用户名
        var email = $(this).attr('email'); //获取邮箱
        var phone = $(this).attr('phone'); //获取手机号

        var user_id = $(this).attr('id'); //获取用户id
        var upload_size = $(this).attr('upload_size'); //获取允许上传次数
        var upload_num = $(this).attr('upload_num'); //获取允许上传大小
        var switch_type = $(this).attr('switch_type'); //获取是否启用
        var content = $(this).attr('content'); //获取备注内容
        //添加数据
        $('.register_type').val(register_type);
        $('.user_name').val(user_name);
        $('.corporate_name').val(corporate_name);
        $('.email').val(email);
        $('.phone').val(phone);

        $('.upload_size').val(upload_size);
        $('.upload_num').val(upload_num);
        $('.content').html(content);
        $('.user_id').val(user_id);


        if(switch_type == 1){
            $('.switch_type_y').attr('selected',true)
        }else{
            $('.switch_type_n').attr('selected',true)
        }
    });

    //修改用户信息
    $('.user_form').click(function(){
        $.ajax({
            url: 'upload_user.php',
            type: "post",
            dataType:'json',
            data: $("#form").serialize(),
            success: function (data) {
                if(data.success){
                    location.href = 'user_view.php';
                }else{
                    if(data.res){
                        $('.password_ok').html(data.res);
                        $('.password_ok').css('display','block');
                    }else{
                        alert('修改失败，请重新修改');
                    }
                }
            },
            error: function (err) {
                alert('修改失败，请重新修改');
                //location.href = 'user_view.php';
            }
        });
    });

    //添加用户信息
    $('.add_form').click(function(){
        $.ajax({
            url: 'register.php',
            type: "post",
            dataType:'json',
            data: $("#form_reg").serialize(),
            success: function (data) {
                prompt_error(data);
            },
            error: function (err) {
                alert('添加用户失败，请重新添加');
                location.href = 'user_view.php';
            }
        });
    });

    //错误信息提示
    function prompt_error(data){
        if(!data.success){
            var title = data.title;
            var result_err = data.result_err;
            $(".message-input").each(function(i){
                if($(this).attr('name') == title){
                    $('.'+title).css("display","block");
                    $('.'+title).html(result_err);
                }else{
                    $(".warning").eq(i).css("display","none");
                    $(".warning").eq(i).html('');
                }
            })
        }else{
            location.href = 'user_view.php';
        }
    }

</script>

</body>
</html>