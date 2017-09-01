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
        location.href = "/Admin/Logout/logout";
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
            url: 'modify_pas',
            type: "post",
            dataType:'json',
            data: $("#form").serialize(),
            success: function (data) {
                prompt_error(data);
            },
            error: function (err) {
                alert('密码修改失败, 请重新修改');
                location.href = "changepas";
            }
        });
    }
});