$(function(){
    gradeLevel = 'all';
    ctype  =  $("#language_title").find("li:first").addClass("active").find("a").text().trim();//第一个

    /**
     * 绘制饼图并填充数据
     */
    window.addEventListener('resize', function () {
        oc_gradeChart.resize();
        oc_riskChart.resize();
    });
    var drawChart = function(ctype,level){
        oc_gradeChart = echarts.init(document.getElementById('oc_grade'),'dark');
        oc_riskChart = echarts.init(document.getElementById('oc_risk'),'dark');

        // oc_gradeChart.showLoading({
        //     text:'正在加载中...',
        //     x: 'center',
        //     y: 'center',
        //     textStyle: { color: '#fff' },
        //     effectOption: {backgroundColor: '#2a323d'}
            
        // });
        // oc_riskChart.showLoading({
        //     text:'正在加载中...',
        //     x: 'center',
        //     y: 'center',
        //     textStyle: { color: '#fff' },
        //     effectOption: {backgroundColor: '#2a323d'}
            
        // });

        option_gradeChart = {
            backgroundColor:'#2a323d',
            title: {
                text: '程度分类视图',
                x:'left'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            toolbox: {
                x: 'right',
                feature: {
                    saveAsImage : {
                        show : true,
                        title : '保存为图片',
                        type : 'png',
                        lang : ['点击保存']
                    }
                }
            },
            legend: {
				x : '0%',
                y : '90%',
                width:'100%',
                height:'20%',
                data:[]
            },
            series: [{
                name:'面积模式',
                type:'pie',
                radius : [30, 110],
                center : ['50%', '50%'],
                roseType : 'area',
                data: [],
			itemStyle:{ 
				normal:{ 
					label:{ 
						show: true, 
						formatter: '{b} : {c} ({d}%)' 
					}, 
						labelLine :{show:true} 
				} 
	        }
            }]
        };

        option_riskChart = {
            backgroundColor:'#2a323d',
            title: {
                text: '风险分类视图',
                x:'left'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            toolbox: {
                x: 'right',
                feature: {
                    saveAsImage : {
                        show : true,
                        title : '保存为图片',
                        type : 'png',
                        lang : ['点击保存']
                    }
                }
            },
            legend: {
                x : '86%',
                y : 'center',
                width:'16%',
                height:'98%',
                data:[]
            },
            series: [{
                name: '问题分布',
                type: 'pie',
                radius: ['30%', '70%'],
                roseType : 'area',
                avoidLabelOverlap: true,
                selectedMode: 'single',
                selectedOffset: 20, //选中是扇区偏移量
                itemStyle:{
                    normal:{
                        label:{
                            show: true,
                            formatter: '{b} : {c} ({d}%)'
                        },
                        labelLine :{show:true}
                    }
                },
                data: []
            }]
        };

        $.ajax({
            type:"GET",
            sync:true,
            url:"owerview.php?codetype="+ctype,
            jsonType:'json',
            success:function(result,status){
                if(status == 'success'){
                    var json_owner = eval('('+ result +')'); //由JSON字符串转换为JSON对象
                    var gradeObj = json_owner.owner_grade;
                    var riskObj = json_owner.owner_risk;
                    /*var riskData = json_owner.owner_data;*/

                    /*画饼图*/
                    var gradeArr = [];//封装legend
                    var riskArr = [];

                    var gradeArrData = [];//封装数据
                    var riskArrData = [];

                    for (var key in gradeObj)
                    {
                        gradeArr.push(key);
                        var gradeDataObj = {};
                        gradeDataObj.name = key;
                        if(gradeDataObj.name == '高'){
                            gradeDataObj.itemStyle = {normal:{color:'#dd6b66'}};
                        }else if(gradeDataObj.name == '中'){
                            gradeDataObj.itemStyle = {normal:{color:'#fece56'}};
                        }else{
                            gradeDataObj.itemStyle = {normal:{color:'#93d665'}};
                        }
                        gradeDataObj.value = gradeObj[key];
                        gradeArrData.push(gradeDataObj);
                    }
                    option_gradeChart.legend.data = gradeArr;
                    option_gradeChart.series[0].data = gradeArrData;
                    oc_gradeChart.hideLoading();
                    document.getElementById("oc_grade").removeAttribute("_echarts_instance_");
                    oc_gradeChart.setOption(option_gradeChart);

                    for (var key in riskObj){
                        riskArr.push(key);
                        var riskDataObj = {};
                        riskDataObj.name = key;
                        riskDataObj.value = riskObj[key];
                        riskArrData.push(riskDataObj);
                    }
                    /*option_riskChart.legend.data = riskArr;*/
                    option_riskChart.series[0].data = riskArrData;
                    oc_riskChart.hideLoading();
                    oc_riskChart.setOption(option_riskChart);
                    /*填充表格*/
                    /*console.log(riskData);*/
                }else{
                    console.log("请求错误!");
                }
            },error:function(){
                console.log("请求错误!");
            }
        },'json');

        /**
         * 程度分布点击展示具体
         */
        oc_gradeChart.on("click",function(params){
            gradeLevel = params.name;

            //oc_riskChart.showLoading({text:'重新加载中...'});
            //根据高中低程度来分别展示右边风险分布图
            var url = encodeURI("owerview.php?codetype="+ctype+"&level="+gradeLevel);
            $.ajax({
                type:"GET",
                sync:true,
                url:url,
                jsonType:'json',
                success:function(result,status){
                    var json_ownerUpdate = eval('('+ result +')');
                    var riskArrDataUpdate = [];
                    var riskObjUpdate = json_ownerUpdate.owner_risk;
                    for (var key in riskObjUpdate)
                    {
                        var riskDataObjUpdate = {};
                        riskDataObjUpdate.name = key;
                        riskDataObjUpdate.value = riskObjUpdate[key];
                        riskArrDataUpdate.push(riskDataObjUpdate);
                    }
                    option_riskChart.series[0].data = riskArrDataUpdate;
                    option_riskChart.title.text = params.name;
                    oc_riskChart.hideLoading();
                    oc_riskChart.setOption(option_riskChart);
                }
            });
            //更新表
            var data_url = encodeURI('owerdata.php?codetype='+ctype+'&level='+gradeLevel);
            $('#table').bootstrapTable('refresh', {url:data_url});
        });
		
		/**
        * 风险分类视图点击展示具体
        */
        oc_riskChart.on("click",function(params){ 
            risk_type = params.name;
			//更新表
			var risk_url = encodeURI('riskdata.php?codetype='+ctype+'&risk_type='+risk_type);
			$('#table').bootstrapTable('refresh', {url:risk_url});

        });

        //填充表格
        $('#table').bootstrapTable({
            method: 'get',
            url: "owerdata.php?codetype=" + ctype + "&level=" + level,
            cache: false,
            sidePagination: "client",

            columns: [{
                title: '序号',
                formatter: function(value, row, index) {
                    return index + 1;
                },
                align: 'center'
            }, {
                field: 'severity',
                title: '风险程度',
                align: 'center'
            }, {
                field: 'warning_type',
                title: '漏洞名称',
                align: 'center'
            }, {
                field: 'file',
                title: '漏洞位置',
                align: 'center'
            }, {
                field: 'code',
                title: '漏洞片段',
                align: 'center'
            }],
            pageSize: 5,
            pageList: [5,10, 25, 50, 100],
            pagination: true
        });
    }

    
    $.each($(".btn-codetype"),function(){
        $(this).on("click",function(){
            ctype = $(this).find('a').html().trim();
            drawChart(ctype,'all');//加载选中的
            $('#table').bootstrapTable('refresh', {url: 'owerdata.php?codetype='+ctype+'&level=all'});
        });
    });

drawChart(ctype,'all');//加载默认

//恢复视图
// $(".reset-view").click(function(){
// 	gradeLevel = "all";
// 	//表格恢复
// 	$('#table').bootstrapTable('refresh', {url: 'owerdata.php?codetype='+ctype+'&level=all'});
// });

});

