<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$serviceData= $_SESSION['serviceData'];
$sellerName = $serviceData['seller_name'];
$serviceArea = $serviceData['service_area'];
$serviceType=$serviceData['service_type'];
$servicetypeDesc;
if ($serviceType==1){
    $serviceTypeDesc = '旅游';
} else if ($serviceType==2){
    $serviceTypeDesc = '留学';
}
$price = $serviceData['service_price'];
$signedUser = $_SESSION['signedUser'];
?>
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
        <h1>购买<?php echo $sellerName; ?>的服务</h1>
    </div>

    <form id="submitorder" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=createOrder">
        <div data-role="content">
            <h5>服务信息:</h5>
            <ul data-role="listview" data-inset="true">
                <li>服务地点: <span class="ui-li-count"><?php echo $serviceArea; ?></span></li>
                <li>服务类型: <span class="ui-li-count"><?php echo $serviceTypeDesc; ?></span></li>
                <li>服务价格: <span class="ui-li-count">￥<?php echo $price; ?>/小时</span></li>
            </ul>
            <h5>咨询时长:</h5>
            <div id="div-slider">
                <input type="range" name="servicehours" id="servicehours" value="1" min="0" max="100" data-highlight="true" />
            </div>

            <h5>总计(￥):</h5>
            <div id="totalmoney">
                <input type="text" name="servicetotalfee" id="servicetotalfee"  value="<?php echo $price; ?>"/>
            </div>

            <h5>咨询话题:</h5>
            <textarea cols="30" rows="8" name="requestmessage" id="requestmessage" data-mini="true"></textarea>

            <input type="hidden" name="customerid" id="customerid"  value="<?php echo $signedUser;?>"/>
            <input type="hidden" name="sellerid" id="sellerid"  value="<?php echo $serviceData['seller_id']; ?>"/>
            <input type="hidden" name="sellername" id="sellername"  value="<?php echo $sellerName; ?>"/>
            <input type="hidden" name="servicearea" id="servicearea"  value="<?php echo $serviceArea; ?>"/>
            <input type="hidden" name="servicetype" id="servicetype"  value="<?php echo $serviceType; ?>"/>
            <input type="hidden" name="serviceprice" id="serviceprice"  value="<?php echo $price; ?>"/>
        </div>

        <div data-role="footer" data-position="fixed" data-theme="c">
            <div data-role="navbar">
                <ul>
                    <li><a href="#" onclick="JavaScript:$('#submitorder').submit();" rel="external" >去付款</a></li>
                </ul>
            </div>
        </div>
</div>
<script type="text/javascript">
    $("#div-slider").change(function() {
        var slider_value = $("#servicehours").val();
        var service_price = <?php echo $price;?>;
        html = '<input type="text" name="servicetotalfee" id="servicetotalfee"  value="' + slider_value * service_price +'"/>';
        $("#totalmoney").html(html);
    });
</script>

</body>
</html>


