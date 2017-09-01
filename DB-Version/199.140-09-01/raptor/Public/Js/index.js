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

// 设置第一屏大小
	banHeight()
	$(window).resize(function(){
	    banHeight()
	})
	function banHeight(){
	    var screenHeight = $(window).height();
	    $(".banner").css("height",screenHeight);
	}
// 时间跳动

    function ran(min,max){
        return Math.floor(Math.random() * (max - min))+ min;
    } 

    var myDate = new Date();
    var nf = myDate.getFullYear();        //获取当前年份(2位)
    var yf = myDate.getMonth();       //获取当前月份(0-11,0代表1月)
    var xq = myDate.getDate();  
    var xs = myDate.getHours(); 
    // var sj = nf +"."+ yf + "." + xq;

   
   //算法不对
    var intGe = Math.floor((nf + yf + xq)*0.47)+ xs;
    var intLin = Math.floor(intGe*8289);
    var intDef = Math.floor(intGe*54);

    $("#numCon0").html(intGe);
    $("#numCon1").html(intLin);
    $("#numCon2").html(intDef);
   
    


    autoplay()
    function autoplay(){
        var options = {
            useEasing : true,
            useGrouping : true,
            separator : ',',
            decimal : '.'
        };
        var demo0 = new CountUp("numCon0", 0, intGe , 0, 2, options);
        demo0.start();
        var demo1 = new CountUp("numCon1", 0, intLin, 0, 2, options);
        demo1.start();
        var demo2 = new CountUp("numCon2", 0, intDef, 0, 2, options);
        demo2.start();
    }

    setInterval(function(){
        autoplay();
    },180000)
