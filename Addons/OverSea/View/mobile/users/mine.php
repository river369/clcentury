<?php
session_start();
$customerid = $_SESSION['signedUser'];
$existedUser = $_SESSION['signedUserInfo'] ;
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>易知海外</title>

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />

</head>
<body>

<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>我的</h1>
    </div>

    <div class="container" id="crop-avatar" data-role="content">
        <ul data-role="listview" data-inset="true">
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=myinfo&customerid=<?php echo $customerid;?>" rel="external">
                    <img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/<?php echo $customerid;?>/head.png?t=<?php echo rand(0,10000); ?>" alt="">
                    <h2><?php echo $existedUser['name']?></h2>
                    <p style="white-space:pre-wrap;">编辑个人信息</p>
                </a>
            </li>
        </ul>
        <h4 style="color:steelblue">服务:</h4>
        <ul data-role="listview" data-inset="true">
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=myServices&sellerid=<?php echo $customerid;?>&status=20" rel="external">
                    我发布的易知服务
                </a>
            </li>
        </ul>
        <h4 style="color:steelblue">订单:</h4>
        <ul data-role="listview" data-inset="true">
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&status=0,10" rel="external">
                    我购买订单
                </a>
            </li>
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=querySellerOrders&sellerid=<?php echo $customerid;?>&status=10" rel="external">
                    购买我服务的订单
                </a>
            </li>
        </ul>
        <h4 style="color:steelblue">个人:</h4>
        <ul data-role="listview" data-inset="true">
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=publishRealName&userid=<?php echo $customerid;?>" rel="external">
                    申请实名认证
                </a>
            </li>
            <li>
                <a href="./change_password.php" rel="external">
                    修改密码
                </a>
            </li>
            <li>
                <a href="../../../Controller/ClearSessions.php" rel="external">
                    退出登录
                </a>
            </li>
        </ul>
    </div>

    <?php include '../common/footer.php';?>
</div>
<script>
    $(document).ready(function(){
        $("img").error(function () {
            $(this).attr("src", "../../resource/images/head_default.jpg");
        });
    });
</script>
</body>
</html>
