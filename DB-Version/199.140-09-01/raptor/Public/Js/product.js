// 百度地图API功能
var map = new BMap.Map("footer_add");
var point = new BMap.Point(116.355512,40.072434);
map.centerAndZoom(point, 18);
function addMarker(point,index){
    var myIcon = new BMap.Icon("/Public/Images/markes.png",new BMap.Size(23,30),{
        offset:new BMap.Size(10,25)
    });
    var marker = new BMap.Marker(point,{icon:myIcon});  // 创建标注
    map.addOverlay(marker);               // 将标注添加到地图中
    marker.enableDragging();//可拖拽
    marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
}
addMarker(point,1)
map.enableScrollWheelZoom();//可缩放
//上面5条横短线 下面5条短线
for(var a=0;a<$(".hdT").length;a++){
    $(".hdT").eq(a).css({"left":a*22.9350+"%"});
    $(".hdB").eq(a).css({"left":a*22.9350+"%"})
}
//上面4张图片 下面4张图片
for(var b=0;b<$(".tpT").length;b++){
    $(".tpT").eq(b).css({"left":(b*22.8471+7.9086)+"%","background-position":(b*32.2)+"% 0%"});
    $(".tpB").eq(b).css({"left":(b*22.8471+7.9086)+"%","background-position":(b*32.2)+"% 82%"})
}
//中间3张图
for(var c=0;c<$(".tpM").length;c++){
    $(".tpM").eq(c).css({"right":(c*22.4956 + 20.2109)+"%","background-position":(64.0538-c*32.2)+"% 40.1%"})
}
//hover效果
$(".tpT").each(function(i){
    $(this).hover(function(){
        $(this).css("background-position-y","20.5%");
    },function(){
        $(this).css("background-position-y","0px")
    })
})
$(".tpM").each(function(i){
    $(this).hover(function(){
        $(this).css("background-position-y","60.8%");
    },function(){
        $(this).css("background-position-y","40.1%")
    })
})
$(".tpB").each(function(i){
    $(this).hover(function(){
        $(this).css("background-position-y","102.6%");
    },function(){
        $(this).css("background-position-y","82%")
    })
})


//调用函数滚动条到达一定位置加载动画
function dh(){
    //两条竖短线
    $(".sd0").animate({
        height:'14.8594%'
    },300)

    $(".sd1").delay(8400)
    $(".sd1").animate({
        height:'14.8594%'
    },300)

    //两条竖长线
    $(".sc0").delay(3000)
    $(".sc0").animate({
        height:'34.5382%'
    },300)

    $(".sc1").delay(5400)
    $(".sc1").animate({
        height:'34.5382%'
    },300)

    //两条横长线
    $(".hc0").delay(3300)
    $(".hc0").animate({
        width:'19%'
    },300)

    $(".hc1").delay(5100)
    $(".hc1").animate({
        width:'19%'
    },300)

    //上5条横短线
    for(var d=0;d<$(".hdT").length;d++){
        $(".hdT").eq(d).delay(d*600 + 300);
        $(".hdT").eq(d).animate({
            width: '7.7329%'
        },300)



        $(".hdB").eq(d).delay(d*600 + 5700);
        $(".hdB").eq(d).animate({
            width: '7.7329%'
        },300)
    }
    //中间2条横短线
    $(".hdM").eq(0).delay(3900)
    $(".hdM").eq(0).animate({
        width:'7.7329%'
    },300)

    $(".hdM").eq(1).delay(4500)
    $(".hdM").eq(1).animate({
        width:'7.7329%'
    },300)
    //上下4个图
    for(var e=0;e<$(".tpT").length;e++){
        $(".tpT").eq(e).delay(e*600+600);
        $(".tpT").eq(e).animate({
            width:'15.1142%'
        },300)

        $(".tpB").eq(e).delay(e*600+6000);
        $(".tpB").eq(e).animate({
            width:'15.1142%'
        },300)
    }
    //中间3图
    for(var f=0;f<$(".tpM").length;f++){
        $(".tpM").eq(f).delay(f*600+3600);
        $(".tpM").eq(f).animate({
            width:'15.1142%'
        },300)
    }
}

var mapConTop = $(".part6").offset().top;
console.log(mapConTop)

$(window).scroll(function(){
    if($(window).scrollTop() >= mapConTop){
        dh();
    }

})