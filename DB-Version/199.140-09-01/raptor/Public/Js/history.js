$(function(){
    $.ajax({
        url: 'analytics_data',
        type: "get",
        dataType:'json',
        success: function (data) {
            var json = data;
            $('#table').bootstrapTable({
                pagination: true,
                pageNumber:1,
                pageSize: 10,
                pageList: [10,20],
                columns:[{
                    field: 'task_name',
                    title: '任务名称',
                    align: 'center'
                }, {
                    field: 'leak_file_type',
                    title: '项目文件类型',
                    align: 'center'
                }, {
                    field: 'upload_time',
                    title: '任务时间',
                    align: 'center'
                }, {
                    field: 'scan_status',
                    title: '检测状态',
                    align: 'center'
                },{
                    field: 'leak_num',
                    title: '缺陷总数',
                    align: 'center'
                },{
                    field: 'found_name',
                    title: '创建者',
                    align: 'center'
                },{
                    field:'dw_pre',
                    title: '操作',
                    align: 'center'
                }],
                data:json
            });
        }
    });
});

//下载扫描数据
$('body').on('click','table .dw_pre',function() {
    $("#iframe").remove();
    var z_w = $('body').width();
    var codes_type_h = $('body').height();
    var id = $(this).attr('id');
    var url = 'history_word.php?id='+id;
    $('body').append('<iframe id="iframe" frameborder=0 width=' + z_w + ' height=' + codes_type_h + ' marginheight=0 marginwidth=0 scrolling=no src=' + url + '></iframe>');
    $("#iframe").on("load", function () {
        location.href = 'word.php';
        $('#iframe').hide();
    });
});

//删除用户相关数据
$('body').on('click','table .del_data',function(){
    $(this).find('span').css('width','3rem');
    $(this).find('span').html('删除中...');
    var info_id = $(this).attr('info_id');
    var re_tr = $(this).parent().parent();
    $.ajax({
        url: 'del_data',
        type: "post",
        dataType:'json',
        data: {'info_id':info_id},
        success: function (data) {
            if(data){
                location.href = 'history';
            }else{
                alert('删除失败，请重新删除');
                location.href = 'history';
            }
        },
        error: function (err) {
            alert('删除失败，请重新删除');
            location.href = 'history';
        }
    });
});

//word下载
$('body').on('click','table .download_word',function() {
    $("#iframe").remove();
    $(this).css('width','3rem');
    $(this).find('span').html('下载中...');
    var info_id = $(this).attr('info_id');
    var z_w = $('body').width();
    var codes_type_h = $('body').height();
    var url = '/Home/Word/word?info_id=' + info_id;
    $('body').append('<iframe id="iframe" frameborder=0 width=' + z_w + ' height=' + codes_type_h + ' marginheight=0 marginwidth=0 scrolling=no src=' + url + ' style="display:none"></iframe>');
    $("#iframe").on("load", function () {
        $('.download').css('width', '2.6rem');
        $('.download span').css('color', '#fff');
        $('.download').find('span').html('下载');
    });
});