<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$serviceData= $_SESSION['serviceData'];
$serviceId = $serviceData['service_id'];
$serviceName = $serviceData['service_name'];
$sellerId = $serviceData['seller_id'];
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
$isMine = 1;
?>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>易知海外</title>
    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="../../resource/js/validation/jquery.validate.min.js"></script>
    <script src="../../resource/js/validation/localization/messages_zh.min.js"></script>
    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/validation/validation.css" />
    <style>
        h5{ color:#01A4B5}
        p{ font-size:14px;}
        table{ table-layout : fixed; width:100% }
    </style>
</head>
<body>

<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>购买<?php echo $sellerName; ?>的服务</h1>
    </div>

    <form id="submitorder" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=createOrder">
        <div data-role="content">
            <h5>服务信息</h5>
            <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                <li>服务编号: <span class="ui-li-count"><?php echo $serviceId; ?></span></li>
                <li>服务名称: <span class="ui-li-count"><?php echo $serviceName; ?></span></li>
                <li>服务地点: <span class="ui-li-count"><?php echo $serviceArea; ?></span></li>
                <li>服务类型: <span class="ui-li-count"><?php echo $serviceTypeDesc; ?></span></li>
                <li>服务价格: <span class="ui-li-count">￥<?php echo $price; ?>/小时</span></li>
            </ul>
            <h5>咨询时长</h5>
            <table style="margin: -8px 0px -8px 0px" border="0">
                <tr>
                    <td style="width:20%">
                        <a href="#" onclick="minus1();" data-theme="b"  data-role="button" rel="external">
                            <img id="minus" src="../../resource/images/minus.png" width="25" height="25" class="add"/>
                        </a>
                    </td>
                    <td style="width:20%">
                        <input id="service_hours" name="service_hours" type="number" value="1"/>
                    </td>
                    <td style="width:20%">
                        <a href="#" onclick="add1();" data-theme="b"  data-role="button" rel="external">
                         <img id="add" src="../../resource/images/inc.png" width="25" height="25" class="minus"/>
                        </a>
                    </td>
                    <td style="width:40%"></td>
                </tr>
            </table>

            <h5>总计(￥)</h5>
            <div id="totalmoney">
                <input type="text" name="service_total_fee" id="service_total_fee" readonly="true" value="<?php echo $price; ?>"/>
            </div>

            <h5>咨询话题</h5>
            <div id="totalmoney">
                <textarea cols="30" rows="8" name="request_message" id="request_message" data-mini="true"></textarea>
            </div>

            <a href="./customer_agreement.html" class="ui-controlgroup-label" data-transition="slip"><h5>点击阅读购买服务声明</h5></a>

            <a href="#rulePopup" data-rel="popup" class="ui-controlgroup-label"></a>
            <input name="agree" id="agree" data-mini="true" type="checkbox" data-theme="e">
            <label for="agree">我同意上述服务声明</label>

            <input type="hidden" name="service_id" id="service_id"  value="<?php echo $serviceId;?>"/>
            <input type="hidden" name="service_name" id="service_name"  value="<?php echo $serviceName;?>"/>
            <input type="hidden" name="customer_id" id="customer_id"  value="<?php echo $signedUser;?>"/>
            <input type="hidden" name="seller_id" id="seller_id"  value="<?php echo $sellerId; ?>"/>
            <input type="hidden" name="seller_name" id="seller_name"  value="<?php echo $sellerName; ?>"/>
            <input type="hidden" name="service_area" id="service_area"  value="<?php echo $serviceArea; ?>"/>
            <input type="hidden" name="service_type" id="service_type"  value="<?php echo $serviceType; ?>"/>
            <input type="hidden" name="service_price" id="service_price"  value="<?php echo $price; ?>"/>

            <div>
                <a href="#" onclick="JavaScript:$('#submitorder').submit();" data-theme="c" data-role="button" rel="external">去付款</a>
            </div>
        </div>

        <?php include '../common/footer.php';?>
</div>
<script type="text/javascript">
    $("#service_hours").change(function() {
        var slider_value = $("#service_hours").val();
        var service_price = <?php echo $price;?>;
        html = '<input type="text" name="service_total_fee" id="service_total_fee" readonly="true"  value="' + slider_value * service_price +'"/>';
        $("#totalmoney").html(html);
        $("#totalmoney").trigger('create')
    });

    function add1() {
        $('#service_hours').val(Number($('#service_hours').val())+1);
        $('#service_hours').change();
    };
    function minus1() {
        var num = Number($('#service_hours').val())-1;
        if (num>0){
            $('#service_hours').val(Number($('#service_hours').val())-1);
            $('#service_hours').change();
        }
    };
</script>
<script>
    $( "#panel-fixed-page1" ).on( "pageinit", function() {
        $( "#submitorder" ).validate({
            rules: {
                service_hours:{
                    required: true,
                    number : true
                },
                agree: "required"
            },
            messages: {
                service_hours:{
                    required: "咨询时长不能为空",
                    number : "必须输入合法的数字",
                },
                agree: "请接受我们的声明"
            },
            errorPlacement: function( error, element ) {
                error.insertAfter( element.parent() );
            }
        });
    });
    $(document).ready(function(){
        $('#service_hours').change();
    });
</script>

</body>
</html>


