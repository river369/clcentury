<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$order= $_SESSION['orderDetail'];
$orderActions = $_SESSION['orderActionDetails'] ;

function getOrderStatusString($status)
{
    $orderStatus = '';
    switch ($status)
    {
        case 0:
            $orderStatus = "买家已付款,等待卖家接收";
            break;
        case 102:
            $orderStatus = "卖家拒绝了该订单";
            break;
        case 2:
            $orderStatus = "卖家已接收";
            break;
        case 104:
            $orderStatus = "卖家取消了该订单";
            break;
        case 4:
            $orderStatus = "卖家已将订单置为完成,等待买家确认";
            break;
        case 106:
            $orderStatus = "买家取消了该订单";
            break;
        case 6:
            $orderStatus = "买家已将订单置为完成,等待易知付款";
            break;
        case 8:
            $orderStatus = "易知已经完成付款,订单结束";
            break;

    }
    return $orderStatus;
}

$orderStatus = getOrderStatusString($order['conditions']);

?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>易知海外</title>

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="../../resource/js/horizontal/jquery.timelineMe.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/horizontal/jquery.timelineMe.css" />
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed">
        <h1>订单详情</h1>
    </div>
    <div data-role="navbar">
        <ul>
            <li><a href="javascript:showStatus()">订单状态(北京时间)</a></li>
            <li><a href="javascript:showOrderDetail()" class="ui-btn-active">订单详情</a></li>
        </ul>
    </div><!-- /navbar -->
    <div data-role="content" id="orderstatus">
        <div id="timeline-container-basic" type="text"></div>
    </div>

    <div data-role="content" id="orderdetail">
        <ul data-role="listview" data-inset="true">
            <li data-role="list-divider">订单号: <span class="ui-li-count"><?php echo $order['id'];?></span></li>
            <li data-role="list-divider">卖家: <span class="ui-li-count"><?php echo $order['sellername'];?></span></li>
            <li data-role="list-divider">买家: <span class="ui-li-count"><?php echo $order['customername'];?></span></li>
            <li data-role="list-divider">订单状态: <span class="ui-li-count"><?php echo $orderStatus;?></span></li>
            <li data-role="list-divider">买家留言: <span class="ui-li-count"><?php echo $order['requestmessage'];?></span></li>
            <li data-role="list-divider">价格: <span class="ui-li-count">￥<?php echo $order['serviceprice'];?>/小时</span></li>
            <li data-role="list-divider">已购买: <span class="ui-li-count"><?php echo $order['servicehours'];?>小时</span></li>
            <li data-role="list-divider">总计: <span class="ui-li-count"><?php echo $order['servicetotalfee'];?>元</span></li>
        </ul>
    </div>

    <div data-role="footer" data-position="fixed">
        <h4>Copyright (c) 2016 .</h4>
    </div>
</div>

<script>
    $('#orderdetail').show();
    $('#orderstatus').hide();
    $('#timeline-container-basic').timelineMe({
        items: [
            <?php
            $lastAction = 0;
            foreach($orderActions as $key => $orderAction)
            {
                $lastAction = $orderAction['action'];
                $actionString = getOrderStatusString($lastAction);
                echo "{ type: 'smallItem', label: '".$actionString.
                    "', shortContent: '".$orderAction['creation_date'] ."',},";
            ?>
            <?php } ?>
            <?php if ($lastAction < 2) {?>
            {
                type: 'milestone',
                label: '卖家确认',
            },
            <?php } ?>
            <?php if ($lastAction < 4) {?>
            {
                type: 'milestone',
                label: '卖家完成任务',
            },
            <?php } ?>
            <?php if ($lastAction < 6) {?>
            {
                type: 'milestone',
                label: '买家评论',
            },
            <?php } ?>
            <?php if ($lastAction < 8) {?>
            {
                type: 'milestone',
                label: '易知付款',
            },
            <?php } ?>
        ]
    });
    function showOrderDetail() {
        $('#orderdetail').show();
        $('#orderstatus').hide()
    }

    function showStatus() {
        $('#orderdetail').hide();
        $('#orderstatus').show();
    }
</script>
</body>
</html>

