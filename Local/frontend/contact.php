<?php
include "session.php";
include "header.php";
include "mysql_config.php";

//查询所有用户数据
    $mysql_str = "select id,user_name,diction,register_time from {$tb_prefix}_user order by register_time desc";
    $query = $pdo->prepare($mysql_str);
    $query->execute();
    $res = $query->fetchall(PDO::FETCH_ASSOC);
    $num = 1;
    foreach($res as $key => &$value){
    	$value['num'] = $num;
	    $value['caozuo'] = "<a href='##'><span class='operation edit btn' data-toggle ='modal' data-target='#modify_modal' title='编辑' id={$value['id']} user_name={$value['user_name']} diction={$value['diction']} >编辑</span></a>&nbsp;&nbsp;<a href='javascript:;'><span class='operation del' title='删除' id={$value['id']}>删除</span></a>";
		$value['diction'] = $value['diction'] == 1 ? '系统管理员' : '代码审计';
	    $num++;
    }
    $json_res = json_encode($res);
?>
    <link rel="stylesheet" href="./dist/css/contact.css">
    <link rel="stylesheet" href="./dist/css/table.css">
    <link rel="stylesheet" href="./dist/css/pop-up.css">
    
<div class="container">
    <div class="row" style="margin-top: 10px">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 addtitle">
            <a href = "contact.php" style=" background-color:rgba(34,41,48,.6);box-shadow: 2px 2px 3px #000;">用户管理</a>
            <a href = "sys-tool.php" class= "secondT">网络管理</a>
            <a href = "sys-jour.php" class= "secondT">设备日志</a>
            <a href = "message.php" class= "secondT">设备管理</a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 addtitle">
            <a href="##"  class="adduser btn" data-toggle = "modal"  data-target="#creatrole_add_modal" style="font-size:14px">添加用户</a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <table id = "table" class="table"></table>
        </div>
    </div>
</div>

<!-- 添加新用户弹窗 -->
<div class="modal fade" id="creatrole_add_modal"  aria-labelledby="creatrole_add_title" aria-hidden="true" data-backdrop= "false">
    <div class="modal-dialog popup0">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                    </button>
                    <h4 class="modal-title" id="creatrole_add_title">
                        添加新用户
                    </h4>
                </div>
                <form id="form" onsubmit="return false">
                    <div class="modal-body" style="margin-top: 20px">
                        <p>
                            <span>用&nbsp;&nbsp;户&nbsp;&nbsp;名</span>
			                <input type="text" name="nickname" placeholder="请输入用户名 中文 数字 字母（3-15）" class="message-input">
			                <label class="warning nickname" style="font-weight:100"></label>
                        </p>
                        <p>
                            <span>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码</span>
			                <input type="password" name="password" placeholder="请输入密码 数字 字母 标点（6-20）" class="message-input">
			                <label class="warning password" style="font-weight:100"></label>
                        </p>
                        <p>
                            <span>确认密码</span>
			                <input type="password" name="password_ok" placeholder="请再次输入密码" class="message-input">
			                <label class="warning password_ok" style="font-weight:100"></label>
                        </p>
                        <p>
                            <span>角&nbsp;&nbsp;色&nbsp;&nbsp;组</span>
                            <select name='diction'>
                                <option value="1" >系统管理员</option>
                                <option value="2" selected="selected">代码审计员</option>
                            </select>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-pri-def register">
                            确定
                        </button>
                        <button type="button" class="btn btn-default btn-pri-def" data-dismiss="modal">
                            取消
                        </button>
                    </div>
                </form>
            </div>
    </div>
</div>
<!-- 修改用户弹窗 -->
<div class="modal fade" id="modify_modal"  aria-labelledby="modify_title" aria-hidden="true" data-backdrop= "false">
    <div class="modal-dialog popup1">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                    </button>
                    <h4 class="modal-title" id="modify_title">
                        修改用户信息
                    </h4>
                </div>
                <form id="form" onsubmit="return false" class="edit_form">
                    <div class="modal-body" style="margin-top: 20px">
                        <p>
                            <span>用&nbsp;&nbsp;户&nbsp;&nbsp;名</span>
			                <input type="text" name="id" display="none">
			                <input type="text" name="edit_nickname" placeholder="请输入用户名 中文 数字 字母（3-15）" readonly="readonly" class="message-input">
			                <label class="warning edit_nickname" style="font-weight:100"></label>
                        </p>
                        <p>
                            <span>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码</span>
							<input type="password" name="edit_password" placeholder="请输入密码 数字 字母 标点（6-20）" class="message-input">
			                <label class="warning edit_password" style="font-weight:100"></label>
                        </p>
                        <p>
                            <span>确认密码</span>
			                <input type="password" name="edit_password_ok" placeholder="请再次输入密码" class="message-input">
			                <label class="warning edit_password_ok" style="font-weight:100"></label>
                        </p>

                        <p>
                            <span>角&nbsp;&nbsp;色&nbsp;&nbsp;组</span>
                            <select name='diction'>
                                <option value="1" >系统管理员</option>
                                <option value="2" selected="selected">代码审计员</option>
                            </select>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-pri-def edit_reg">
                            确定
                        </button>
                        <button type="button" class="btn btn-default btn-pri-def" data-dismiss="modal">
                            取消
                        </button>
                    </div>
                </form>
            </div>
    </div>
</div>



    <script src="./dist/js/jquery-3.1.1.min.js"></script>
    <script src="./dist/js/bootstrap.min.js"></script>
    <script src="./dist/js/bootstrap-table.js"></script>
    <script>
        $('#table').bootstrapTable({
	         pagination: true,
             pageNumber:1,
             pageSize: 10,
             pageList: [10,20],
            columns: [
                {
                    field: 'num',
                    title: '序号',
                    align:'center',
                    valign:'middle'

                }, {
                    field: 'user_name',
                    title: '用户名',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'diction',
                    title: '所属角色组',
                    align:'center',
                    valign:'middle'
                },{
                    field: 'register_time',
                    title: '创建时间',
                    align:'center',
                    valign:'middle'
                },{
                    field:'caozuo',
                    title:'操作',
                    align:'center',
                    valian:'middle'
                }
            ],
		data:<?php echo $json_res?>

        });

    $(".adduser").click(function(){
        objM($(".popup0"));
        $(this).css("background-color","#20262f")
    });
    $(".close,.btn-pri-def").click(function(){
        $(".adduser").css("background-color","#2a323d");
    })
	$('.register').click(function(){
        	$.ajax({
            		url: 'register.php',
            		type: "post",
            		dataType:'json',
            		data: $("#form").serialize(),
            		success: function (data) {
                		prompt_error(data);
            		},
            		error: function (err) {
                		alert('添加用户失败，请重新添加');
                		location.href = "contact.php";
            		}
        	});
	})
    //错误信息提示
    function prompt_error(data){
        if(!data.success){
            var title = data.title;
            var result_err = data.result_err;
            $(".message-input").each(function(i){
                if($(this).attr('name') == title){
                    $('.'+title).css("display","block");
                    $('.'+title).html(result_err);
                }else{
                    $(".warning").eq(i).css("display","none");
                    $(".warning").eq(i).html('');
                }
            })
        }else{
            location.href = "contact.php";
        }
    }
    //删除用户
    $('.del').click(function(){
        var id = $(this).attr('id');
        $.ajax({
            url: 'del_user.php',
            type: "post",
            dataType:'json',
            data: {id:id},
            success: function (data) {
                if(data.success){
                    location.href = "contact.php";
                }else{
                   alert('用户删除失败，请重新删除');
                }
            },
            error: function (err) {
                alert('用户删除失败，请重新删除');
                location.href = "contact.php";
            }
        });
    });
	//编辑用户
	$('.edit').click(function(){
        objM($(".popup1"));
		var id = $(this).attr('id');
		var user_name = $(this).attr('user_name');
		var diction = $(this).attr('diction');
		$('#modify_modal #form input[name="id"]').val(id);
		$('#modify_modal #form input[name="edit_nickname"]').val(user_name);
		if(diction == 1){
			$('#modify_modal #form option[value="1"]').attr('selected',true);
		}else if(diction == 2){
			$('#modify_modal #form option[value="2"]').attr('selected',true);
		}
	});
	$('.edit_reg').click(function(){
		$.ajax({
			url: 'up_user.php',
			type: "post",
			dataType:'json',
			data: $(".edit_form").serialize(),
			success: function (data) {
				if(data.success){
					location.href = "contact.php";
				}else{
					prompt_error(data);
				}
			},
			error: function (err) {
				alert('用户修改失败，请重新修改');
				location.href = "contact.php";
			}
		});
	})


    //弹框拖动
//     function drag() {
//     var obj = $('.modal-dialog');
//     obj.bind('mousedown', start);
//     function start(e) {
//         var ol = obj.offset().left;
//         var ot = obj.offset().top;

//         deltaX = e.pageX - ol;
//         deltaY = e.pageY - ot;
//         $(document).bind({
//             'mousemove': move,
//             'mouseup': stop
//         });
//         return false;
//     }
//     function move(e) {
//         obj.css({
//             "left": (e.pageX - deltaX),
//             "top": (e.pageY - deltaY)
//         });
//         return false;
//     }
//     function stop() {
//         $(document).unbind({
//             'mousemove': move,
//             'mouseup': stop
//         });
//     }
// }
// drag();
   
   
    function objM(objMove){
        $('.modal-dialog').css({"top":"30px","left":"50%","margin-left":"-300px"})
        objMove.mousedown(function(e){
            var maxMoveL = $(window).width()-objMove.width();
            var maxMoveT = $(window).height()-objMove.height();
            var obj = e || event;
            var ol = obj.pageX - $(this).offset().left;
            var ot = obj.pageY - $(this).offset().top;
            $(document).mousemove(function(e){
                var obj2 = e || event;
                var newLeft = obj2.pageX - ol;
                var newTop = obj2.pageY - ot;
                if(newLeft<0){
                    newLeft = 0;
                }else if(newLeft>maxMoveL){
                    newLeft = maxMoveL;
                }

                if(newTop<0){
                    newTop = 0;
                }else if(newTop>maxMoveT-4){
                    newTop = maxMoveT-4;
                }
               
                objMove.css({"left":newLeft,"top":newTop,"margin-left":"0px","cursor":"Move"});
                return false;
            })
        })
        $(document).mouseup(function(){
            $(this).unbind("mousemove");
            objMove.css("cursor","Default");
        })
    }
    
    </script>
</body>
</html>
