<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
require dirname(__FILE__).'/../../../init.php';
use Addons\OverSea\Common\BusinessHelper;

$order= $_SESSION['orderDetail'];
$orderActions = $_SESSION['orderActionDetails'] ;
$commentsData = $_SESSION['commentsData'];
$sellerData = $_SESSION['sellerData'];

$orderStatus = BusinessHelper::translateOrderStatus($order['status']);
$orderStopReason = getOrderStopReason($order['status'], $orderActions);

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
    <div role="main" class="ui-content">
        <div data-role="navbar">
            <ul>
                <li><a href="javascript:showOrderDetail()" class="ui-btn-active">订单详情</a></li>
                <li><a href="javascript:showStatus()">订单状态(北京时间)</a></li>
            </ul>
        </div><!-- /navbar -->
        <div data-role="content" id="orderstatus">
            <div id="timeline-container-basic" type="text"></div>
        </div>

        <div data-role="content" id="orderdetail">
            <h4 style="color:steelblue">订单信息</h4>
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
            <br>
            <h4 style="color:steelblue">卖家联系方式</h4>
            <?php if (isset($sellerData)) {?>
                <ul data-role="listview" data-inset="true">
                    <li data-role="list-divider">微信号: <span class="ui-li-count"><?php echo $sellerData['weixin'];?></span></li>
                </ul>
            <?php } else {?>
                <h5>付款后可以获取卖家联系方式</h5>
            <?php } ?>


            <br>
            <h4 style="color:steelblue">客户评论</h4>
            <?php if (isset($commentsData) && count($commentsData) >0) {?>
                <?php
                foreach ($commentsData as $comment){ ?>
                    <div>
                        <p>评论者:<?php echo $comment['customer_name'];?> 于 <?php echo $comment['creation_date']?><p>
                        <p>评分等级:<?php echo $comment['stars'];?>星<p>
                        <p>详细意见:<?php echo $comment['comments'];?><p>
                    </div>
                    <hr>
                <?php } ?>
            <?php } else {?>
                <h5>暂无评论</h5>
            <?php } ?>
        </div>

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
                $actionString = BusinessHelper::translateOrderStatus($lastAction);
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

