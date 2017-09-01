$.ajax({
    url: 'userview_data',
    type: "get",
    dataType: 'json',
    success: function (data) {
        var json = data;
        $('#table').bootstrapTable({
            pagination: true,
            pageNumber:1,
            pageSize: 3,
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
            data:json
        });
    }
});


$(".user-add").click(function () {
    $(this).css("background-position","-143px -46px")
})
$(".btn-pri-def,.close,.modal").click(function(){
    $(".user-add").css("background-position","-143px 2px")
})

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
        url: 'delete_user',
        type: "post",
        dataType:'json',
        data: {'user_id':user_id,'user_name':user_name},
        success: function (data) {
            if(data){
                location.href = 'userview';
            }else{
                alert('删除失败，请重新删除');
            }
        },
        error: function (err) {
            alert('删除失败，请重新删除');
        }
    });
});

var reg = '<p class="war_not">' +
    '<span>用&nbsp;&nbsp;户&nbsp;&nbsp;名</span>' +
    '<input type="hidden" name="register_type" class="register_type" value=""> ' +
    '<input type="text" name="nickname" class="message-input" value="">  ' +
    '<label style="display:none;" class="warning nickname"></label> ' +
    '</p> ' +
    '<p class="war_not"> '+
    '<span>公&nbsp;&nbsp;司&nbsp;&nbsp;名</span> ' +
    '<input type="text" name="corporate_name" class="message-input" value="">' +
    '<label style="display:none;" class="warning corporate_name"></label> ' +
    '</p> ' +
    '<p class="war_not"> ' +
    '<span>新&nbsp;&nbsp;密&nbsp;&nbsp;码</span> ' +
    '<input type="password" name="password" class="message-input" placeholder="如果不修改密码，请不要填写">' +
    '<label style="display:none;" class="warning password"></label> ' +
    '</p> ' +
    '<p class="war_not"> ' +
    '<span>确认密码</span> ' +
    '<input type="password" name="password_ok" class="message-input" placeholder="如果不修改密码，请不要填写"> ' +
    '<label style="display:none;" class="warning password_ok"></label> ' +
    '</p> ' +
    '<p class="war_not"> ' +
    '<span>邮&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;件</span> ' +
    '<input type="email" name="email" class="message-input" value="">' +
    '<label style="display:none;" class="warning email"></label> ' +
    '</p> ' +
    '<p class="war_not"> ' +
    '<span>电&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;话</span> ' +
    '<input type="tel" name="phone" class="message-input" value=""> ' +
    '<label style="display:none;" class="warning phone"></label>' +
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
    $('input[name="register_type"]').val(register_type);
    $('input[name="nickname"]').val(user_name);
    $('input[name="corporate_name"]').val(corporate_name);
    $('input[name="email"]').val(email);
    $('input[name="phone"]').val(phone);

    $('input[name="upload_size"]').val(upload_size);
    $('input[name="upload_num"]').val(upload_num);
    $('input[name="content"]').html(content);
    $('input[name="user_id"]').val(user_id);


    if(switch_type == 1){
        $('.switch_type_y').attr('selected',true)
    }else{
        $('.switch_type_n').attr('selected',true)
    }
});

//修改用户信息
$('.user_form').click(function(){
    $.ajax({
        url: 'upload_user',
        type: "post",
        dataType:'json',
        data: $("#form").serialize(),
        success: function (data) {
            if(data.success){
                location.href = 'userview';
            }else{
                prompt_error(data);
            }

        },
        error: function (err) {
            alert('修改失败，请重新修改');
            location.href = 'userview';
        }
    });
});

//添加用户信息
$('.add_form').click(function(){
    $.ajax({
        url: 'register',
        type: "post",
        dataType:'json',
        data: $("#form_reg").serialize(),
        success: function (data) {
            prompt_error(data);
        },
        error: function (err) {
            alert('添加用户失败，请重新添加');
            location.href = 'userview';
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
        location.href = 'userview';
    }
}


