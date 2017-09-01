    var scr = document.getElementsByTagName("script");
    var data = scr[scr.length-1].getAttribute("data");
    var info_id = scr[scr.length-1].getAttribute("info_id");
    if(data != 'word'){
        var center_str = ['30%','50%'];
        var center_str1 = ['70%','50%'];
        var toolbox_str =  {
            x: 'right',
            feature: {
                saveAsImage : {
                    show : true,
                    title : '保存为图片',
                    type : 'png',
                    lang : ['点击保存']
                }
            }
        }
    }else{
        var center_str =['50%','50%'];
        var center_str1 =['50%','50%'];
    }
    //    EC图
    $(window).resize(function(){
        mainmap0.resize();
        mainmap1.resize();
        mainmap2.resize();
    })
    mainmap0 = echarts.init(document.getElementById('mainmap0'),'dark');
    mainmap1 = echarts.init(document.getElementById('mainmap1'),'dark');
    mainmap2 = echarts.init(document.getElementById('mainmap2'),'dark');
    option0 = {
        animation : false,
        backgroundColor:'#2a323d',
        title : {
            text: '文件分类视图',

            x:'left'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        toolbox: toolbox_str,
        legend:{
            x : '60%',
            y : 'center',
            width:'20%',
            height:'100%',
            data:[]
        },

        calculable : true,
        series : [
            {
                name:'面积模式',
                type:'pie',
                radius : [30, 110],
                center : center_str,
                roseType : 'area',
                data:[],
				itemStyle:{ 
					normal:{ 
						label:{ 
							show: true, 
							formatter: '{b} : {c} ({d}%)' 
						}, 
							labelLine :{show:true} 
					} 
	            }
            }
        ]
    };
    option1 = {
        animation : false,
        backgroundColor:'#2a323d',
        title : {
            text: '程度分类视图',
            x:'left'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        toolbox: toolbox_str,
        legend: {
            x : '20%',
            y : 'center',
            width:'20%',
            height:'100%',
            data:[]
        },

        calculable : true,
        series : [
            {
                name:'面积模式',
                type:'pie',
                radius : [30, 110],
                center : center_str1,
                roseType : 'area',
                data:[],
                
				itemStyle:{ 
					normal:{ 
						label:{ 
							show: true, 
							formatter: '{b} : {c} ({d}%)' 
						}, 
							labelLine :{show:true} 
					} 
	            }
            }
        ]
    };
    option2 = {
        animation : false,
        backgroundColor:'#2a323d',
        title : {
            text: '风险分类视图',

            x:'left'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
	formatter:function(val){    
	    return (val.length > 21 ? (val.slice(0,21)+"...") : val ); 
			
	},
        toolbox:toolbox_str,
        legend: {
            x : '60%',
            y : 'center',
            width:'20%',
            height:'98%',
            data:[]
        },

        calculable : true,
        series : [
            {
                name:'面积模式',
                type:'pie',
                radius: ['30%', '70%'],
                center:center_str,
                roseType : 'area',
                avoidLabelOverlap: true,
                data:[]
            }
        ]
    };
    mainmap0.setOption(option0);
    mainmap1.setOption(option1);
    mainmap2.setOption(option2);

    if(info_id != null){
        cc = info_id
    }else{
        var aa = $('#zong').attr('href');
        var arr=aa.split('/');
        var bb = arr[5].split('.');
        var cc = bb[0];
    }


        $.ajax({
            type:"GET",
            async:false,
            url:"/Home/Analysis/issues2?info_id="+cc,
            success:function(result,status){
                if(status == 'success'){
    
                    var json_owner = eval('('+ result +')'); //由JSON字符串转换为JSON对象
                    var fileObj = json_owner.data2;
                    var gradeObj = json_owner.data4;
                    var riskObj = json_owner.data6;

                    /*画饼图*/
                    var gradeArr = [];//封装legend
                    var riskArr = [];
                    var fileArr = [];

                    var gradeArrData = [];//封装数据
                    var riskArrData = [];
                    var fileArrData = [];

                    for (var key in gradeObj)
                    {
                        gradeArr.push(key);
                        var gradeDataObj = {};
                        gradeDataObj.name = key;
                        if(gradeDataObj.name == '高'){
                            gradeDataObj.itemStyle = {normal:{color:'#dd6b66'}};
                        }else if(gradeDataObj.name == '中'){
                            gradeDataObj.itemStyle = {normal:{color:'#fece56'}};
                        }else if(gradeDataObj.name == '低'){
                            gradeDataObj.itemStyle = {normal:{color:'#93d665'}};
                        }else{
                            gradeDataObj.itemStyle = {normal:{color:'#4179c7'}};
                        }
                        gradeDataObj.value = gradeObj[key];
                        gradeArrData.push(gradeDataObj);
                    }
                    option1.series[0].data = gradeArrData;

                    for (var key in riskObj){
                        riskArr.push(key);
                        var riskDataObj = {};
                        riskDataObj.name = key;
                        riskDataObj.value = riskObj[key];
                        riskArrData.push(riskDataObj);
                    }
                    option2.series[0].data = riskArrData;

                    for (var key in fileObj){
                        fileArr.push(key);
                        var fileDataObj = {};
                        fileDataObj.name = key;
                        fileDataObj.value = fileObj[key];
                        fileArrData.push(fileDataObj);
                    }
                    if(data !='word'){
                        option0.legend.data = fileArr;
                        option1.legend.data = gradeArr;
                        option2.legend.data = riskArr;
                    }

                    option0.series[0].data = fileArrData;


                    mainmap0.setOption(option0);
                    mainmap1.setOption(option1);
                    mainmap2.setOption(option2);
                }else{
                    console.log("请求错误!");
                }
            },error:function(){
                console.log("请求错误!");
            }
        },'json');

