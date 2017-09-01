<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/Public/Css/bootstrap.css">
    <link rel="stylesheet" href="/Public/Css/caf.css">
    <link rel="stylesheet" href="/Public/Css/table.css">
    <link rel="stylesheet" href="/Public/Css/messshow.css">
</head>
<body>
<div  class="tab-content tab-content-top">
    <div class="scroll-container">
        <div class="scroll">
            <div class="tab-pane fade in active changecode" id="tabmessage">
                <table>
                    <?php if(is_array($content_array)): foreach($content_array as $key=>$vo): ?><tr style="font-size: 13px" id="<?php echo ($key+1); ?>">
                        <td>
                            <?php echo ($key+1); ?>
                        </td>
                        <td style="width:100%">
                            <?php echo '<pre id="pre">';echo $vo?>
                        </td>
                    </tr><?php endforeach; endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<!--下面结构-->
<ul class="nav nav-tabs tabsT">
    <li class="active"><a href="#tab2" data-toggle="tab" class="tab-titles">缺陷描述</a></li>
    <li><a href="#tab3" data-toggle="tab" class="tab-titles">修改建议</a></li>
    <li><a href="#tab4" data-toggle="tab" class="tab-titles">缺陷审计</a></li>
    <li><a href="#tab1" data-toggle="tab" class="tab-titles">跟踪路径</a></li>
</ul>
<div  class="tab-content tab-content-bottom ">
    <div class="tab-pane fade in active changedescribe scroll-right" id="tab2">
        <p class="part4-text">
           <?php echo ($leak_defect_des); ?>
        </p>
    </div>
    <div class="tab-pane fade changesuggest  scroll-right" id="tab3">
        <p class="part4-text">
            <?php echo ($leak_modify_sug); ?>
        </p>
    </div>
    <div class="tab-pane fade changeaudit  scroll-right" id="tab4">
        <form onsubmit="return false" id="form">
            <div class="audit-relative">
                <span class="audit-title">告警等级</span>
                <div class="level-posi">
                    <input name="data_id" type="hidden" value="<?php echo ($data_id); ?>">
                    <input name="xs_view" type="hidden" value="<?php echo ($xs_view); ?>">
                    <input name="grade_status" type="hidden" value="<?php echo ($grade_status); ?>">
                    <input type="radio" name="grade" id="height" value="1" <?php if($leak_grade == 1): ?>checked<?php endif; ?> >
                    <label for="height"></label>
                    <span class="grade-cla">高</span>

                    <input type="radio" name="grade" id="middle" value="2" <?php if($leak_grade == 2): ?>checked<?php endif; ?>>
                    <label for="middle" style="left: 48px;"></label>
                    <span class="grade-cla">中</span>

                    <input type="radio" name="grade" id="low" value="3" <?php if($leak_grade == 3): ?>checked<?php endif; ?>>
                    <label for="low" style="left: 94px;"></label>
                    <span class="grade-cla">低</span>

                    <input type="radio" name="grade" id="ign" value="4" <?php if($leak_grade == 4): ?>checked<?php endif; ?>>
                    <label for="ign" style="left: 138px;"></label>
                    <span class="grade-cla">忽略</span>
                </div>
            </div>
            <div class="audit-relative-bott">
                <span class="audit-title">备注</span>
                <textarea class="text-area" name="describe"><?php echo ($leak_audit_res); ?></textarea>
            </div>
            <button type="submit" class="sub">提交</button>
        </form>
    </div>
    <div class="tab-pane fade in changepath  scroll-right" id="tab1">
        <!--推入跟踪路径   表格-->
        <table id = "tableBottom" class="table table-bordered" style="margin-top: 0">
        </table>
    </div>
</div>
<script src = "/Public/Js/jquery-3.1.1.min.js"></script>
<script src = "/Public/Js/bootstrap.min.js"></script>
<script src="/Public/Js/bootstrap-table.js"></script>
<script>
    $(function(){
        //警告行数显示
        var line_num = "<?php echo ($leak_line_num); ?>";
        $('#'+line_num+' #pre').css('background','#3a80a7');
        var top = $('#'+line_num).offset().top;
        $('.scroll').scrollTop(top-100);
    });
    //提交审计结果
    $('.sub').click(function(){
        $.ajax({
            url: 'upload_data',
            type: "post",
            async : false,
            dataType:'json',
            data: $("#form").serialize(),
            success: function (data){
                if(data.success == false){
                    alert(data.res);
                }else{
                    parent.online_data(data.grade_status,data.xs_view);
                }
            },
            error:function(){
                alert('数据提交失败');
            }
        });
    });
    $('#tableBottom').bootstrapTable({
        columns: [
            {
                field: 'file_type_name',
                title: '文件名',
                align:'center',
                valign:'middle'

            }, {
                field: 'leak_line_num',
                title: '行号',
                align:'center',
                valign:'middle'
            },{
                field: 'code_part',
                title: '代码段',
                align:'center',
                valign:'middle'
            }],
        data:<?php echo ($track_file); ?>
    });
</script>
</body>
</html>