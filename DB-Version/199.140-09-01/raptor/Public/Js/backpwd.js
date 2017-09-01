function checkemail(obj){
    var valemail=$(obj).val().trim();
    var emailvalid = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
    if (!emailvalid.test(valemail)) {
        $(".yz").css("display", "none");
        return false;
    }else{
        $(".yz").css("display", "block");
        $(".warningtel").css("display","none");
    }
}
//邮箱警告
function warningemail(obj){
    var valemail=$(obj).val().trim();
    var emailvalid = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
    if (!emailvalid.test(valemail)) {
        $(".warningemail").css("display","block");
        $(".warningemail").html("请输入正确的邮箱");
        return false;
    }else{
        $(".warningemail").css("display","none");
    }
}
$('.fs_code').click(function(){
    //发送验证码
    var email = $("input[name=email]").val();
    $.ajax({
        url: 'email',
        type: "post",
        dataType:'json',
        data:{'email':email},
        success: function (data) {
            $(".email_code").css("display","block");
            $('.email_code').html(data.res);
        },
        error:function(){
            alert('验证码发送失败，请重新发送');
        }
    });
});
//倒计时封装
var countdown=60;
function settime(obj) {
    if (countdown == 0) {
        $(obj).removeAttr("disabled");
        $(obj).html("免费获取验证码");
        countdown = 60;
        return;
    } else {
        $(obj).prop("disabled", true);
        $(obj).html("重新发送" + countdown);
        countdown--;
    }
    setTimeout(function() {
        settime(obj)
    },1000)
}
//判断输入是否为空
var  nullArr = ["邮箱不能为空","验证码错误","密码不能为空","密码不能为空"];
var reg_type = false;
$(".register").click(function(){
    $(".message-input").each(function(i){
        if($(this).val().trim() == ""){
            $(".warning").eq(i).css("display","block");
            $(".warning").eq(i).html(nullArr[i]);
            reg_type = false;
        }else{
            $(".warning").eq(i).css("display","none");
            $(".warning").eq(i).html('');
            reg_type = true;
        }
    })

    if(reg_type == true){
        $.ajax({
            url: '/Home/Modifypwd/modify_pwd',
            type: "post",
            dataType:'json',
            data: $("#form").serialize(),
            success: function (data) {
                prompt_error(data);
            },
            error: function (err) {
                alert('密码修改失败，请重新修改');
                location.href = "backpwd";
            }
        });
    }
})

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
        location.href = "/Home/login/login";
    }
}
