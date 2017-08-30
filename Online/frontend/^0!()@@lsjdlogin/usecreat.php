<?php
include 'config/mysql_config.php';
 include 'config/session.php';
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
    <link rel="stylesheet" href="css/iframe-usecreat.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/pop-up.css">



</head>
<body>

<div class="creat-user "><p>用户创建<span class="user-add btn" data-toggle = "modal"  data-target="#creatrole_add_modal"></span></p></div>

<!--------------滚动条----------->
<div class="scroll-container">
    <div class="scroll">
        <table class="table table-bordered" id = "table">
        </table>
    </div>
</div>

<!--添加用户弹框-->

<div class="modal fade" id="creatrole_add_modal"  aria-labelledby="creatrole_add_title" aria-hidden="true">
    <div class="modal-dialog">
        <!-- <form action=""> -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="creatrole_add_title">
                    新建
                </h4>
            </div>
            <form action="" type="" id="form_reg" onsubmit="return false">
                <div class="modal-body" style="margin-top: 20px">
                    <p>
                        <span>用&nbsp;&nbsp;户&nbsp;&nbsp;名</span>
                        <input type="text" name="user_name" class="message-input">
                        <label style="display:none;" class="warning user_name"></label>
                    </p>
                    <p>
                        <span>公&nbsp;&nbsp;司&nbsp;&nbsp;名</span>
                        <input type="text" name="corporate_name">
                    </p>
                    <p>
                        <span>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码</span>
                        <input type="password" name="password">
                    </p>
                    <p>
                        <span>确认密码</span>
                        <input type="password" name="password_ok" class="message-input">
                        <label style="display: none" class="warning password_ok"></label>
                    </p>

                    <p>
                        <span>邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;件</span>
                        <input type="email" name="email">
                    </p>
                    <p>
                        <span>电&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;话</span>
                        <input type="tel" name="phone">
                    </p>
                    <p>
                        <span>扫描次数</span>
                        <input type="text" name="upload_num" value="10">
                    </p>
                    <p>
                        <span>扫描大小</span>
                        <input type="text" name="upload_size" value="20">
                    </p>
                    <p>
                        <span>是否启用</span>
                        <select name="switch_type">
                            <option value="1" selected = "selected">是</option>
                            <option value="2">否</option>
                        </select>
                    </p>
                    <div class="remark">
                        <span>备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注</span>
                        <textarea name="content"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-pri-def add_form">
                        确定
                    </button>
                    <button type="button" class="btn btn-default btn-pri-def" data-dismiss="modal">
                        取消
                    </button>
                </div>
            </form>
        </div>
        <!-- </form> -->
    </div>
</div>
<!--编辑用户弹窗-->
<div class="modal fade" id="edit_modal"  aria-labelledby="edit_title" aria-hidden="true">
    <div class="modal-dialog">
        <!-- <form action=""> -->
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
                        <input type="number" name="upload_num" value="" placeholder="(次/天)" class="upload_num">

                    </p>
                    <p>
                        <span>扫描大小</span>
                        <input type="number" name="upload_size" value="" placeholder="(M/次)" class="upload_size">
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
        <!-- </form> -->
    </div>
</div>
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-table.js"></script>
<script>
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
        }, {
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
            field: 'upload_num',
            title: '扫描次数(次)',
            align:'center',
            valign:'middle'
        },{
            field: 'upload_size',
            title: '扫描大小(M)',
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
        }
        ,{
            field: 'caozuo',
            title: '操作',
            align:'center',
            valign:'middle',
            events:'del—edit'
        }],
        data:<?php echo $data_json?>
    });

</script>
<script>
    $(".user-add").click(function () {
        // $(this).css("color","#2188b6");
        $(this).css("background-position","-143px -46px")
    })
    $(".btn-pri-def,.close,.modal").click(function(){
        // $(".creat-user").css("color","#ffffff");
        $(".user-add").css("background-position","-143px 2px")
    })



</script>
<script>
    //  左侧导航第一个
    var bol0 = true;
    $(".left-nav").eq(0).click(function(){
        if(bol0){
            $(this).css("color","#ffffff");
            $(".usemessage").css("background-position","2px 6px")
            $(".rx").eq(0).css("background-position"," -260px 6px");
            $(".list").eq(0).slideUp();
            bol0 = false;
        }else{
            $(this).css("color","#2188b6");
            $(".usemessage").css("background-position","2px -41px")
            $(".rx").eq(0).css("background-position"," -260px -36px");
            $(".list").eq(0).slideDown();
            bol0 = true;
        }
    })
    //  左侧导航第2个
    var bol1 = true;
    $(".left-nav").eq(1).click(function(){
        if(bol1){
            $(this).css("color","#2188b6");
            $(".runanalyze").css("background-position"," -46px -40px");
            $(".rx").eq(1).css("background-position"," -260px -36px");
            $(".list").eq(1).slideDown();
            bol1 = false;
        }else{
            $(this).css("color","#ffffff");
            $(".runanalyze").css("background-position"," -46px 6px");
            $(".rx").eq(1).css("background-position"," -260px 6px");
            $(".list").eq(1).slideUp();
            bol1 = true;
        }
    })
</script>
<script>
    //    导航对应的iframe
    var arr = ["iframe-usecreat.html","iframe-useview.html","iframe-useopera.html","33"];
    $(".list-li").each(function(i){
        $(this).click(function(){
            $(".list-li").css("color","#ffffff");
            $(this).css("color","#2188b6");
            $(".iframe").attr("src",arr[i]);
            console.log(arr[i]);
        })
    })

    //删除用户
    var user_id = '';
    var user_name = '';
    $('body').delegate('.del','click',function(){
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
                    location.href = 'usecreat.php';
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
    $('body').delegate('.edit','click',function(){
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
                    location.href = 'usecreat.php';
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
                //location.href = 'usecreat.php';
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
                location.href = 'usecreat.php';
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
            location.href = 'usecreat.php';
        }
    }

</script>
</body>
</html>