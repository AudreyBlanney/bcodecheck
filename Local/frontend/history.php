<?php
include 'session.php';
include 'header.php';
?>

<link rel="stylesheet" href="./dist/css/table.css">
<link rel="stylesheet" href="./dist/css/proman.css">
<script src="./dist/js/bootstrap-table.js"></script>


<div class="container">
    <div class="row rowpiece">
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" ><a href="history.php" style=" background-color:rgba(34,41,48,.6);box-shadow: 2px 2px 3px #000;">工程列表</a></div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" ><a href="task.php" >任务列表</a></div>
    </div>
    <div class="row rowpiece">
        <table class="table table-bordered " id = "table" style="text-align: center;ord-wrap:break-word;word-break:break-all">

        </table>
    </div>

</div>

<script>

    $(function(){
        $.ajax({
            url: 'analytics_his.php',
            type: "get",
            dataType:'json',
            success: function (data) {
                var json = data;
                $('#table').bootstrapTable({
                    pagination: true,
                    pageNumber:1,
                    pageSize: 10,
                    pageList: [10,20],
                    columns:[ {
                        field: 'name',
                        title: '任务名称',
                        align: 'center'
                    }, {
                        field: 'remark',
                        title: '项目文件类型',
                        align: 'center'
                    }, {
                        field: 'date',
                        title: '任务时间',
                        align: 'center'
                    }, {
                        field: 'test_type',
                        title: '检测状态',
                        align: 'center'
                    },{
                        field: 'defect_num',
                        title: '缺陷总数',
                        align: 'center'
                    },{
                        field: 'found_name',
                        title: '创建者',
                        align: 'center'
                    },{
                        field: 'dw_pre',
                        title: '操作',
                        align: 'center'
                    }],
                    data:json
                    　
                });
            }
        });
    });


    
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
</script>
</body>
</html>