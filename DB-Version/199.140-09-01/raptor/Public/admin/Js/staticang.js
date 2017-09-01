$.ajax({
    url: 'staticang_data',
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
                    field: 'user_name',
                    title: '用户名',
                    align:'center',
                    valign:'middle'
                }, {
                    field: 'register_type',
                    title: '用户类型',
                    align:'center',
                    valign:'middle'
                }, {
                    field: 'corporate_name',
                    title: '企业名',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'scan_num',
                    title: '总次数',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'scan_size',
                    title: '总大小(M)',
                    align:'center',
                    valign:'middle'
                }],
            data:json
        });
    }
})


