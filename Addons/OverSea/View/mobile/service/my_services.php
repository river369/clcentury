<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$myServices= $_SESSION['myServices'];
$sellerid = $_SESSION['sellerId'] ;
$querystatus = $_SESSION['querystatus'];

$querystatusString = "感谢您发布易知服务,易知海外将在24小时内完成审核。";
if ($querystatus == 60) {
    $querystatusString = "已上架的易知服务。请注意,重新编辑易知服务后服务进入审核状态,将不能被买家搜索到。";
} else if ($querystatus == 40) {
    $querystatusString = "点击服务查看拒绝原因";
} else if ($querystatus == 100) {
    $querystatusString = "已经被您暂停的易知服务。未购买的买家不能查询到该服务。";
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

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <style type="text/css">
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>我的服务</h1>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <div data-role="navbar">
            <ul>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=myServices&sellerid=<?php echo $sellerid;?>&status=20" <?php echo $querystatus == 20 ? "class='ui-btn-active'" : ''; ?> rel="external">审核中</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=myServices&sellerid=<?php echo $sellerid;?>&status=60" <?php echo $querystatus == 60 ? "class='ui-btn-active'" : ''; ?> rel="external">已上架</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=myServices&sellerid=<?php echo $sellerid;?>&status=100" <?php echo $querystatus == 100 ? "class='ui-btn-active'" : ''; ?> rel="external">已暂停</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=myServices&sellerid=<?php echo $sellerid;?>&status=40" <?php echo $querystatus == 40 ? "class='ui-btn-active'" : ''; ?> rel="external">已拒绝</a></li>
            </ul>
        </div>
        <?php if (isset($myServices) && count($myServices) >0) { ?>
            <h6 style="color:grey"><?php echo $querystatusString ?></h6>
            <?php
            foreach($myServices as $key => $service)
            {
                $serviceid= $service['id'];
                $status = $service['status'];
                $serviceType = $service['service_type'];
                $serviceTypeDesc = '旅游';
                if ($serviceType == 2) {
                    $serviceTypeDesc = '留学';
                }
            ?>
            <ul data-role="listview" data-inset="true">
                <li data-role="list-divider">服务编号: <span class="ui-li-count"><?php echo $service['id'];?></span></li>
                <li>
                    <a href="../../../Controller/AuthUserDispatcher.php?c=publishService&sellerid=<?php echo $sellerid; ?>&service_id=<?php echo $serviceid; ?>" rel="external">
                        <img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/<?php echo $sellerid;?>/head.png?t=<?php echo rand(0,10000); ?>" alt="">
                        <h2> <?php echo $service['service_area'];?>:<?php echo $serviceTypeDesc;?> </h2>
                        <p style="white-space:pre-wrap;"><?php echo $service['service_name'];?> </p>
                        <p class="ui-li-aside">￥<?php echo $service['service_price'];?>/小时</p>
                    </a>
                </li>
                <li data-role="list-divider">创建日期: <span class="ui-li-count"><?php echo $service['creation_date'];?></span></li>
                <li data-theme="c">
                    <div class="ui-grid-a">
                        <div class="ui-block-a">
                        <?php if ($querystatus == 60) { ?>
                            <a href="#pauseDialog" data-rel="popup" class="ui-mini" onclick="pausePopup('<?php echo $serviceid; ?>')">暂停服务</a>
                        <?php } else if ($querystatus == 100)  {?>
                            <a href="#recoverDialog" data-rel="popup" class="ui-mini" onclick="recoverPopup('<?php echo $serviceid; ?>')">恢复服务</a>
                        <?php }  ?>
                        </div>
                        <div class="ui-block-b" align="right"><a href="#deleteDialog" data-rel="popup" class="ui-mini" onclick="deletePopup('<?php echo $serviceid; ?>')">删除服务</a></div>
                    </div>
                </li>
            </ul>
        <?php }
        } else {?>
            <h4 style="color:steelblue">没有处于该状态的服务</h4>
        <?php } ?>

        <div data-role="popup" id="deleteDialog" data-overlay-theme="a" data-theme="a" style="max-width:400px;">
            <div data-role="header" data-theme="a">
                <h1>删除服务</h1>
            </div>
            <div role="main" class="ui-content">
                <h3 class="ui-title" id="deleteServiceString"></h3>
                <form id="deleteService" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=deleteService&sellerid=<?php echo $sellerid;?>&status=<?php echo $querystatus;?>">
                    <input type="hidden" name="deleteServiceId" id="deleteServiceId" value="">
                    <label for="deletereason">删除原因:</label>
                    <textarea cols="30" rows="8" name="deletereason" id="deletereason" data-mini="true"></textarea>
                    <input type="submit" name="cancelsubmit" id="cancelsubmit" value="删除">
                </form>
                <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
            </div>
        </div>

        <div data-role="popup" id="pauseDialog" data-overlay-theme="a" data-theme="a" style="max-width:400px;">
            <div data-role="header" data-theme="a">
                <h1>暂停服务</h1>
            </div>
            <div role="main" class="ui-content">
                <h3 class="ui-title" id="pauseServiceString"></h3>
                <form id="pauseService" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=pauseService&sellerid=<?php echo $sellerid;?>&status=100">
                    <input type="hidden" name="pauseServiceId" id="pauseServiceId" value="">
                    <label for="deletereason">暂停原因:</label>
                    <textarea cols="30" rows="8" name="pausereason" id="pausereason" data-mini="true"></textarea>
                    <input type="submit" name="cancelsubmit" id="cancelsubmit" value="暂停">
                </form>
                <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
            </div>
        </div>
        
        <div data-role="popup" id="recoverDialog" data-overlay-theme="a" data-theme="a" style="max-width:400px;">
            <div data-role="header" data-theme="a">
                <h1>恢复服务</h1>
            </div>
            <div role="main" class="ui-content">
                <h3 class="ui-title" id="recoverServiceString"></h3>
                <form id="recoverService" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=recoverService&sellerid=<?php echo $sellerid;?>&status=60">
                    <input type="hidden" name="recoverServiceId" id="recoverServiceId" value="">
                    <input type="submit" name="cancelsubmit" id="cancelsubmit" value="恢复">
                </form>
                <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
            </div>
        </div>
    </div>

    <?php include '../common/footer.php';?>
</div>
<script>
    function deletePopup(serviceId) {
        var messages = "确定删除服务" + serviceId + "?";
        $('#deleteServiceString').html(messages);
        $('#deleteServiceId').val(serviceId);
        $('#deleteDialog').popup('open');
    };
    function pausePopup(serviceId) {
        var messages = "确定暂定服务" + serviceId + "?";
        $('#pauseServiceString').html(messages);
        $('#pauseServiceId').val(serviceId);
        $('#pauseDialog').popup('open');
    };
    function recoverPopup(serviceId) {
        var messages = "确定恢复服务" + serviceId + "?";
        $('#recoverServiceString').html(messages);
        $('#recoverServiceId').val(serviceId);
        $('#recoverDialog').popup('open');
    };
    $(document).ready(function(){
        $("img").error(function () {
            $(this).attr("src", "../../resource/images/head_default.jpg");
        });
    });
</script>
</body>
</html>

