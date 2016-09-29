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
    <meta charset="UTF-10">
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
        h5{ color:lightgrey; font-size:10px;}
        p{ font-size:14px;}
        table{ table-layout : fixed; width:100%; }
        label{ color:#01A4B5}
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page"  data-theme="a" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>订单详情</h1>
    </div>
    <div role="main" class="ui-content">
        <div data-role="navbar">
            <ul>
                <li><a href="#" onclick="showOrderDetail()" class="ui-btn-active">订单详情</a></li>
                <li><a href="#" onclick="showStatus()">订单状态(北京时间)</a></li>
            </ul>
        </div><!-- /navbar -->
        <div data-role="content" id="orderstatus">
            <div id="timeline-container-basic" type="text"></div>
        </div>

        <div data-role="content" id="orderdetail" style="margin: -20px 0px -20px 0px">
            <h5>订单信息</h5>
            <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                <li>
                    <table style="margin: -12px 0px -12px 0px" border="0">
                        <tr>
                            <td style="width:19%">
                                <p style="color:#33c8ce;">订单号</p>
                            </td>
                            <td style="width:81%">
                                <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo $order['order_id'];?></p>
                            </td>
                        </tr>
                    </table>
                </li>
                <li>
                    <table style="margin: -12px 0px -12px 0px" border="0">
                        <tr>
                            <td style="width:19%">
                                <p style="color:#33c8ce;">订单状态</p>
                            </td>
                            <td style="width:81%">
                                <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo $orderStatus;?> </p>
                            </td>
                        </tr>
                    </table>
                </li>
                <li>
                    <table style="margin: -12px 0px -12px 0px" border="0">
                        <tr>
                            <td style="width:19%">
                                <p style="color:#33c8ce;">服务名称</p>
                            </td>
                            <td style="width:81%">
                                <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo $order['service_name'];?></p>
                            </td>
                        </tr>
                    </table>
                </li>
                <li>
                    <table style="margin: -12px 0px -12px 0px" border="0">
                        <tr>
                            <td style="width:19%">
                                <p style="color:#33c8ce;">卖家</p>
                            </td>
                            <td style="width:81%">
                                <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo $order['seller_name'];?> </p>
                            </td>
                        </tr>
                    </table>
                </li>
                <li>
                    <table style="margin: -12px 0px -12px 0px" border="0">
                        <tr>
                            <td style="width:19%">
                                <p style="color:#33c8ce;">买家</p>
                            </td>
                            <td style="width:81%">
                                <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo $order['customer_name'];?> </p>
                            </td>
                        </tr>
                    </table>
                </li>
                <li>
                    <table style="margin: -12px 0px -12px 0px" border="0">
                        <tr>
                            <td style="width:19%">
                                <p style="white-space:pre-wrap; color:#33c8ce;">咨询话题</p>
                            </td>
                            <td style="width:81%">
                                <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo $order['request_message'];?></p>
                            </td>
                        </tr>
                    </table>
                </li>
                <li>
                    <table style="margin: -12px 0px -12px 0px" border="0">
                        <tr>
                            <td style="width:19%">
                                <p style="color:#33c8ce;">价格</p>
                            </td>
                            <td style="width:81%">
                                <p style="white-space:pre-wrap; color:#6f6f6f;">￥<?php echo $order['service_price'];
                                    echo  $order['service_price_type'] == 1 ? "/小时":"/次";?> </p>
                            </td>
                        </tr>
                    </table>
                </li>
                <li>
                    <table style="margin: -12px 0px -12px 0px" border="0">
                        <tr>
                            <td style="width:19%">
                                <p style="color:#33c8ce;">已购买</p>
                            </td>
                            <td style="width:81%">
                                <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo $order['service_hours'];
                                    echo  $order['service_price_type'] == 1 ? "小时":"次";?></p>
                            </td>
                        </tr>
                    </table>
                </li>
                <li>
                    <table style="margin: -12px 0px -12px 0px" border="0">
                        <tr>
                            <td style="width:19%">
                                <p style="color:#33c8ce;">总计</p>
                            </td>
                            <td style="width:81%">
                                <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo $order['service_total_fee'];?>元</p>
                            </td>
                        </tr>
                    </table>
                </li>
            </ul>

            <h5>卖家联系方式:</h5>
            <?php if (isset($sellerData)) {?>
                <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                    <li>
                        <table style="margin: -10px 0px -10px 0px" border="0">
                            <tr>
                                <td style="width:19%">
                                    <p style="color:#33c8ce;">微信号</p>
                                </td>
                                <td style="width:81%">
                                    <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo $sellerData['weixin'];?></p>
                                </td>
                            </tr>
                        </table>
                    </li>
                </ul>
            <?php } else {?>
                <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                    <li><p style="margin: -6px 0px -12px 0px">买家付款,卖家接收后即可显示卖家联系方式</p></li>
                </ul>
            <?php } ?>

            <?php if ($isOrderNormal == 0){?>
                <h5>订单异常变更信息:</h5>
                <?php foreach ($orderActions as $key => $orderAction) {
                    $condition = $orderAction['action'];
                    $isException = BusinessHelper::isOrderException($condition);
                    if ($isException == 0 || ($condition == 40)) {?>
                        <div>
                            <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                                <li>
                                    <table style="margin: -10px 0px -10px 0px" border="0">
                                        <tr>
                                            <td style="width:19%">
                                                <p style="color:#33c8ce;">变更内容</p>
                                            </td>
                                            <td style="width:81%">
                                                <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo BusinessHelper::translateOrderStatus($condition)?></p>
                                            </td>
                                        </tr>
                                    </table>
                                </li>
                                <li style="margin: -10px 0px -10px 0px" >
                                    <p>原因:<?php echo $orderAction['comments'];?><p>
                                    <p>日期:<?php echo substr($orderAction['creation_date'], 0, 10 );?><p>
                                </li>
                            </ul>
                        </div>
                <?php }
                    }
            } ?>

            <h5>客户评论:</h5>
            <?php if (isset($commentsData) && count($commentsData) >0) {?>
                <?php
                foreach ($commentsData as $comment){ ?>
                    <div>
                        <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                            <li>
                                <table style="margin: -10px 0px -10px 0px" border="0">
                                    <tr>
                                        <td style="width:19%">
                                            <p style="color:#33c8ce;">评论者</p>
                                        </td>
                                        <td style="width:53%">
                                            <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo $comment['customer_name'];?></p>
                                        </td>
                                        <td style="width:10%">
                                            <p style="color:#33c8ce;">打分</p>
                                        </td>
                                        <td style="width:20%">
                                            <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo BusinessHelper::translateOrderFeeling($comment['stars']); ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </li>
                            <li style="margin: -10px 0px -10px 0px">
                                <p>意见:<?php echo $comment['comments'];?><p>
                                <p>日期:<?php echo substr($comment['creation_date'], 0, 10 );?><p>
                            </li>
                        </ul>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p style = "font-size:10px;">暂无评论</p>
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
                echo "{ type: 'smallItem', label: '<h5 style=\"color:#01A4B5\">".$actionString."</h5>', shortContent: '<h5 style=\"color:#01A4B5\">".$orderAction['creation_date']."</h5>',},";
            ?>
            <?php } ?>
            <?php if ($lastAction < 10) {?>
            {
                type: 'milestone',
                label: '<h5>买家支付</h5>',
            },
            <?php } ?>
            <?php if ($lastAction < 20) {?>
            {
                type: 'milestone',
                label: '<h5>卖家确认</h5>',
            },
            <?php } ?>
            <?php if ($lastAction < 40) {?>
            {
                type: 'milestone',
                label: '<h5>卖家完成任务</h5>',
            },
            <?php } ?>
            <?php if ($lastAction < 60) {?>
            {
                type: 'milestone',
                label: '<h5>买家确认完成</h5>',
            },
            <?php } ?>
            <?php if ($lastAction < 80) {?>
            {
                type: 'milestone',
                label: '<h5>买家评论</h5>',},
            <?php } ?>
            <?php if ($lastAction < 100) {?>
            {
                type: 'milestone',
                label: '<h5>易知海外向卖家付款</h5>',
            },
            <?php } ?>
            <?php if (BusinessHelper::isCanceledOrder($lastAction)) {?>
            {
                type: 'milestone',
                label: '<h5>易知海外向买家退款</h5>',
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