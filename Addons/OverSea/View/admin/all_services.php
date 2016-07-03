<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$allServices= $_SESSION['allServices'];

?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>易知海外</title>

    <script src="../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>

    <link rel="stylesheet" href="../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../resource/style/themes/my-theme.min.css" />
    <style type="text/css">
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>审核服务</h1>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <?php
        foreach($allServices as $key => $service)
        {
            $serviceid= $service['service_id'];
            $status = $service['status'];
            $serviceType = $service['service_type'];
            $serviceTypeDesc = '旅游';
            if ($serviceType == 2) {
                $serviceTypeDesc = '留学';
            }
        ?>
        <ul data-role="listview" data-inset="true">
            <li data-role="list-divider">服务编号: <span class="ui-li-count"><?php echo $serviceid;?></span></li>
            <li>
                <a href="../../Controller/AuthUserDispatcher.php?c=publishService&sellerid=<?php echo $service['seller_id'];?>&service_id=<?php echo $serviceid; ?>" rel="external">
                    <img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/<?php echo $service['seller_id'];?>/head.png" alt="">
                    <h2> <?php echo $service['service_area'];?>:<?php echo $serviceTypeDesc;?> </h2>
                    <p style="white-space:pre-wrap;"><?php echo $service['service_name'];?> </p>
                    <p class="ui-li-aside">￥<?php echo $service['service_price'];?>/小时</p>
                </a>
            </li>
            <li data-role="list-divider">创建日期: <span class="ui-li-count"><?php echo $service['creation_date'];?></span></li>
            <li data-theme="c">
                <div class="ui-grid-a">
                    <div class="ui-block-a"><a href="#checkDialog" data-rel="popup" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="checkPopup('<?php echo $serviceid; ?>', 0)">批准服务</a></div>
                    <div class="ui-block-b"><a href="#checkDialog" data-rel="popup" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="checkPopup('<?php echo $serviceid; ?>', 1)">拒绝服务</a></div>
                </div>
            </li>
        </ul>
        <?php } ?>

        <div data-role="popup" id="checkDialog" data-overlay-theme="a" data-theme="a" style="max-width:400px;">
            <div data-role="header" data-theme="a">
                <h1>检查服务</h1>
            </div>
            <div role="main" class="ui-content">
                <h3 class="ui-title" id="checkString">.</h3>
                <form id="checkService" data-ajax="false" method="post" action="../../Controller/AuthUserDispatcher.php?c=checkService&status=20">
                    <input type="hidden" name="serviceId" id="serviceId" value="">
                    <input type="hidden" name="checkaction" id="checkaction" value="">
                    <label for="reason">原因:</label>
                    <textarea cols="30" rows="8" name="checkreason" id="checkreason" data-mini="true"></textarea>
                    <input type="submit" name="cancelsubmit" id="cancelsubmit" value="确定">
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
    function checkPopup(serviceId, type) {
        var messages = "确定";
        if (type == 0){
            messages = messages + "批准";
        } else {
            messages = messages + "拒绝";
        }
        messages = messages + "服务" + serviceId + "?";
        //alert(messages);
        $('#checkString').html(messages);
        $('#serviceId').val(serviceId);
        $('#checkaction').val(type);
        $('#checkDialog').popup('open');
    };
    $(document).ready(function(){
        $("img").error(function () {
            $(this).attr("src", "../resource/images/head_default.jpg");
        });
    });
</script>
</body>
</html>

