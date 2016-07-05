<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
session_start();
$orderDetail = $_SESSION['orderDetail'];
?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
    <title>易知海外</title>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery-ui-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />

</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="d">
        <h1>评论订单服务</h1>
    </div>

    <div data-role="content">
        <p>感谢您使用<?php echo $orderDetail['seller_name'];?>提供的服务,请对本次服务进行打分和评论</p>
        <form id="submityz" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=customerCommnetOrder">
            <input type="hidden" name="service_id" id="service_id"  value="<?php echo $orderDetail['service_id'];?>"/>
            <input type="hidden" name="order_id" id="order_id"  value="<?php echo $orderDetail['order_id'];?>"/>
            <input type="hidden" name="customer_id" id="customer_id"  value="<?php echo $orderDetail['customer_id'];?>"/>
            <input type="hidden" name="seller_id" id="seller_id"  value="<?php echo $orderDetail['seller_id']; ?>"/>
            <input type="hidden" name="customer_name" id="customer_name"  value="<?php echo $orderDetail['customer_name'];?>"/>
            <input type="hidden" name="seller_name" id="seller_name"  value="<?php echo $orderDetail['seller_name']; ?>"/>
            <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
                <legend>本次服务体验:</legend>
                <input name="star" id="star1" value="1" type="radio">
                <label for="star1">很不满意</label>
                <input name="star" id="star2" value="2" type="radio">
                <label for="star2">不满意</label>
                <input name="star" id="star3" value="3" type="radio">
                <label for="star3">正常</label>
                <input name="star" id="star4" value="4" type="radio">
                <label for="star4">满意</label>
                <input name="star" id="star5" value="5" type="radio" checked="true">
                <label for="star5">非常满意</label>
            </fieldset>

            </br>
            <label for="comments">您的建议:</label>
            <textarea cols="30" rows="8" name="comments" id="comments" data-mini="true"></textarea>
            </br>
            <input type="submit" name="comments_submit" id="comments_submit" value="提交评论">
        </form>
    </div>

    <?php include '../common/footer.php';?>
</div>
</body>
</html>

