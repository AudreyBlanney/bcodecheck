<?php
    include 'session.php';
    $id = $_GET['id'] ? $_GET['id'] : 1;
    $_SESSION['current_scan_report'] = $_SESSION['report_id'][$id];
    $data = json_decode(file_get_contents($_SESSION['report_id'][$id]), true);
    $chart_codetypes_metrics = array();//代码分类
    $chart_severity_metrics = array();//程度分类
    $chart_vulntype_metrics = array();//风险分类
    $se_array = array();
    $vu_array = array();
    $fe_array = array();
    if($data){
        foreach ($data['warnings'] as $key => $value) {
            if (array_key_exists('file', $value)) {
                array_push($fe_array, strtolower(trim(substr(strrchr($value['file'], '.'), 1))));
                $chart_codetypes_metrics = array_count_values($fe_array);
            }
        }
        for($i=0; $i < count($data['warnings']); $i++) {
            @$rule_id = !empty($data['warnings'][$i]['warning_code']) ? $data['warnings'][$i]['warning_code'] : '-';
            array_push($se_array,$data['warnings'][$i]['severity']);
            $chart_severity_metrics = array_count_values($se_array);

            array_push($vu_array,$data['warnings'][$i]['warning_type']);
            $chart_vulntype_metrics = array_count_values($vu_array);
        }
    }
$branch_view = json_encode($chart_codetypes_metrics); //获取分览视图json数据
?>
<!DOCTYPE HTML>
<!-- 代码种类分布 -->
<div id = 'codes_type_w'><div id="codes_type" style="display: none"></div></div>
<!-- 程度分布 -->
<div id = 'grades_type_w'><div id="grades_type" style="display: none"></div></div>
<!-- 风险分布 -->
<div id = 'risks_type_w'><div id="risks_type" style="display: none"></div></div>

<!--分览视图-->
<div id = "oc_grade" style="width:751px;height:300px;display: none"></div>
<div id = "oc_risk" style="width:751px;height:300px;display: none"></div>
<!--end-->
<script src="./dist/js/jquery-3.1.1.min.js"></script>
<script src="./dist/js/bootstrap.min.js"></script>
<script src="./dist/js/bootstrap-table.js"></script>
<script src="assets/js/echarts.min.js"></script>
<script src="assets/js/dark.js"></script>
<!--总览视图操作-->
<script>
    //设置容器高宽
    var worldcodes_type_w = $('#codes_type_w');
    function resizeWorldcodes_type_w() {
        worldcodes_type_w.width(window.innerWidth+'px');
        worldcodes_type_w.height(window.innerHeight-300+'px');
    };
    resizeWorldcodes_type_w();

    var worldgrades_type_w = $('#grades_type_w');
    function resizeWorldgrades_type_w() {
        worldgrades_type_w.width(window.innerWidth+'px');
        worldgrades_type_w.height(window.innerHeight-300+'px');
    };
    resizeWorldgrades_type_w();

    var worldrisks_type_w = $('#risks_type_w');
    function resizeWorldrisks_type_w() {
        worldrisks_type_w.width(window.innerWidth+'px');
        worldrisks_type_w.height(window.innerHeight-300+'px');
    };
    resizeWorldrisks_type_w();

    function resizeWorldMapContainer(worldMapContainer) {
        worldMapContainer.width('751');
        worldMapContainer.height('305');
    };
    codes_type();//代码种类l
    grades_type();//程度分类
    risks_type();//风险种类分布
    //代码种类
    function codes_type(){
        var worldMapContainer = $('#codes_type');
        resizeWorldMapContainer(worldMapContainer);
        var codeTypeChart = echarts.init(document.getElementById('codes_type'),'dark');
        codeTypeChart.setOption({
            animation : false,
            backgroundColor:'#2a323d',
            calculable : true,
            series : [
                {
                    type:'pie',
                    radius: ['10%', '90%'],
                    center : 'center',
                    roseType : 'area',
                    data:[<?php
                            $codetypes_metrics = '';
                            foreach ($chart_codetypes_metrics as $key => $value) {
                              $codetypes_metrics .= "{name:'" . $key ."',value:".$value."},";
                            }
                            echo $codetypes_metrics;
                      ?>],
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
        });
        var picInfo = codeTypeChart.getDataURL();
        if(picInfo) {
            $.ajax({
                type: "post",
                data: {baseimg:picInfo},
                url: 'download_image.php?action=codes_type',
                async: false,
                success: function (data) {

                } ,
                error: function (err) {
                    alert('图片保存失败');
                }
            });
        }
        $('#codes_type_w').hide();
    }

    //程度分类
    function grades_type(){
        var worldMapContainer = $('#grades_type');
        resizeWorldMapContainer(worldMapContainer);
        var gradeTypeChart = echarts.init(document.getElementById('grades_type'),'dark');
        gradeTypeChart.setOption({
            animation : false,
            backgroundColor:'#2a323d',
            calculable : true,
            series : [
                {
                    name:'面积模式',
                    type:'pie',
                    radius : [25, 150],
                    center : 'center',
                    roseType : 'area',
                    data:[<?php
                            $sev_metrics = "";
                            foreach ($chart_severity_metrics as $key => $value) {
                              if($key == '高'){
                                $sev_metrics .= "{name:'" . $key ."',value:".$value.",itemStyle:{normal:{color:'#dd6b66'}}},";
                              }else if($key == '中'){
                                $sev_metrics .= "{name:'" . $key ."',value:".$value.",itemStyle:{normal:{color:'#fece56'}}},";
                              }else{
                                $sev_metrics .= "{name:'" . $key ."',value:".$value.",itemStyle:{normal:{color:'#93d665'}}},";
                              }
                            }
                            echo $sev_metrics;
                      ?>],
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
        });
        var picInfo = gradeTypeChart.getDataURL();
        if(picInfo) {
            $.ajax({
                type: "post",
                data: {baseimg:picInfo},
                url: 'download_image.php?action=grades_type',
                async: false,
                success: function (data) {

                } ,
                error: function (err) {
                    alert('图片保存失败');
                }
            });
        }
        $('#grades_type_w').hide();
    }

    //风险种类分布
    function risks_type(){
        var worldMapContainer = $('#risks_type');
        resizeWorldMapContainer(worldMapContainer);
        var risksTypeChart = echarts.init(document.getElementById('risks_type'),'dark');
        risksTypeChart.setOption({
            animation : false,
            backgroundColor:'#2a323d',
            calculable : true,
            series : [
                {
                    name:'面积模式',
                    type:'pie',
                    radius: ['10%', '73%'],
                    center : ['49%', '50%'],
                    roseType : 'area',
                    avoidLabelOverlap: true,
                    data:[<?php
                           $vuln_metrics = "";
                           foreach ($chart_vulntype_metrics as $key => $value) {
                              $vuln_metrics .= "{name:'" . $key ."',value:".$value."},";
                            }
                          echo $vuln_metrics;
                        ?>],
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
        });
        var picInfo = risksTypeChart.getDataURL();
        if(picInfo) {
            $.ajax({
                type: "post",
                data: {baseimg:picInfo},
                url: 'download_image.php?action=risks_type',
                async: false,
                success: function (data) {

                } ,
                error: function (err) {
                    alert('图片保存失败');
                }
            });
        }
        $('#risks_type_w').hide();
    }
</script>
<!--end-->
<!------分览视图操作---->
<script>
    var oc_gradepicInfo = '';
    var drawChart = function(ctype,level){
        oc_gradeChart = echarts.init(document.getElementById('oc_grade'),'dark');
        oc_riskChart = echarts.init(document.getElementById('oc_risk'),'dark');

        option_gradeChart = {
            backgroundColor:'#2a323d',
            animation : false,
            calculable : true,
            series: [{
                name:'面积模式',
                type:'pie',
                radius : [20, 148],
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
            animation : false,
            calculable : true,
            series: [{
                name: '问题分布',
                type: 'pie',
                radius: ['10%', '73%'],
                center : ['49%', '50%'],
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
            async:false,
            url:"owerview.php?codetype="+ctype,
            jsonType:'json',
            success:function(result,status){
                if(status == 'success'){
                    var json_owner = eval('('+ result +')'); //由JSON字符串转换为JSON对象
                    var gradeObj = json_owner.owner_grade;
                    var riskObj = json_owner.owner_risk;
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
                    option_gradeChart.series[0].data = gradeArrData;
                    oc_gradeChart.hideLoading();
                    document.getElementById("oc_grade").removeAttribute("_echarts_instance_");
                    oc_gradeChart.setOption(option_gradeChart);
                    oc_gradepicInfo = oc_gradeChart.getDataURL();
                    for (var key in riskObj){
                        riskArr.push(key);
                        var riskDataObj = {};
                        riskDataObj.name = key;
                        riskDataObj.value = riskObj[key];
                        riskArrData.push(riskDataObj);
                    }
                    option_riskChart.series[0].data = riskArrData;
                    oc_riskChart.hideLoading();
                    oc_riskChart.setOption(option_riskChart);
                    oc_riskpicInfo = oc_riskChart.getDataURL();
                }else{
                    console.log("请求错误!");
                }
            },error:function(){
                console.log("请求错误!");
            }
        },'json');
        //下载分览视图图片
        if(oc_gradepicInfo) {
            $.ajax({
                type: "post",
                data: {baseimg:oc_gradepicInfo},
                url: 'download_image.php?file_class_type=cd_'+ctype,
                async: false,
                success: function (data) {

                } ,
                error: function (err) {
                    alert('图片保存失败');
                }
            });
        }
        if(oc_riskpicInfo) {
            $.ajax({
                type: "post",
                data: {baseimg:oc_riskpicInfo},
                url: 'download_image.php?file_class_type=fx_'+ctype,
                async: false,
                success: function (data) {

                } ,
                error: function (err) {
                    alert('图片保存失败');
                }
            });
        }
    }
    $('#oc_grade').hide();
    $('#oc_risk').hide();
    var branch_view = <?php echo $branch_view?>;
   $.each(branch_view,function(branch_key,branch_value){
        drawChart(branch_key,'all');
   });
</script>
<!--end-->
