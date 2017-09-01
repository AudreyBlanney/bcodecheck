$.ajax({
    url: 'useroper_data',
    type: "get",
    dataType: 'json',
    success: function (data) {
        var json = data;
        $('#table').bootstrapTable({
            pagination: true,
            pageNumber:1,
            pageSize: 10,
            pageList: [10,20],
            columns: [
                {
                    field: 'remark',
                    title: '序号',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'user_name',
                    title: '用户名',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'register_type',
                    title: '用户类型',
                    align:'center',
                    valign:'middle'
                }
                ,{
                    field: 'corporate_name',
                    title: '公司名',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'email',
                    title: '邮件',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'phone',
                    title: '电话',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'upload_file_size',
                    title: '扫描包大小',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'scan_status',
                    title: '检测状态',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'code_line_num',
                    title: '扫描代码行数',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'leak_file_type',
                    title: '扫描语言种类',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'code_long',
                    title: '扫描时长',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'upload_time',
                    title: '扫描时间',
                    align:'center',
                    valign:'middle'
                }],
            data:json
        });

    }
});
