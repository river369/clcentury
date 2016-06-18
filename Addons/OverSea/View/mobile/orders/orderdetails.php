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
$orderStatus = getOrderStatusString($order['status']);
$orderStopReason = getOrderStopReason($order['status'], $orderActions);

function getOrderStatusString($condition)
{
    $orderStatus = '';
    switch ($condition)
    {
        case 0:
            $orderStatus = "订单已创建";
            break;
        case 10:
            $orderStatus = "买家已付款,等待卖家接收";
            break;
        case 1020:
            $orderStatus = "卖家拒绝了该订单";
            break;
        case 20:
            $orderStatus = "卖家已接收";
            break;
        case 1040:
            $orderStatus = "卖家取消了该订单";
            break;
        case 40:
            $orderStatus = "卖家已将订单置为完成,等待买家确认";
            break;
        case 1060:
            $orderStatus = "买家取消了该订单";
            break;
        case 60:
            $orderStatus = "买家已将订单置为完成,等待易知付款";
            break;
        case 80:
            $orderStatus = "买家完成评论";
            break;
        case 100:
            $orderStatus = "易知已经完成付款,订单结束";
            break;

    }
    return $orderStatus;
}

function getOrderStopReason($condition, $orderActions)
{
    foreach ($orderActions as $key => $orderAction) {

        if ($orderAction['action'] == $condition){
            return $orderAction['comments'];
        }
    }
    return null;
}
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
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
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
            <li data-role="list-divider">订单状态: <span class="ui-li-count"><?php echo $orderStatus;?></span></li>
            <li data-role="list-divider">卖家: <span class="ui-li-count"><?php echo $order['seller_name'];?></span></li>
            <li data-role="list-divider">买家: <span class="ui-li-count"><?php echo $order['customer_name'];?></span></li>
            <?php if(!is_null($orderStopReason) && !empty($orderStopReason)){ ?>
                <li data-role="list-divider">订单停止原因: <span class="ui-li-count"><?php echo $orderStopReason;?></span></li>
            <?php } ?>
            <li data-role="list-divider">买家咨询话题: <span class="ui-li-count"><?php echo $order['request_message'];?></span></li>
            <li data-role="list-divider">价格: <span class="ui-li-count">￥<?php echo $order['service_price'];?>/小时</span></li>
            <li data-role="list-divider">已购买: <span class="ui-li-count"><?php echo $order['service_hours'];?>小时</span></li>
            <li data-role="list-divider">总计: <span class="ui-li-count"><?php echo $order['service_total_fee'];?>元</span></li>
        </ul>
    </div>

    <div data-role="footer" data-position="fixed" data-theme="c">
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
            <?php if ($lastAction < 10) {?>
            {
                type: 'milestone',
                label: '买家支付',
            },
            <?php } ?>
            <?php if ($lastAction < 20) {?>
            {
                type: 'milestone',
                label: '卖家确认',
            },
            <?php } ?>
            <?php if ($lastAction < 40) {?>
            {
                type: 'milestone',
                label: '卖家完成任务',
            },
            <?php } ?>
            <?php if ($lastAction < 60) {?>
            {
                type: 'milestone',
                label: '买家确认完成',
            },
            <?php } ?>
            <?php if ($lastAction < 80) {?>
            {
                type: 'milestone',
                label: '买家评论',
            },
            <?php } ?>
            <?php if ($lastAction < 100) {?>
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

