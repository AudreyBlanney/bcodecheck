<?php
session_start();
if(!empty($_SESSION['user_name'])) {
    include 'header.php';
}else{
	include 'header_not_login.php';
}

?>
<link rel="stylesheet" href="./dist/css/index1.css">


<!--第一屏-->
 <div class="banner">
    <div class="container" style="height: inherit;position: relative;">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12  col-lg-12 bg_title"></div>
        </div>
        <?php if(empty($_SESSION['user_name'])) {?>
			<a href="login_not.php"><button class="free-btn" style="color:#fff">免费检测</button></a>
		<?php }else{?>
			<a href="scan.php"><button class="free-btn" style="color:#fff">免费检测</button></a>
		<?php }?>
        <div class="row bg_num" id="second">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style = "margin-left: 15px">
                <div class="num_part3" style="margin-left: 5%">
                    <span>检测代码项目数量</span> 
                    <span style="color: #ff3652" id="numCon0"></span>  
                </div>
                <div class=" num_part3"> 
                    <span>累计检测代码行数</span>    
                    <span style="color: #edae3e" id="numCon1"></span>  
                </div>
                <div class="num_part3">
                    <span>累计发现缺陷个数</span> 
                    <span style="color: #13ac4c" id="numCon2"></span>      
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container" >
    <div class = "row">
        <h2 class = "title_h2">BCodeCheck是什么？</h2>
        <p class="title_p">BCodeCheck从编码安全的角度出发，自动快速检查源代码中的缺点和敏感信息，分析并定位这些问题将会引发的安全漏洞隐患。
         并提供代码修订措施和建议，帮助企业和个人高效的解决软件产品所带来的先天缺陷。</p>
        <div class = "col-xs-12 col-sm-6 col-md-6 col-lg-6 picCon">
            <div class="pic-leftCon">
               <div class="pic-left"></div>
            </div>
        </div>

        <div class = "col-xs-12 col-sm-12 col-md-6 col-lg-6 bcc">
            <h4 class = "title_h4">什么时候能用到BCodeCheck？</h4>
            <ul  class="bbc-text" >
	            <li> <span></span> &nbsp;&nbsp;如何知道业务系统在开发的过程中使用了哪些高危函数？</li>
	            <li> <span></span> &nbsp;&nbsp;如何知道不同编程语言会给业务系统埋下哪些安全隐患？</li>
	            <li> <span></span> &nbsp;&nbsp;如何从本质上解决掉隐藏在业务系统中的潜在漏洞？</li>
	            <li> <span></span> &nbsp;&nbsp;如何有效的从安全的角度提升代码编写质量？</li>
	            <li> <span></span> &nbsp;&nbsp;如何有效评估软件开发商交付的软件产品安全可靠？</li>
	            <li> <span></span> &nbsp;&nbsp;如何高效完成信息系统新增上线功能代码安全评估？</li>
	            <li> <span></span> &nbsp;&nbsp;......</li>
            </ul>
        </div>
    </div>
    <h2 class = "title_h2">BCodeCheck具备哪些优势？</h2>
    <div class = "row">
        <div class = "col-xs-12 col-sm-6 col-md-3 col-lg-3">
            <div class="advant4 advant0">
                <div class="map4 map0"></div>
                <p>检查源码种类全，对外开放力度大，目前为国内最多，包含Java、c/c++、PHP等13种编程和脚本语言</p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 ">
            <div class="advant4 advant1">
               <div class="map4 map1"></div>
               <p>自动化程度高，整包上传，一键扫描，界面友好，可视化效果好</p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 ">
            <div class="advant4 advant2">
               <div class="map4 map2"></div>
               <p>速度快，定位准、漏报和误报率低、修改意见详细，参考价值大</p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 ">
            <div class="advant4 advant3">
               <div class="map4 map3"></div>
               <p>全面的代码先天缺陷发现能力，支持OWSAP、CWE和CVE等定义的漏洞项检查，优秀的专家团队支撑</p>
            </div>
        </div>
    </div>
    <h2 class = "title_h2">基于客户需求的代码审计解决方案</h2>
    <p class="title_p fa">精准定制，根据客户的不同需求与应用场景，匠迪将提供线上与线下相结合的方式。做到客户有需求，匠迪有方案；以成本和效果为出发点。</p>
    <div class = "row">
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
            <div class="h4-3 ">
               <h3 class="h4-3left">线上<br>自主扫描 ，远程协助 <br> 远程技术答疑支持 <br>人工修正审计报告服</h3>
            </div>
        </div>
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
            <div class = "pic-right3Con">
                <div class="pic-right3"></div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-7 col-md-7  col-lg-7" >
            <div class = "pic-left3Con">
                <div class="pic-left3"></div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-5  col-md-5 col-lg-5 " >
            <div class="h4-3">
              <h3 class=" h4-3right" >线下<br>人员培训，针对辅导<br> 提供便携式与机架式不同型号产品<br>提供专业审计人员到场服务</h3>
            </div>
        </div>
    </div>
</div>
<footer class="footer-con" id="third">
    <div class = "map-con" id = "footer_add"></div>
    <div class="footer-meng">
        <div class="footer-message">
            <div class="footer-top">
                <div class="fmc footer-message-left">
                    <div class="mess-email">邮箱：hr@Obsec.net </div>
                    <div class="mess-tel">电话：400-690-6007 </div>
                </div>
                <div class="fmc footer-message-mid">
                    <div class="ser-num"></div>
                    <span>微信服务号</span>
                </div>
                <div class="fmc footer-message-right">
                    <div class="off-num"></div>
                    <span>微信公众号</span>
                </div>
            </div>
            <address>北京市昌平区黄平路19号龙旗广场D座610</address>
            <br><hr>
            <p style="text-align: center">网站声明 | 法律版权 | 京ICP备案：17000897号 Copyright © 2017 OBSeC Inc. 北京匠迪</p>
        </div>
    </div>
</footer>
<script type="text/javascript" src="dist/js/jquery-3.1.1.min.js"></script>
<script src="./dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="dist/js/countUp.js"></script>
 <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=vDR0scA5MDOrnxj556GHUCGHD0zbmk5B&s=1"></script>
<script type="text/javascript">
    // 百度地图API功能
    var map = new BMap.Map("footer_add");
    var point = new BMap.Point(116.355512,40.072434);
    map.centerAndZoom(point, 18);
    function addMarker(point,index){
       var myIcon = new BMap.Icon("images/markes.png",new BMap.Size(23,30),{
           offset:new BMap.Size(10,25)
          });
        var marker = new BMap.Marker(point,{icon:myIcon});  // 创建标注
        map.addOverlay(marker);               // 将标注添加到地图中
        marker.enableDragging();//可拖拽
        marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
    }
    addMarker(point,1)
    map.enableScrollWheelZoom();//可缩放
</script> 
 <script>
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
          decimal : '.', 
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




//锚点动画
//  $(".second").click(function(){
//       $("html, body").animate({
//            scrollTop: $("#second").offset().top,

//       }, 1500);
//  });
// $(".third").click(function(){
//       $("html, body").animate({
//            scrollTop: $(this).offset().top 
//       },1500);
//  });
</script> 
</body>
</html>
