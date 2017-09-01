//    公司名称出现与否

    var appBol = true;  //个人用户  企业用户
    $("#personal").click(function(){
        appBol = true; 
        $(".company").css("display","none");     //公司输入栏隐藏
        $(".yz").css("display","none");         //手机验证码框隐藏
        $(".message-input").each(function(i){  //信息输入框清空 警告信息隐藏
            $(this).val("");
            $(".warning").eq(i).css("display","none");
        });
        createCode();   //从新随机验证码
    });


    $("#public").click(function(){
        appBol = false; 
        $(".company").css("display","block");
        $(".yz").css("display","none");
        $(".message-input").each(function(i){
            $(this).val("");
            $(".warning").eq(i).css("display","none");
        });
        createCode();

//  判断是否为空，下面提交用
        var comval = $(".comInp").val().trim();
        if(comval == ""){
            comBol = false;
        }else{
            comBol = true;
        }
    });

// 用于公司名称为空的提示
    function comBlur(obj){
       var val = $(obj).val().trim();
       if(val == ""){
          $(".corporate_name").css("display","block").html("公司名称不能为空");
       }else{
          $(".corporate_name").css("display","none");
       }
    } 


//  手机号change事件,验证码框出现
    function checktel(obj){
        var val=$(obj).val().trim();
        var telvalid = /^1\d{10}$/;
        if (!telvalid.test(val)) {
            $(".yz").css("display", "none");
            $(".warningtel").css("display","block").html("请输入正确的手机号码");
            return false;
        }else{
            $(".yz").css("display", "block");
            $(".warningtel").css("display","none");
        }
    }
//  用户名 电话 邮箱 密码正则数组
    var arr = [/^[\x7f-\xff0-9a-zA-Z]{3,15}$/,/^1\d{10}$/,"/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i",/^[a-zA-Z0-9,，、。?!.'？！@#$%^&*·`~<>；;‘'【】]{6,20}$/];
//  错误信息提示数组
    var arr1 = ["用户名格式不对，从重新输入","手机号格式不对，请重新输入","邮箱格式不正确，请重新输入","密码格式不对，请重新输入"]
    var reg_type = false;
    function warning(obj,n){
        var val = $(obj).val().trim();
        var telvalid = eval("("+arr[n]+")");
        if(!telvalid.test(val)){
            $(".sj").eq(n).css("display","block").html(arr1[n]);
            reg_type = false;
        }else{
            $(".sj").eq(n).css("display","none");
            reg_type = true;
        }
    }
//  确认密码验证
    var qrmmBol = false;
    function qrmm(obj){
        var passVal = $(".message-input[name = 'password']").val().trim();
        var qrmmVal = $(obj).val().trim();
        if(passVal == qrmmVal){
            qrmmBol = true;
            $(".password_ok").css("display","none");

        }else{
            qrmmBol = false;
            $(".password_ok").css("display","block").html("请重新输入密码");
        }
    }
    $('.fs_code').click(function(){
        //发送验证码
        var phone = $("input[name=phone]").val();
        $.ajax({
            url: '/home/Verification/phone_code',
            type: "post",
            dataType:'json',
            data:{'phone':phone},
            success: function (data) {
                if(!data.success){
                    $(".warningtel").css("display","block");
                    $('.warningtel').html(data.res);
                }else{
                    $(".warningtel").css("display","block");
                    $('.warningtel').html(data.res);
                    $("input[name='phone']").attr('readonly',true);
                    settime();
                }
            },
            error:function(){
                $("input[name='phone']").attr('readonly',false);
                alert('验证码发送失败，请重新发送');
            }
        });
    });
//  倒计时封装
    var countdown=60;
    function settime() {
        if (countdown == 0) {
            $('.fs_code').removeAttr("disabled");
            $('.fs_code').html("免费获取验证码");
            $("input[name='phone']").attr('readonly',false);
            countdown = 60;
            return;
        } else {
            $('.fs_code').prop("disabled", true);
            $('.fs_code').html("重新发送" + countdown);
            countdown--;
        }
        setTimeout(function() {
                    settime()
        },1000)
    }
//  相关服务协议（不应该通过点击事件，应该通过属性判断）
   var bol = true;
   $(".notify label").click(function(){
       if(bol){
           $(".register").prop("disabled", true);
           bol = false;
       }else{
           $(".register").removeAttr("disabled");
           bol = true;
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
            location.href = "/Home/Index/index";
        }
    }
    $(function(){
        createCode();
    });
//  验证码
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

//用户注册
$('.register').click(function(){
    if(appBol == true){
        subMit(); 
    }else{
        subMit();
        if($(".comInp").val().trim() == ""){
            $(".corporate_name").css("display","block").html("公司名称不能为空");
        }else{
            $(".corporate_name").css("display","none");
        }
    } 
});

// 提交按钮点击封装
  function subMit(){
      $(".sj").parent().find('input').each(function(i){
        warning($(this),i);
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
    if(reg_type == true&&qrmmBol == true){
        $.ajax({
            url: 'register',
            type: 'post',
            dataType:'json',
            data: $("#form").serialize(),
            success: function (data) {
                prompt_error(data);
            },
            error: function (err) {
                alert('注册失败, 请重新注册');
                location.href = "signin";
            }
        });
    }


  }
