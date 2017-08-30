$(function(){
  $(".type_language").find(".btn-codetype:first-child")
    .removeClass("btn-gray").addClass("btn-blue");//默认加载第一个

  gradeLevel = 'all';

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
    
    oc_gradeChart.showLoading({text:'正在加载中...'});
    oc_riskChart.showLoading({text:'正在加载中...'});

    option_gradeChart = {
      title: {
          show:true,
          text: '程度分布视图',
          subtext:'据程度可点击展示风险分布',
          x:'center'
      },
      tooltip: {
        trigger: 'item',
        formatter: "{a} <br/>{b}: {c} ({d}%)"
      },
      toolbox: {
        x: 'left',
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
        orient: 'vertical',
        right:'3%',
        align: 'left',
        y:'bottom',
        data:[]
      },
      series: [{
          name: '问题分布',
          type: 'pie',
          radius: ['30%', '70%'],
          avoidLabelOverlap: false,
          hoverAnimation: false,
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

    option_riskChart = {
      title: {
          show:true,
          text: '风险分布视图',
          x:'center'
      },
      tooltip: {
        trigger: 'item',
        formatter: "{a} <br/>{b}: {c} ({d}%)"
      },
      toolbox: {
        x: 'left',
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
        orient: 'vertical',
        right:'3%',
        align: 'left',
        y:'bottom',
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
      console.log(params);
      
      oc_riskChart.showLoading({text:'重新加载中...'});
      //根据高中低程度来分别展示右边风险分布图
      $.ajax({
        type:"GET",
        sync:true,
        url:"owerview.php?codetype="+$(".btn-blue").html()+'&level='+gradeLevel,
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
      $('#table_owner').bootstrapTable('filterBy', null);
      $('#table_owner').bootstrapTable('refresh', {url: 'owerdata.php?codetype='+$(".btn-blue").html()+'&level='+gradeLevel});
    });

    //具体点击展示表格
    oc_riskChart.on("click",function(params){
      var tt = params.name;
      $('#table_owner').bootstrapTable('filterBy', {
          warning_type: [tt]
      });
    });

    //填充表格
    $('#table_owner').bootstrapTable({
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
           align: 'center',
        }, {
           field: 'warning_type',
           title: '漏洞名称',
           align: 'center',
        }, {
           field: 'file',
           title: '漏洞位置',
           align: 'center'
        }, {
           field: 'code',
           title: '漏洞片段',
           align: 'left'
        }, {
           field: 'message',
           title: '描述及修复建议',
           align: 'left'
        }, {
           field: 'link',
           title: '参考',
           align: 'left'
        }],
        pageSize: 10,
        pageList: [10, 25, 50, 100],
        pagination: true
    });
  }

  
  drawChart($(".btn-blue").html(),'all');//加载默认选中的

  $.each($(".btn-codetype"),function(){
    $(this).on("click",function(){
        $("#table_owner").bootstrapTable('destroy');
        $("#toolbar select").get(0).selectedIndex = 0;

        gradeLevel = 'all';
        if($(this).hasClass("btn-gray")){
            $(this).removeClass("btn-gray").addClass("btn-blue").siblings().removeClass("btn-blue").addClass("btn-gray");//换样式
        }
        drawChart($(".btn-blue").html(),'all');//加载选中的
        $('#table_owner').bootstrapTable('refresh', {url: 'owerdata.php?codetype='+$(".btn-blue").html()+'&level=all'});
    });
  });

  $('#toolbar').find('select').change(function () {
      $("#table_owner").bootstrapTable('destroy').bootstrapTable({
            method: 'get',
            url: "owerdata.php?codetype="+$(".btn-blue").html()+"&level="+gradeLevel,
            exportDataType: $(this).val(),
            sidePagination: "client",
            cache: false, 
            showExport:true,

            columns: [ {
                field: 'state',
                checkbox: true
                
            },{
                title: "操作",
                editable: {
                    type: 'select',
                    title: '人为操作',
                    mode: "popup",  
                    source:[{value:"1",text:"未确定"},{value:"2",text:"已确定"},{value:"3",text:"忽略"}]
                }
            },{
                title: '序号',
                formatter: function (value, row, index) {  
                    return index+1;  
                },
                align: 'center'
            }, {
                field: 'severity',
                title: '风险程度',
                align: 'center'
            },{
                field: 'warning_type',
                title: '漏洞名称',
                align: 'center',
            }, {
                field: 'file',
                title: '漏洞位置',
                align: 'center'
            },{
                field:'code',
                title:'漏洞片段',
                align:'left'
            },{
                field:'message',
                title:'描述及修复建议',
                align:'left'
            },{
                field:'link',
                title:'参考',
                align:'left'
            }],
            pageSize: 10,  
            pageList: [10, 25, 50, 100],
            pagination:true
      });
    });
});