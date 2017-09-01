<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <title>匠迪云</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="/Public/Images/favicon.ico"/>
    <link rel="stylesheet" href="/Public/Css/bootstrap.css">
    <link rel="stylesheet" href="/Public/Css/common.css">
    <link rel="stylesheet" href="/Public/Css/header.css">

    <script src="/Public/Js/jquery-3.1.1.min.js"></script>
    <script src="/Public/Js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-default ">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img src="/Public/Images/logo1.png" alt="" title="匠迪云安全"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" >

            <ul class="nav navbar-nav ">
                <li><a href="<?php echo U('Index/index');?>" class="navA index">首页</a></li>
                <li><a href="<?php echo U('Scan/scan');?>" class="navA scan">代码扫描</a></li>
                <li><a href="<?php echo U('CodeAnalysis/issues');?>" class="navA issues" >代码分析</a></li>
                <li><a href="<?php echo U('History/history');?>" class="navA history">工程管理</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown user_down"><a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true">您好，<?php echo ($_SESSION['user_name']); ?><span class="caret"></span></a>
                    <ul class="dropdown-menu user_menu">
                        <li class="usemes-li"><a href="<?php echo U('Userinfo/userinfo');?>" class="user_data"><span class="use usemes" ></span> 用户信息</a></li>
                        <li class="usepas-li"><a href="<?php echo U('Changepas/changepas');?>" class="user_data"><span class="use usepas"></span> 修改密码</a></li>
                        <li><a href="mailto:hr@Obsec.net?Subject=代码扫描平台用户建议" class="user_data"><span class="use "></span> 用户建议</a></li>
                    </ul>
                </li>
                <li><a href="/Home/Login/login" class="navA"><span class="glyphicon glyphicon-log-out"></span>注销</a></li>
            </ul>
        </div>
    </div>
</nav>
<script>
    $(function(){
        var file = "<?php echo $_SERVER['PHP_SELF']?>";
        if(file == '/scan.php'){
            $('.scan').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/issues.php' || file == '/issues_bro.php' || file == '/online.php'){
            $('.issues').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/history.php' || file == '/task.php'){
            $('.history').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/contact.php'){
            $('.contact').css('border-bottom','4px solid #65b6fc');
        }else if(file == '/userinfo.php'){
            $('.usemes').css('background-position','0 -30px');
            $('.usemes-li a').css('color','#145876');
        }else if(file == '/changepas.php'){
            $('.usepas').css('background-position','-40px -30px');
            $('.usepas-li a').css('color','#145876');
        }else if(file == '/index.php'){
			//$('nav').addClass('navbar-fixed-top');
			$('.index').css('border-bottom','4px solid #65b6fc');
		}
    });

    $('.user_down').click(function(){
        if( $('.user_menu').css('display') == 'none'){
            $('.user_menu').css('display','block');
        }else{
            $('.user_menu').css('display','none');
        }

    });
</script>
<link rel="stylesheet" href="/Public/Css/caf.css">
<link rel="stylesheet" href="/Public/Css/jeuic.css">
<link rel="stylesheet" href="/Public/Css/table.css">

<div class="container">
    <div class="row rowpiece">


        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead" >
            <a href="<?php echo U('CodeAnalysis/issues');?>" >总览视图</a>
        </div>
        <div class="col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead">
            <a href="<?php echo U('CodeAnalysis/issues_bro');?>">分览视图</a>
        </div>
        <div class = "col-xs-4 col-sm-2 col-md-2 col-lg-1 subhead">
            <a href="<?php echo U('CodeAnalysis/online');?>" class="tab-titles" style=" background-color:rgba(34,41,48,.6);" >在线审计</a></div>


    </div>
    <div class="row rowpiece type_language">
        <div  class="row tab-content" style="margin-top: 1rem">


            <div class="tab-pane fade in active" id="first">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 rl lee">
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

                
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 rl ree">
                    <div class="message-right">
                        <!--上面结构----标题-->
                        <ul  class="nav nav-tabs cre-ul" style="height:28px;line-height: 28px">
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

<script src="/Public/Js/bootstrap-table.js"></script>
<script src="/Public/Js/jeTreeC.js"></script>
<!-- <script src="./dist/js/echarts.js"></script>
<script src="./dist/js/dark.js"></script> -->
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
                tid: "M0<?php echo $upid; echo $uvid; ?>",
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
    var data_type = "<?php echo !empty($data_type) ? $data_type : 5;?>";
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
            var url = encodeURI('issues_audit.php?fu_key='+fu_key+'&key_id='+key_id+'&data_type=<?php echo $data_type ?>');
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
giveHeight()
function giveHeight(){
    var winHeight = $(window).height();
    console.log("左上"+$(".rl").eq(0).offset().top + "右上" + $(".rl").eq(1).offset().top);
//       树形赋高
    var treeConTop = $(".tree-con").offset().top;

    var treeConHeight = winHeight - treeConTop -6;

    if(treeConHeight >= 500){
        treeConHeight = 500
    }else{
        treeConHeight = treeConHeight;
    }
    $(".tree-con").css({"height":treeConHeight});
//右侧赋高
    var iframeConTop= $(".iframe-con").offset().top;
    var iframeConHeight = winHeight - iframeConTop - 7;

    if(iframeConHeight >= 552){
        iframeConHeight = 552
    }else{
        iframeConHeight = iframeConHeight;
    }
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