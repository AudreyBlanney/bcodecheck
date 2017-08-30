<?php
    include 'session.php';
    include 'mysql_config.php';
    include 'header.php';
    //获取用户信息
    $mysql_str = "select id,user_name,phone,email,register_time from {$tb_prefix}_user where (user_name = ? or phone = ? or email = ?)  and switch_type = ?";
    $query = $pdo->prepare($mysql_str);
    $query->execute(array($_SESSION['user_name'],$_SESSION['user_name'],$_SESSION['user_name'],1));
    $res = $query->fetch(PDO::FETCH_ASSOC);
?>
<link rel="stylesheet" href="./dist/css/userinfo.css">
<div class="container">
    <div class="row main">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="useheibg"></div>

        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 usehei" >
            <div class="usemessage">
                <div class="linemessage">
                    <span class="markname">用户名：</span>
                    <span class="markmessage"><?php echo $res['user_name']?></span>
                </div>
                <div class="linemessage">
                    <span class="markname">电话：</span>
                    <span class="markmessage"><?php echo $res['phone']?></span>
                </div>
                <div class="linemessage">
                    <span class="markname">邮箱：</span>
                    <span class="markmessage"><?php echo $res['email']?></span>
                </div>
                <div class="linemessage" style="border-bottom: none">
                    <span class="markname">创建时间：</span>
                    <span class="markmessage"><?php echo $res['register_time']?></span>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>