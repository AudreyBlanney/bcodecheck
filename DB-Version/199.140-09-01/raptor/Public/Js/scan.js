//    上传文件input的样式
var type = false;
$(".file").on("change","input[type='file']",function(){
    var filePath=$(this).val();
    if(filePath.indexOf("zip")!=-1){
        var arr=filePath.split("\\")
        var fileName=arr[arr.length-1];
        $(".showfilemessage").html(fileName);
        type = true;
    }else{
        $('.form-inline').attr('onsubmit','return false');
        $(".showfilemessage").html("上传有误，请重新上传文件");
        $(".showfilemessage").css("color","red");
        type = false;
        return false
    }
})
//文件上传
$('.btn-sm').click(function(){
    var scan_name = $("input[name='scan_name']").val(); //获取任务名称
    var user_id = $("input[name='user_id']").val(); //获取用户id
    var formEditData = new FormData($("#postForm")[0]); //获取上传文件数据
    var upload_status = false;
    //获取任务名称是否重复/是否超出上传次数
    $.ajax({
        url: 'repeat_scan',
        type: "post",
        async : false,
        data : {'scan_name':scan_name},
        success: function (data){
            if(data.success == true){
                upload_status = false;
                alert(data.res);
            }else{
                upload_status = true;
            }
        },
        error:function(){
            upload_status = false;
            alert('文件上传失败');
        }
    });

    if(upload_status == true){
        jindu();
        $.ajax({
            url: '/raptor/upload',
            type: "post",
            contentType: false,
            processData: false,
            data : formEditData,
            dataType:'json',
            success: function (data){
                if(data.status == 1){
                    //跳转扫描
                    scan_file(user_id,scan_name);
                }else if(data.status == 2){
                    alert('上传文件超出限制'+data.limit_size+'MB,请重新上传');
                    location.href = 'scan';
                }else if(data.status == 3){
                    alert('上传文件限制数量'+data.limit_num+'个,请重新上传');
                    location.href = 'scan';
                }else if(data.status == 4){
                    alert('空文件包，请重新上传');
                    location.href = 'scan';
                }else if(data.status == 5){
                    alert('任务名称重复，请重新上传');
                }else if(data.status == 6){
                    alert('压缩包出现问题，请重新压缩上传');
                    location.href = 'scan';
                }
            },
            error:function(){
                alert('文件上传失败，请稍后再传');
                location.href = 'scan';
            }
        });
    }
});

//进度条及百分比样式
function jindu(){
    $('.btn-sm').html('上传中...');
    var per = 60+"%";
    $(".progress-bar").css("width",per);
    $(".percent").html(per);
}

//扫描文件
function scan_file(user_id,scan_name){
    $.ajax({
        url: '/zip/scan',
        type: "post",
        data:{'user_id':user_id,'scan_name':scan_name},
        dataType:'json',
        success: function (data){}
    });
    var per = 100+"%";
    $(".progress-bar").css("width",per);
    $(".percent").html(per);
    $('.btn-sm').html('上传扫描');
     setTimeout(function(){
     location.href = '/Home/Task/task';
     },1000);
}

