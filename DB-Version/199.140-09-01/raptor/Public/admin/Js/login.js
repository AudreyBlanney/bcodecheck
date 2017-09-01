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
            url: 'login_on',
            type: "post",
            dataType:'json',
            data: $("#form").serialize(),
            success: function (data) {
                if(data.success){
                    location.href = "/Jd400bocde690obsec6007/Index/index";
                }else{
                    $(".warning_err").css("display","block");
                    $('.warning_err').html(data.res);
                    createCode();
                }
            },
            error: function (err) {
                alert('登录失败，请重新登录');
                location.href = "login";
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
