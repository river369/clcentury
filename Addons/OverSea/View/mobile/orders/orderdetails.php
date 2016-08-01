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

$isOrderNormal = 1;
foreach ($orderActions as $key => $orderAction) {
    $condition = $orderAction['action'];
    if (BusinessHelper::isOrderException($condition) == 0) {
        $isOrderNormal = 0;
    }
}
$isMine = 1;
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
    <script src="../../resource/js/rater/rater.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/horizontal/jquery.timelineMe.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <style>
        .rate-base-layer
        {
            color: #aaa;
        }
        .rate-hover-layer
        {
            color: orange;
        }
        .rate-select-layer
        {
            color:orange;
        }
        h5{ color:#33c8ce}
        p{ font-size:14px;}
        table{ table-layout : fixed; width:100%; }
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>订单详情</h1>
    </div>
    <div role="main" class="ui-content">
        <div data-role="navbar">
            <ul>
                <li><a href="javascript:showOrderDetail()" class="ui-btn-active" data-theme="e">订单详情</a></li>
                <li><a href="javascript:showStatus()" data-theme="e">订单状态(北京时间)</a></li>
            </ul>
        </div><!-- /navbar -->
        <div data-role="content" id="orderstatus">
            <div id="timeline-container-basic" type="text"></div>
        </div>

        <div data-role="content" id="orderdetail">
            <h5>订单信息</h5>
            <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                <li>订单号: <span class="ui-li-count"><?php echo $order['order_id'];?></span></li>
                <li>订单状态: <span class="ui-li-count"><?php echo $orderStatus;?></span></li>
                <li>服务名称: <span class="ui-li-count"><?php echo $order['service_name'];?></span></li>
                <li>卖家: <span class="ui-li-count"><?php echo $order['seller_name'];?></span></li>
                <li>买家: <span class="ui-li-count"><?php echo $order['customer_name'];?></span></li>
                <li>买家咨询话题: <span class="ui-li-count"><?php echo $order['request_message'];?></span></li>
                <li>价格: <span class="ui-li-count">￥<?php echo $order['service_price'];?>/小时</span></li>
                <li>已购买: <span class="ui-li-count"><?php echo $order['service_hours'];?>小时</span></li>
                <li>总计: <span class="ui-li-count"><?php echo $order['service_total_fee'];?>元</span></li>
            </ul>
            <br>
            <h5>卖家联系方式:</h5>
            <?php if (isset($sellerData)) {?>
                <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                    <li>微信号: <span class="ui-li-count"><?php echo $sellerData['weixin'];?></span></li>
                </ul>
            <?php } else {?>
                <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                    <li>买家付款,卖家接收后即可显示卖家联系方式</li>
                </ul>
            <?php } ?>

            <br>

            <?php if ($isOrderNormal == 0){?>
                <h5>订单异常变更信息:</h5>
                <?php foreach ($orderActions as $key => $orderAction) {
                    $condition = $orderAction['action'];
                    $isException = BusinessHelper::isOrderException($condition);
                    if ($isException == 0 || ($condition == 40)) {?>
                        <div>
                            <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                                <li>变更内容: <span class="ui-li-count"><?php echo BusinessHelper::translateOrderStatus($condition)?></span></li>
                                <li>
                                    <p>原因:<?php echo $orderAction['comments'];?><p>
                                    <p>日期:<?php echo substr($orderAction['creation_date'], 0, 10 );?><p>
                                </li>
                            </ul>
                        </div>
                        <hr>
                <?php }
                    }
            } ?>

            <br>
            <h5>客户评论:</h5>
            <?php if (isset($commentsData) && count($commentsData) >0) {?>
                <?php
                foreach ($commentsData as $comment){ ?>
                    <div>
                        <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                            <li>评论者:<?php echo $comment['customer_name'];?> <span class="ui-li-count"><?php echo BusinessHelper::translateOrderFeeling($comment['stars']); ?></span></li>
                            <li>
                                <p>意见:<?php echo $comment['comments'];?><p>
                                <p>日期:<?php echo substr($comment['creation_date'], 0, 10 );?><p>
                            </li>
                        </ul>
                    </div>
                    <hr>
                <?php } ?>
            <?php } else { ?>
                <h5>暂无评论</h5>
            <?php } ?>
        </div>

    </div>
    <?php include '../common/footer.php';?>
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
    $(document).ready(function(){
        var options = {
            max_value: 5,
            step_size: 0.5,
            initial_value: $('#orderratevalue').val(),
        };
        $(".orderrate").rate(options);
    });
</script>
</body>
</html>

