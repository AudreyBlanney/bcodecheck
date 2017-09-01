//定时查询获取数据进度
var autoplay = setInterval(
    function(){
    var proElement = '';
    $.ajax({
        url: 'task_data',
        type: "get",
        dataType:'json',
        success: function (data) {
            if(data.res == true){
                clearInterval(autoplay);
            }
            var res_data = data.data;
            if(res_data.length > 0){
                $.each(res_data,function(task_v,task_u){
                    if(task_u.scan_status == 1){
                        var scan_status = '扫描成功';
                    }else if(task_u.scan_status == 2){
                        var scan_status = '扫描失败';
                    }else if(task_u.scan_status == 3){
                        var scan_status = task_u.scan_sped;
                    }

                    proElement += "<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 bar-con'>" +
                    "<span class='task_name' title = "+task_u.task_name+">"+task_u.task_name+"</span>" +
                    "<div class='progress'>" +
                    "<div class='progress-bar' role='progressbar' aria-valuemin='0' aria-valuemax='100' style='width:"+task_u.scan_sped+"'></div>" +
                    "</div>" +
                    "<p class='percent'>"+scan_status +"</p>" +
                    "</div>"
                });
                $(".pro_area").html(proElement);
            }else{
                $('.notice').html('暂无扫描数据');
                clearInterval(autoplay);
            }
        },
        error: function (err) {
            $('.notice').html('暂无扫描数据');
            clearInterval(autoplay);
        }
    });
},1000)
