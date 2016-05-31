<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$orders= $_SESSION['customerOrders'];
$customerid = $_SESSION['customerId'] ;
$ordersCondition= $_SESSION['customerOrdersCondition'];
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
    <style type="text/css">
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed">
        <h1>我购买的服务</h1>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <div data-role="navbar">
            <ul>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&condition=0" <?php echo $ordersCondition == 0 ? "class='ui-btn-active'" : ''; ?> rel="external">已购买</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&condition=20" <?php echo $ordersCondition == 20 ? "class='ui-btn-active'" : '' ?> rel="external">已接收</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&condition=40" <?php echo $ordersCondition == 40 ? "class='ui-btn-active'" : '' ?> rel="external">待确认</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&condition=60" <?php echo $ordersCondition == 60 ? "class='ui-btn-active'" : '' ?> rel="external">已完成</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&condition=1020,1040,1060" <?php echo $ordersCondition == '1020,1040,1060' ? "class='ui-btn-active'" : '' ?> rel="external">已取消</a></li>
            </ul>
        </div><!-- /navbar -->
        <?php
        foreach($orders as $key => $order)
        {
            $orderid = $order['id'];
        ?>
        <ul data-role="listview" data-inset="true">
            <li data-role="list-divider">订单号: <span class="ui-li-count"><?php echo $order['id'];?></span></li>
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=queryOrderDetails&orderid=<?php echo $orderid; ?>" rel="external">
                    <img class="weui_media_appmsg_thumb" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAMAAAAOusbgAAAAeFBMVEUAwAD///+U5ZTc9twOww7G8MYwzDCH4YcfyR9x23Hw+/DY9dhm2WZG0kbT9NP0/PTL8sux7LFe115T1VM+zz7i+OIXxhes6qxr2mvA8MCe6J6M4oz6/frr+us5zjn2/fa67rqB4IF13XWn6ad83nxa1loqyirn+eccHxx4AAAC/klEQVRo3u2W2ZKiQBBF8wpCNSCyLwri7v//4bRIFVXoTBBB+DAReV5sG6lTXDITiGEYhmEYhmEYhmEYhmEY5v9i5fsZGRx9PyGDne8f6K9cfd+mKXe1yNG/0CcqYE86AkBMBh66f20deBc7wA/1WFiTwvSEpBMA2JJOBsSLxe/4QEEaJRrASP8EVF8Q74GbmevKg0saa0B8QbwBdjRyADYxIhqxAZ++IKYtciPXLQVG+imw+oo4Bu56rjEJ4GYsvPmKOAB+xlz7L5aevqUXuePWVhvWJ4eWiwUQ67mK51qPj4dFDMlRLBZTqF3SDvmr4BwtkECu5gHWPkmDfQh02WLxXuvbvC8ku8F57GsI5e0CmUwLz1kq3kD17R1In5816rGvQ5VMk5FEtIiWislTffuDpl/k/PzscdQsv8r9qWq4LRWX6tQYtTxvI3XyrwdyQxChXioOngH3dLgOFjk0all56XRi/wDFQrGQU3Os5t0wJu1GNtNKHdPqYaGYQuRDfbfDf26AGLYSyGS3ZAK4S8XuoAlxGSdYMKwqZKM9XJMtyqXi7HX/CiAZS6d8bSVUz5J36mEMFDTlAFQzxOT1dzLRljjB6+++ejFqka+mXIe6F59mw22OuOw1F4T6lg/9VjL1rLDoI9Xzl1MSYDNHnPQnt3D1EE7PrXjye/3pVpr1Z45hMUdcACc5NVQI0bOdS1WA0wuz73e7/5TNqBPhQXPEFGJNV2zNqWI7QKBd2Gn6AiBko02zuAOXeWIXjV0jNqdKegaE/kJQ6Bfs4aju04lMLkA2T5wBSYPKDGF3RKhFYEa6A1L1LG2yacmsaZ6YPOSAMKNsO+N5dNTfkc5Aqe26uxHpx7ZirvgCwJpWq/lmX1hA7LyabQ34tt5RiJKXSwQ+0KU0V5xg+hZrd4Bn1n4EID+WkQdgLfRNtvil9SPfwy+WQ7PFBWQz6dGWZBLkeJFXZGCfLUjCgGgqXo5TuSu3cugdcTv/HjqnBTEMwzAMwzAMwzAMwzAMw/zf/AFbXiOA6frlMAAAAABJRU5ErkJggg==" alt="">
                    <h2> <?php echo $order['sellername'];?> </h2>
                    <p style="white-space:pre-wrap;"><?php echo $order['requestmessage'];?> </p>
                    <p class="ui-li-aside">￥<?php echo $order['serviceprice'];?>/小时</p>
                </a>
            </li>
            <li data-role="list-divider">已购买: <?php echo $order['servicehours'];?>小时 <span class="ui-li-count">总计: <?php echo $order['servicetotalfee'];?>元</span></li>
            <?php if ($ordersCondition == 0 || $ordersCondition == 20) {?>
                <li>
                    <div class="ui-grid-a">
                        <div class="ui-block-a"><a href="#cancelDialog" data-rel="popup" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="cancelPopup('<?php echo $orderid; ?>')">取消</a></div>
                    </div>
                </li>
            <?php } ?>
            <?php if ($ordersCondition == 40) {?>
                <li>
                    <div class="ui-grid-a">
                        <div class="ui-block-a"><a href="#confirmDialog" data-rel="popup" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="confirmPopup('<?php echo $orderid; ?>')">确认完成</a></div>
                    </div>
                </li>
            <?php } ?>
        </ul>
        <?php } ?>

        <div data-role="popup" id="confirmDialog" data-overlay-theme="a" data-theme="a" style="max-width:400px;">
            <div data-role="header" data-theme="a">
                <h1>确认完成</h1>
            </div>
            <div role="main" class="ui-content">
                <h3 class="ui-title" id="confirmOrderString">确认订单已履约完成? </h3>
                <form id="confirmorder" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=customerConfirmOrder">
                    <input type="hidden" name="confirmorderid" id="confirmorderid" value="">
                    <input type="submit" name="confirmsubmit" id="confirmsubmit" value="确定">
                </form>
                <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
            </div>
        </div>

        <div data-role="popup" id="cancelDialog" data-overlay-theme="a" data-theme="a" style="max-width:400px;">
            <div data-role="header" data-theme="a">
                <h1>取消订单</h1>
            </div>
            <div role="main" class="ui-content">
                <h3 class="ui-title" id="cancelOrderString">确定取消订单? </h3>
                <form id="cancelOrder" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=customerCancelOrder">
                    <input type="hidden" name="cancelorderid" id="cancelorderid" value="">
                    <label for="cancelreason">取消原因:</label>
                    <textarea cols="30" rows="8" name="cancelreason" id="cancelreason" data-mini="true"></textarea>
                    <input type="submit" name="cancelsubmit" id="cancelsubmit" value="无奈取消">
                </form>
                <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
            </div>
        </div>

    </div>

    <div data-role="footer" data-position="fixed">
        <h4>Copyright (c) 2016 .</h4>
    </div>
</div>
<script>
    function confirmPopup(orderid) {
        var messages = "确认订单" + orderid + "已履约完成?";
        //alert (messages);
        $('#confirmOrderString').html(messages);
        $('#confirmorderid').val(orderid);
        $('#confirmDialog').popup('open');
    };
    function cancelPopup(orderid) {
        var messages = "确定取消订单" + orderid + "?";
        $('#cancelOrderString').html(messages);
        $('#cancelorderid').val(orderid);
        $('#cancelDialog').popup('open');
    };
</script>
</body>
</html>

