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
$ordersStatus= $_SESSION['customerOrdersStatus'];
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
    <style type="text/css">
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>我购买的服务</h1>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <div data-role="navbar">
            <ul>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&status=0,10" <?php echo ($ordersStatus == 0 || $ordersStatus == 10) ? "class='ui-btn-active'" : ''; ?> rel="external">已下单</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&status=20" <?php echo $ordersStatus == 20 ? "class='ui-btn-active'" : '' ?> rel="external">已接收</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&status=40" <?php echo $ordersStatus == 40 ? "class='ui-btn-active'" : '' ?> rel="external">待确认</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&status=60,80,100" <?php echo ($ordersStatus == 60 || $ordersStatus == 80 || $ordersStatus == 100) ? "class='ui-btn-active'" : '' ?> rel="external">已完成</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&status=1020,1040,1060" <?php echo ($ordersStatus == 1020 || $ordersStatus == 1040 || $ordersStatus == 1060) ? "class='ui-btn-active'" : '' ?> rel="external">已取消</a></li>
            </ul>
        </div><!-- /navbar -->
        <?php
        foreach($orders as $key => $order)
        {
            $orderid = $order['id'];
            $orderStatus = $order['status'];
        ?>
        <ul data-role="listview" data-inset="true">
            <li data-role="list-divider">订单号: <span class="ui-li-count"><?php echo $order['id'];?></span></li>
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=queryOrderDetails&order_id=<?php echo $orderid; ?>" rel="external">
                    <img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/<?php echo $order['seller_id'];?>/head.png" alt="">
                    <h2> 卖家:<?php echo $order['seller_name'];?> </h2>
                    <p style="white-space:pre-wrap;"><?php echo $order['request_message'];?> </p>
                    <p class="ui-li-aside">￥<?php echo $order['service_price'];?>/小时</p>
                </a>
            </li>
            <li data-role="list-divider">已购买: <?php echo $order['service_hours'];?>小时 <span class="ui-li-count">总计: <?php echo $order['service_total_fee'];?>元</span></li>
            <?php if ($ordersStatus <= 20) {?>
                <li data-theme="c">
                    <div class="ui-grid-a">
                        <div class="ui-block-a"><a href="#cancelDialog" data-rel="popup" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="cancelPopup('<?php echo $orderid; ?>')">取消</a></div>
                        <?php if ($orderStatus == 0) {?>
                          <div class="ui-block-b"><a href="../../../Controller/AuthUserDispatcher.php?c=repayOrder&order_id=<?php echo $orderid; ?>" rel="external" class="ui-shadow ui-btn ui-corner-all ui-mini">去付款</a></div>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>
            <?php if ($ordersStatus == 40) {?>
                <li data-theme="c">
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

    <div data-role="footer" data-position="fixed" data-theme="c">
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
    $(document).ready(function(){
        $("img").error(function () {
            $(this).attr("src", "../../resource/images/head_default.jpg");
        });
    });
</script>
</body>
</html>

