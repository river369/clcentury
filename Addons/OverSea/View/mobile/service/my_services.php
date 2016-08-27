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
    $querystatusString = "已上架的易知服务。重新编辑易知服务后服务进入审核状态,将不能被买家搜索到。";
} else if ($querystatus == 40) {
    $querystatusString = "已被易知海外拒绝的服务。点击'编辑服务'查看拒绝原因。修改服务信息后可以重新提交";
} else if ($querystatus == 100) {
    $querystatusString = "已经被您暂停的易知服务。未购买的买家不能查询到该服务。";
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
    <script src="../../resource/js/rater/rater.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
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
        div.headimag {
            height: 65px;
            width: 65px;
        }
        div.rounded-head-image {
            height: 85px;
            width: 85px;
            border-radius: 50%;
            overflow: hidden;
        }
        h5{ color:#01A4B5}
        p{ font-size:14px; white-space:pre-wrap; word-break:break-all}
        table{ table-layout : fixed; width:100% }
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" data-theme="a" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>我的服务</h1>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <div data-role="navbar">
            <ul>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=myServices&sellerid=<?php echo $sellerid;?>&status=20" <?php echo $querystatus == 20 ? "class='ui-btn-active'" : ''; ?> rel="external" data-theme="a">审核中</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=myServices&sellerid=<?php echo $sellerid;?>&status=60" <?php echo $querystatus == 60 ? "class='ui-btn-active'" : ''; ?> rel="external" data-theme="a">已上架</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=myServices&sellerid=<?php echo $sellerid;?>&status=100" <?php echo $querystatus == 100 ? "class='ui-btn-active'" : ''; ?> rel="external" data-theme="a">已暂停</a></li>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=myServices&sellerid=<?php echo $sellerid;?>&status=40" <?php echo $querystatus == 40 ? "class='ui-btn-active'" : ''; ?> rel="external" data-theme="a">已拒绝</a></li>
            </ul>
        </div>
        <?php if (isset($myServices) && count($myServices) >0) { ?>
            <h6 style="color:grey"><?php echo $querystatusString ?></h6>
            <?php
            $itemIndx = -1;
            foreach($myServices as $key => $service)
            {
                $itemIndex++;
                $serviceid= $service['service_id'];
                $status = $service['status'];
                $serviceType = $service['service_type'];
                $serviceTypeDesc = '旅游';
                if ($serviceType == 2) {
                    $serviceTypeDesc = '留学';
                }
            ?>
            <div style="margin: -5px -9px 0px -9px ">
                <ul data-role="listview" data-inset="true" data-theme="f">
                    <li data-role="list-divider">
                        <p style="margin: -5px 0px -3px 0px;font-size:14px;" ><?php $servicetypeDesc = $service['service_type'] ==1 ? '旅游' : '留学';
                            echo "【".$service['service_area'].":".$servicetypeDesc."】".$service['service_name']?> </p><span class="ui-li-count"><div class="rate<?php echo $itemIndex; ?>"></span>
                        <input type="hidden" id="ratevalue<?php echo $itemIndex; ?>" value="<?php echo $service['stars'];?>"/>
                    </li>
                    <li>
                        <a href="../../../Controller/FreelookDispatcher.php?c=serviceDetails&service_id=<?php echo $service['service_id']; ?>" rel="external">
                            <table style="margin: -8px 0px -8px 0px" border="0">
                                <tr>
                                    <td style="width:27%">
                                        <div class="headimag">
                                            <img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/pics/<?php echo $service['seller_id']; ?>/<?php echo $service['service_id']; ?>/main.png" height="100%">
                                        </div>
                                    </td>
                                    <td style="width:73%">
                                        <p style="white-space:pre-wrap;">卖家:<?php echo $service['seller_name']?></p>
                                        <p style="white-space:pre-wrap;">简介:<?php echo $service['service_brief']?></p>
                                    </td>
                                </tr>
                            </table>
                            <p class="ui-li-aside">￥<?php echo $service['service_price']?>/小时</p>
                        </a>
                    </li>
                    <li>
                        <table border="0" style="margin: -15px 0px -15px 0px">
                            <tr>
                                <td width="60%">
                                    <p style="white-space:pre-wrap; color:#6f6f6f;">服务编号:<?php echo $serviceid;?></p>
                                </td>
                                <td width="40%">
                                    <p style="white-space:pre-wrap; color:#6f6f6f;">创建日期:<?php echo substr($service['creation_date'], 0, 10 )?></p>
                                </td>
                            </tr>
                        </table>
                    </li>
                    <li>
                        <table border="0" style="margin: -15px 0px -15px 0px">
                            <tr>
                                <td width="40%">
                                </td>
                                <td width="20%">
                                    <?php if ($querystatus == 60) { ?>
                                        <a href="#pauseDialog" data-rel="popup" onclick="pausePopup('<?php echo $serviceid; ?>')" class="ui-mini">暂停服务</a>
                                    <?php } else if ($querystatus == 100)  {?>
                                        <a href="#recoverDialog" data-rel="popup" onclick="recoverPopup('<?php echo $serviceid; ?>')" class="ui-mini">恢复服务</a>
                                    <?php }  ?>
                                </td>
                                <td width="20%">
                                    <a href="../../../Controller/AuthUserDispatcher.php?c=publishService&sellerid=<?php echo $sellerid; ?>&service_id=<?php echo $serviceid; ?>" rel="external" class="ui-mini">编辑服务</a>
                                </td>
                                <td width="20%">
                                    <a href="#deleteDialog" data-rel="popup" onclick="deletePopup('<?php echo $serviceid; ?>')" class="ui-mini">删除服务</a>
                                </td>
                            </tr>
                        </table>
                    </li>
                </ul>
            </div>
        <?php }
        } else {?>
            <h5>没有处于该状态的服务</h5>
        <?php } ?>

        <div data-role="popup" id="deleteDialog" data-overlay-theme="a" data-theme="c" style="max-width:400px;">
            <div data-role="header" data-theme="a">
                <h1>删除服务</h1>
            </div>
            <div role="main" class="ui-content">
                <h5 class="ui-title" id="deleteServiceString"></h5>
                <form id="deleteService" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=deleteService&sellerid=<?php echo $sellerid;?>&status=<?php echo $querystatus;?>">
                    <input type="hidden" name="deleteServiceId" id="deleteServiceId" value="">
                    <label for="deletereason"><p>删除原因</p></label>
                    <textarea cols="30" rows="8" name="deletereason" id="deletereason" data-mini="true"></textarea>
                    <div class="ui-grid-a">
                        <div class="ui-block-a">
                            <input type="submit" name="cancelsubmit" id="cancelsubmit" value="删除">
                        </div>
                        <div class="ui-block-b">
                            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow">再想想</a>
                        </div>
                    </div>
                </form>
             </div>
        </div>

        <div data-role="popup" id="pauseDialog" data-overlay-theme="a" data-theme="c" style="max-width:400px;">
            <div data-role="header" data-theme="a">
                <h1>暂停服务</h1>
            </div>
            <div role="main" class="ui-content">
                <h5 class="ui-title" id="pauseServiceString"></h5>
                <form id="pauseService" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=pauseService&sellerid=<?php echo $sellerid;?>&status=100">
                    <input type="hidden" name="pauseServiceId" id="pauseServiceId" value="">
                    <label for="deletereason"><p>暂停原因</p></label>
                    <textarea cols="30" rows="8" name="pausereason" id="pausereason" data-mini="true"></textarea>
                    <div class="ui-grid-a">
                        <div class="ui-block-a">
                            <input type="submit" name="cancelsubmit" id="cancelsubmit" value="暂停">
                        </div>
                        <div class="ui-block-b">
                            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow">再想想</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div data-role="popup" id="recoverDialog" data-overlay-theme="a" data-theme="c" style="max-width:400px;">
            <div data-role="header" data-theme="a">
                <h1>恢复服务</h1>
            </div>
            <div role="main" class="ui-content">
                <h5 class="ui-title" id="recoverServiceString"></h5>
                <form id="recoverService" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=recoverService&sellerid=<?php echo $sellerid;?>&status=60">
                    <input type="hidden" name="recoverServiceId" id="recoverServiceId" value="">
                    <div class="ui-grid-a">
                        <div class="ui-block-a">
                            <input type="submit" name="cancelsubmit" id="cancelsubmit" value="恢复">
                        </div>
                        <div class="ui-block-b">
                            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow">关闭</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../common/footer.php';?>
</div>
<script>
    $(document).ready(function(){
        var i=1
        for (i = 1; i <= <?php echo count($myServices);?>; i++) {
            setRate(i, $('#ratevalue' + i).val());
        }
    });

    function setRate(index, star) {
        var options = {
            max_value: 5,
            step_size: 0.5,
            initial_value: star,
            readonly:true,
        }
        $(".rate" + index).rate(options);
    };
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
    $(document).ready(function(){
        $("img").error(function () {
            $(this).attr("src", "../../resource/images/head_default.jpg");
        });
    });
</script>
</body>
</html>

