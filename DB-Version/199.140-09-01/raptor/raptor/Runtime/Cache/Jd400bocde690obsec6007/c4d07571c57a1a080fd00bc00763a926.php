<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <title>匠迪云</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link rel="shortcut icon" href="/Public/admin/Images/favicon.ico"/>
    <link rel="stylesheet" href="/Public/admin/Css/bootstrap.css">
    <link rel="stylesheet" href="/Public/admin/Css/iframe-stati-analy.css">
    <link rel="stylesheet" href="/Public/admin/Css/table.css">

</head>
<body>

<div class="ecmap" id = "option"></div>

<!--滚动条-->
<div class="scroll-container">
    <div class="scroll">
        <table class="table table-bordered" id = "table">
        </table>
    </div>
</div>




<script src="/Public/admin/Js/jquery-3.1.1.min.js"></script>
<script src="/Public/admin/Js/bootstrap.min.js"></script>
<script src="/Public/admin/Js/bootstrap-table.js"></script>
<script src="/Public/admin/Js/echarts.js"></script>
<script src="/Public/admin/Js/dark.js"></script>
<script src="/Public/admin/Js/staticang.js"></script>
<script>
    $(window).resize(function(){
        mainmap0.resize();
    })
    mainmap0 = echarts.init(document.getElementById('option'),'dark');
    option = {
        backgroundColor:'#2a323d',
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['扫描大小','扫描次数']
        },
        xAxis: [
            {
                type: 'category',
                data : [<?php echo ($date_str); ?>],
                axisPointer: {
                    type: 'shadow'
                }
            }
        ],
        yAxis: [
            {
                type: 'value',
                name: '大小',
                axisLabel: {
                    formatter: '{value} M'
                }
            },
            {
                type: 'value',
                name: '次数',
                axisLabel: {
                    formatter: '{value} 次'
                }
            }
        ],
        series: [
            {
                name:'扫描大小',
                type:'bar',
                data:[<?php echo ($scan_size); ?>]
            },
            {
                name:'扫描次数',
                type:'line',
                yAxisIndex: 1,
                data:[<?php echo ($scan_sum); ?>]
            }
        ]
    };

    mainmap0.setOption(option);
</script>
</body>
</html>