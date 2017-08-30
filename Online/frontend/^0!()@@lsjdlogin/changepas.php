<?php
    include 'config/session.php';
    include 'config/mysql_config.php';
    include 'header.php';
?>
<link rel="stylesheet" href="css/userinfo.css">
<div class="container">
    <div class="row main">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="useheibg"></div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 usehei" >
                <form action="" class="formpas" onsubmit="return false" id="form">
                    <div class="messpas">
                        <span class="formmark">原密码：</span>
                        <input type="password" class="pasinput message-input" name="ya_password">
                        <label for="" class="forwarn ya_password"></label>
                    </div>
                    <div class="messpas">
                        <span class="formmark">新密码：</span>
                        <input type="password" class="pasinput message-input" placeholder="请输入密码 数字 字母 标点（6-20）" name="password">
                        <label for="" class="forwarn password"> </label>
                    </div>
                    <div class="messpas">
                        <span class="formmark">确认密码：</span>
                        <input type="password" class="pasinput message-input" placeholder="请再次确认密码" name="password_ok">
                        <label for="" class="forwarn password_ok"> </label>
                    </div>
                    <button type="submit" class="btnpas register">确认</button>
                </form>
        </div>
    </div>
</div>
<script>
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
                    $(".forwarn").eq(i).css("display","none");
                    $(".forwarn").eq(i).html('');
                }
            })
        }else{
            location.href = "logout.php";
        }
    }
    //判断输入是否为空
    var  nullArr = ["原密码不能为空","新密码不能为空","确认密码不能为空"];
    //用户注册
    var reg_type = false;
    $('.register').click(function(){
        $(".message-input").each(function(i){
            if($(this).val().trim() == ""){
                $(".forwarn").eq(i).css("display","block");
                $(".forwarn").eq(i).html(nullArr[i]);
                reg_type = false;
            }else{
                $(".forwarn").eq(i).css("display","none");
                $(".forwarn").eq(i).html('');
                reg_type = true;
            }
        })
        if(reg_type == true){
            $.ajax({
                url: 'modify_pas.php',
                type: "post",
                dataType:'json',
                data: $("#form").serialize(),
                success: function (data) {
                    prompt_error(data);
                },
                error: function (err) {
                    alert('密码修改失败, 请重新修改');
                    location.href = "changepas.php";
                }
            });
        }
    });
</script>
</body>
</html>