<?php
    include 'header_not_login.php';
?>
<link rel="stylesheet" href="./dist/css/signin.css">
<link rel="stylesheet" href="./dist/css/model.css">
<div class="container">
    <div class="row main">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <h2 class="title">欢迎使用<br>匠迪代码安全审查系统</h2>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <form action="" id="form" onsubmit="return false">
                <div class="xddw">
                    <!--<p class="xddw-p"><input type="radio" name="obj" id = "personal" class="xddw-radio" checked = checked><label for="personal"></label><span style="margin-right: 0;font-size: 14px;margin-top: 2px">通过手机找回密码</span></p>-->
                    <!--<p class="xddw-p" style="left: 33%"><input type="radio" name="obj" id = "public" class="xddw-radio"><label for="public"></label><span style="margin-right: 0;font-size: 14px;margin-top: 2px">通过邮箱找回密码</span></p>-->
                    <p class="xddw-p" style="left: 50%;width: 42%" ><span style="font-size: 12px;margin-right: 0;color: #ffffff">如果您还记得密码,请尝试<a href="login_not.php" >登陆</a></span></p>
                </div>

                <!--<div class="xddw message tel">-->
                    <!--<span>手机号：</span>-->
                    <!--<input type="tel" placeholder="请输入手机号码"  class="message-input"   oninput="checktel(this)" onblur="warning(this)">-->
                    <!--<p class="warning warningtel"></p>-->
                <!--</div>-->
                <div class="xddw message">
                    <span>邮箱：</span>
                    <input type="email" name="email" placeholder="请输入邮箱" class="message-input" oninput="checkemail(this)" onblur="warningemail(this)">
                    <p class="warning warningemail email"></p>
                </div>
                <div class="xddw message yz">
                    <span>验证码：</span>
                    <input type="text" name="email_code" placeholder="请输入验证码" style="width: 40%" class="message-input">
                    <button class="send-btn fs_code" type="button" onclick="settime(this)" >免费获取验证码</button>
                    <p class="warning email_code"></p>
                </div>

                <div class="xddw message">
                    <span>密码：</span>
                    <input type="password" name="password" placeholder="请输入密码" class="message-input">
                    <p class="warning password"></p>
                </div>

                <div class="xddw message">
                    <span>确认密码：</span>
                    <input type="password" name="password_ok" placeholder="请再次输入密码" class="message-input">
                    <p class="warning password_ok"></p>
                </div>

                <div class="xddw message">
                    <button class="register" type="submit" type = "button">确&nbsp;&nbsp;认</button>
                    <!--<button type="reset" value="复位" style="display:none;"></button>-->
                </div>
            </form>
        </div>
    </div>
</div>
<script src="./dist/js/jquery-3.1.1.min.js"></script>
<script src="./dist/js/bootstrap.min.js"></script>
<script>
    //    邮箱手机号出现与否
//    $("#personal").click(function(){
//        $(".tel").css("display","block");
//        $(".email").css("display","none");
//        $(".yz").css("display", "none");
//        $(".message-input").each(function(i){
//            $(this).val("");
//            $(".warning").eq(i).css("display","none");
//        });
//    });
//    $("#public").click(function(){
//        $(".email").css("display","block");
//        $(".tel").css("display","none");
//        $(".yz").css("display", "none");
//        $(".message-input").each(function(i){
//            $(this).val("");
//            $(".warning").eq(i).css("display","none");
//        });
//    });
    //  手机号正则
//    function checktel(obj){
//        var val=$(obj).val().trim();
//        var telvalid = /^1\d{10}$/;
//        if (!telvalid.test(val)) {
//            $(".yz").css("display", "none");
//            return false;
//        }else{
//            $(".yz").css("display", "block");
//            $(".warningtel").css("display","none");
//        }
//    }
    //手机号警告
//    function warning(obj){
//        var val=$(obj).val().trim();
//        var telvalid = /^1\d{10}$/;
//        if (!telvalid.test(val)) {
//            $(".warningtel").css("display","block");
//            $(".warningtel").html("请输入正确的手机号码");
//            return false;
//        }else{
//            $(".warningtel").css("display","none");
//        }
//    }
//    邮箱正则
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
            url: 'mail.php',
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
                url: 'modify_pwd.php',
                type: "post",
                dataType:'json',
                data: $("#form").serialize(),
                success: function (data) {
                    prompt_error(data);
                },
                error: function (err) {
                    alert('密码修改失败，请重新修改');
                    location.href = "back_pwd.php";
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
            location.href = "login_not.php";
        }
    }
</script>
</body>
</html>