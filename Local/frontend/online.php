<?php
include 'session.php';
include 'header.php';
if (!empty($_SESSION['current_scan_report'])) {
    if (file_exists($_SESSION['current_scan_report'])) {
        $data = json_decode(file_get_contents($_SESSION['current_scan_report']), true);
    } else {
        $_SESSION['current_scan_report'] = '';
    }
} else {
    error_log("[ERROR] session: current_scan_report is null.");
}

$chart_codetypes_metrics = array();//代码分类
$chart_vulntype_metrics = array(); //风险分类
$chart_severity_metrics = Array();//程度分类
/*风险代码视图种类*/
if(!empty($data)) {
    $fe_array = array();
    $vu_array = array();
    $se_array = array();
    foreach ($data['warnings'] as $key => $value) {
        if (array_key_exists('file', $value)) {
            array_push($fe_array, strtolower(trim(substr(strrchr($value['file'], '.'), 1))));
            $chart_codetypes_metrics = array_count_values($fe_array);
        }

        array_push($se_array,$value['severity']);
        $chart_severity_metrics = array_count_values($se_array);

        array_push($vu_array,$value['warning_type']);
        $chart_vulntype_metrics = array_count_values($vu_array);
    }
}
//数据整理
$up_data = array();
foreach($chart_vulntype_metrics as $k => $v){
    foreach($data['warnings'] as $data_key => &$data_value){
        if($k == $data_value['warning_type']){
            $up_data[$k]['content'][] = $data_value;
        }
    }
}
//筛选指定数据
$data_type = !empty($_GET['data_type']) ? $_GET['data_type'] : 5; //1忽略 2高 3中 4低 5所有
switch($data_type){
    case 1;
        $severity_type = '忽略';
        break;
    case 2:
        $severity_type = '高';
        break;
    case 3;
        $severity_type = '中';
        break;
    case 4;
        $severity_type = '低';
        break;
    case 5;
        $severity_type = 5;
        break;
}
//审计总数，排序整理
unset($up_data[0]);
$sj_array = array();
foreach($up_data as $up_key => &$up_value){
    $up_value['sj_num'] = 0;
    $sj_array = array();
    foreach($up_value['content'] as $uv_key => &$uv_value){
        if($severity_type != 5){
            if($severity_type != $uv_value['severity']){
                unset($up_value['content'][$uv_key]);
            }else{
                array_push($sj_array,!empty($uv_value['type']) ? $uv_value['type'] : 0);
                $up_value['sj_num'] += !empty($uv_value['type']) ? $uv_value['type'] : 0; //获取审计总数
            }
        }else{
            array_push($sj_array,!empty($uv_value['type']) ? $uv_value['type'] : 0);
            $up_value['sj_num'] += !empty($uv_value['type']) ? $uv_value['type'] : 0; //获取审计总数
        }
    }
    if(empty($up_value['content'])){
        unset($up_data[$up_key]);
    }
    @array_multisort($sj_array, SORT_ASC, $up_value['content']); //按审计排序
}

//获取报警数
$security = explode('(',$data['scan_info']['security_warnings']);
$chart_severity_metrics['zg'] = $security[0]; //获取报警总数
?>
<link rel="stylesheet" href="./dist/css/caf.css">
<link rel="stylesheet" href="./dist/css/jeuic.css">
<link rel="stylesheet" href="./dist/css/table.css">

<div class="container">
    <div class="row rowpiece">
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" ><a href="issues.php" >总览视图</a></div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead"><a href="issues_bro.php"  >分览视图</a></div>
        <div class = "col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead">
            <a href="onlin.php" class="tab-titles" style=" background-color:rgba(34,41,48,.6); box-shadow: 2px 2px 3px #000;" >在线审计</a></div>
    </div>


    <div class="row rowpiece type_language">
       
        <div  class="row tab-content" style="margin-top: 1rem">
            <div class="tab-pane fade in active" id="first">

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 rl">
                    <div class="message-left">
                        <div class="level-con">
                            <div class="part4" type="1">
                                <p>忽略</p>
                                <p style="color: #c31f26"><?php echo !empty($chart_severity_metrics['忽略']) ? $chart_severity_metrics['忽略'] : 0;?></p>
                            </div>
                            <div class="part4" type="2">
                                <p>高</p>
				 <p style="color: #c31f26"><?php echo !empty($chart_severity_metrics['高']) ? $chart_severity_metrics['高'] : 0;?></p>
                            </div>
                            <div class="part4" type="3">
                                <p>中</p>
				<p style="color: #dac82c"><?php echo !empty($chart_severity_metrics['中']) ? $chart_severity_metrics['中'] : 0; ?></p>
                            </div>
                            <div class="part4" type="4">
                                <p>低</p>
                                <p style="color: #199d2b"><?php echo !empty($chart_severity_metrics['低']) ? $chart_severity_metrics['低']:0;?></p>
                            </div>
                            <div class="part4" type="5" style="background: #1f2938">
                                <p>所有</p>
				 <p style="color: #0978b7"><?php echo !empty($chart_severity_metrics['zg']) ? $chart_severity_metrics['zg'] : 0;?></p>
                            </div>

                        </div>
                        <div class="tree-con">
                            <!--滚动条-->
                            <div class="scroll-container">
                                <div class="scroll">

                                    <!--！！！！！！！！！！！！！树形图！！！！！！！！！！！！！！-->
                                    <div class="je-tree" id="trees" >
                                    </div>
                                    <!--！！！！！！！！！！！！！树形图！！！！！！！！！！！！！！-->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 rl">
                    <div class="message-right">
                        <!--上面结构标题-->
                        <ul  class="nav nav-tabs cre-ul" style="border-bottom:1px solid #0978b7;height:28px;line-height: 28px">
                            <li>
                                代码详情
                            </li>
                            <!--推入标题-->
                        </ul>

                        <div class="iframe-con">
                            <iframe src="" frameborder="0" class="iframe"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./dist/js/bootstrap-table.js"></script>
<script src="./dist/js/jeTreeC.js"></script>


<script type="text/javascript" src="./dist/js/bootstrap-table-export.js"></script>
 <script type="text/javascript" src="./dist/js/tableExport.js"></script>
<script src="./dist/js/ga.js"></script>
<!--<script src="./dist/js/issues_bro.js"></script>-->

<script>

</script>
<script>
    //标题点击样式
    $('.subhead').click(function(){
        $(".subheadnext").removeClass("active");
    });
var jsonlist = [
    <?php $upid = 0;$uvid = 0;foreach($up_data as $tree_key => $tree_value){ $uvid = 0?>
    {
        tid: "M0<?php echo $upid;?>",
        name: "<?php echo $tree_key;echo $tree_value['sj_num'] ? $tree_value['sj_num'] : 0; echo '/', count($tree_value['content']).'';?>",
        url: "",
        childlist: [
            <?php foreach($tree_value['content'] as $upv_key => $upv_value){?>
            {
                tid: "M0<?php echo $upid; ?><?php echo $uvid; ?>",
                name: "<?php echo $upv_value['file'];echo '('.$upv_value['line'].')'; ?><span><?php echo !empty($upv_value['type']) ? $upv_value['type'] : 0;?></span>",
                fu_key:"<?php echo $tree_key;?>", //获取父级key
                key_id:"<?php echo $upv_key?>", //获取本级的可以
                suggestmessage:"修复建议0",
                refermessage:"参考信息0",
                auditmessage:"缺陷审计0",
                url: "",
                childlist: "",
                title_name:"<?php echo $upv_value['file'];echo '('.$upv_value['line'].')'; ?>"
            },
            <?php $uvid+=1;}?>
        ]
    },
    <?php $upid+=1;}?>
];

//警告标题
$('.part4').click(function(){
    var type = $(this).attr('type');//获取状态
    $(this).css('background','#1f2938');
    $(this).siblings('.part4').css('background','#2a323d');
    location.href = 'online.php?data_type='+type;
});
$(function(){
    var data_type = "<?php echo !empty($_GET['data_type']) ? $_GET['data_type'] : 5;?>";
    $("div[type='"+data_type+"']").css('background','#1f2938');
    $("div[type='"+data_type+"']").siblings('.part4').css('background','#2a323d');
})

var arr = [];
$("#trees").jeTree({
    datas:jsonlist,
    itemfun:function (item) {
        if(item.childlist.length == 0){
            $(".folderleaf").css({"background":"url(images/checked.png) no-repeat","background-position":"0 5px"})
            $("li[treeid="+item.tid+"] .folderleaf").css({"background":"url(images/checked.png) no-repeat","background-position":"0 -36px"})
            var fu_key = item.fu_key;
            var key_id = item.key_id;
            var url = 'issues_audit.php?fu_key='+fu_key+'&key_id='+key_id+'&data_type=<?php echo $data_type ?>';
            $('.iframe').attr('src',url);
            // 点击5个问题出现
            $(".opera").css("display","none");
            $("li[treeid="+item.tid+"] .opera").css("display","none");
//          判断标题的重复性
            arr = [];
            $(".cre-ul .cre-li").each(function (){
                arr.push($(this).attr("ids"));
            })
            var res = $.inArray(item.tid,arr);
            if(res == -1){
                creTitle(item.tid,item.title_name,item.fu_key,item.key_id);
                $(".cre-li[ids="+item.tid+"]").siblings().removeClass('active');
            }else{
                $(".cre-li[ids="+item.tid+"]").siblings().removeClass('active');
                $(".cre-li[ids="+item.tid+"]").addClass('active');
            }
        }
    }
});
//    创建标题
function creTitle(id,name,fu_key,key_id){
    var creLi = "<li class='cre-li active' active='' fu_key='"+fu_key+"' key_id='"+key_id+"' ids='"+id+"'><a href='#tabmessage' data-toggle='tab' class='cre-title' index = "+id+" title = "+name +"> "+name+"  </a><span class='cre-close'></span></li>";
    $(".cre-ul").append(creLi);
    $(".cre-title[index="+id+"]").parent().prev().attr('active','');
    $(".cre-title[index="+id+"]").parent().attr('active','active');

}
//    点击标题添加active
$(".cre-ul").on("click",".cre-title",function(){
    var fu_key = $(this).parent().attr('fu_key');
    var key_id = $(this).parent().attr('key_id');
    $("li[active='active']").attr('active','');
    $(this).parent().attr('active','active');
    var url = 'issues_audit.php?fu_key='+fu_key+'&key_id='+key_id;
    $('.iframe').attr('src',url);
})
//   点击删除标题
$(".cre-ul").on("click",".cre-close",function(){
    var active_num = $(this).parent().index(); //获取选中元素的位置
    var li_leng = $('.cre-ul .cre-li').length; //获取li数量
    var active =  $(this).parent().attr('active');
    if(active_num == 1 && active == 'active'){
        //如果第一个被选中，删除，赋值后一个
        var fu_key = $(this).parent().next().attr('fu_key');
        var key_id = $(this).parent().next().attr('key_id');
        $(this).parent().next().addClass('active');
        $(this).parent().next().attr('active','active');
        $(this).parent().remove();
    }else if(active_num != 1 && active == 'active'){
            //如果选中的被删除，样式赋值到前一个
            var fu_key = $(this).parent().prev().attr('fu_key');
            var key_id = $(this).parent().prev().attr('key_id');
            $(this).parent().prev().addClass('active');
            $(this).parent().prev().attr('active','active');
            $(this).parent().remove();
    }else{
        //删除未选中的
        var fu_key = $("li[active='active']").attr('fu_key');
        var key_id = $("li[active='active']").attr('key_id');
        $(this).parent().remove();
    }
    if(li_leng != 1){
        var url = 'issues_audit.php?fu_key='+fu_key+'&key_id='+key_id+'&data_type=<?php echo $data_type?>';
        $('.iframe').attr('src',url);
    }else{
        $('.iframe').attr('src','');
    }

})
//    删除指定元素
function removeByValue(arr0, val) {
    for(var i=0; i<arr0.length; i++) {
        if(arr0[i] == val) {
            arr0.splice(i, 1);
            break;
        }
    }
}
//树形及右侧父级赋高
giveHeight();
function giveHeight(){
    var winHeight = $(window).height();
//       树形赋高
    var treeConTop = $(".tree-con").offset().top;
    var treeConHeight = winHeight - treeConTop -6;
    $(".tree-con").css("height",treeConHeight);
//右侧赋高
    var iframeConTop= $(".iframe-con").offset().top;
    var iframeConHeight = winHeight - iframeConTop - 8;
    $(".iframe-con").css("height",iframeConHeight);
  
}
    $(function(){
        $('.folderleaf span').each(function(){
            $(this).css('display','none');
            if($(this).html() == 1){
                $(this).parent().css('color','green');
            }
        });
    })
</script>

</body>
</html>
