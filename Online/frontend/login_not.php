<?php
    include 'header_not_login.php';
?>
<link rel="stylesheet" href="./dist/css/signin.css">

<div class="container">
    <div class="row main">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <h2 class="title">欢迎使用<br>匠迪代码安全审查系统</h2>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <form action= "" id="form" onsubmit="return false">
                <div class="xddw message">
                    <span>用户名：</span>
                    <input type="text" name="user_name" placeholder="请输入用户名/手机号/邮箱" class="message-input">
                    <p class="warning"></p>
                </div>

                <div class="xddw message">
                    <span>密码：</span>
                    <input type="password" name="password" placeholder="请输入密码" class="message-input">
                    <p class="warning warning_err"></p>
                </div>

                <div class="xddw message">
                    <span>验证码：</span>
                    <input type="text" id="inputCode" placeholder="输入验证码" class="message-input" style="width: 45%">
                    <div class="code" id="checkCode" onclick="createCode()" ></div>
                    <p class="warning login_code"></p>
                </div>

                <div class="xddw message">
                    <button class="register" type="submit" type = "button">登&nbsp;&nbsp;陆</button>
                    <!--<button type="reset" value="复位" style="display:none;"></button>-->
                </div>
                <div class="xddw message" style="font-size: 12px">
                    <span ><a href="signin.php">注册新用户</a></span>
                    <span style="left: 76%"><a href="back_pwd.php">忘记密码？</a></span>
                </div>

                 <p style = "width:440px;font-size:14px;text-align:center;color:#22a8de">(建议使用谷歌浏览器)</p>
            </form>
        </div>
    </div>
</div>

<script src="./dist/js/jquery-3.1.1.min.js"></script>
<script src="./dist/js/bootstrap.min.js"></script>
<script>
    //判断是否为空
    var  nullArr = ["用户名不能为空","密码不能为空"];
    var reg_type = false;
    $(".register").click(function(){
        $(".message-input").each(function(i){
            if($(this).val().trim() == ""){
                $(".warning").eq(i).css("display","block");
                $(".warning").eq(i).html(nullArr[i]);
                reg_type = false;
				createCode();
            }else{
                $(".warning").eq(i).css("display","none");
                $(".warning").eq(i).html('');
                reg_type = true;
            }
        })
        //判断验证码
        var inputCode=document.getElementById("inputCode").value;
        if(inputCode.length <= 0) {
            reg_type = false;
            $('.login_code').css("display","block");
            $('.login_code').html("请输入验证码");
			createCode();
        }
        else if(inputCode.toUpperCase() != code.toUpperCase()) {
            reg_type = false;
            $('.login_code').css("display","block");
            $('.login_code').html("验证码输入有误");
            createCode();
        }
        else {
            reg_type = true;
        }
        if(reg_type == true){
            $.ajax({
                url: 'login.php',
                type: "post",
                dataType:'json',
                data: $("#form").serialize(),
                success: function (data) {
                    if(data.success){
                        location.href = "scan.php";
                    }else{
                        $(".warning_err").css("display","block");
                        $('.warning_err').html(data.res);
						createCode();
                    }
                },
                error: function (err) {
                     alert('登录失败，请重新登录');
                     //location.href = "login_not.php";
                 }
            });
        }
    })
    $(function(){
        createCode();
    });
    //验证码
    var code;
    function createCode() {
        code = "";
        var codeLength = 6; //验证码的长度
        var checkCode = document.getElementById("checkCode");
        var codeChars = new Array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
                'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'); //所有候选组成验证码的字符，当然也可以用中文的
        for(var i = 0; i < codeLength; i++) {
            var charNum = Math.floor(Math.random() * 26);
            code += codeChars[charNum];
        }
        if(checkCode) {
            checkCode.className = "code";
            checkCode.innerHTML = code;
        }
    }
</script>
</body>
</html>