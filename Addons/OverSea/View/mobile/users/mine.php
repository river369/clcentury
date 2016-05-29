<?php
session_start();
$customerid = $_SESSION['signedUser'];
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


</head>
<body>

<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed">
        <h1>River</h1>
    </div>

    <div data-role="content">
        <h5>服务信息:</h5>
        <ul data-role="listview" data-inset="true">
            <li>
                <a href="sellerdetails.html" rel="external">
    实名认证
                </a>
            </li>
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&condition=0" rel="external">
    我购买的服务
                </a>
            </li>
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=querySellerOrders&sellerid=<?php echo $customerid;?>&condition=0" rel="external">
    我提供的服务
                </a>
            </li>
            <li>
                <a href="../../../Controller/ClearSessions.php" rel="external">
                    退出登录
                </a>
            </li>
        </ul>
    </div>



    <div data-role="footer" data-position="fixed">
        <h4>Copyright (c) 2016 .</h4>
    </div>
</div>
</body>
</html>
