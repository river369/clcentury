<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
$startTime = microtime(true)*1000;
session_start();
require dirname(__FILE__).'/../../../init.php';
use Addons\OverSea\Model\ServicesBo;
use Addons\OverSea\Model\UsersBo;
use Addons\OverSea\Common\HttpHelper;
use Addons\OverSea\Common\Logs;

HttpHelper::saveServerQueryStringVales($_SERVER['QUERY_STRING']);
$userBo = new UsersBo();
$userBo -> index();
$serviceBo = new ServicesBo();
$serviceBo->getServices();
$servicesData= $_SESSION['servicesData'];

$servicearea = '地球';
if (isset($_SESSION ['servicearea'])){
    $servicearea = $_SESSION ['servicearea'];
} else if (isset($_SESSION ['userSetting'])){
    $userSetting = $_SESSION ['userSetting'];
    if (isset($userSetting['selected_service_area'])){
        $servicearea = $userSetting['selected_service_area'];
    }
}
$periodTime = microtime(true)*1000 - $startTime;
Logs::writeClcLog("rtt,discover,".$periodTime);
$isDiscover = 1;
?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>易知海外</title>

    <script type="text/javascript">
        document.write("<scr"+"ipt src=\"../../resource/js/jquery/jquery-1.11.1.min.js\"></sc"+"ript>");
        document.write("<scr"+"ipt src=\"../../resource/js/jquery/jquery.mobile-1.4.5.min.js\"></sc"+"ript>");
        document.write("<scr"+"ipt src=\"../../resource/js/rater/rater.min.js\"></sc"+"ript>");
        document.write("<scr"+"ipt src=\"../../resource/js/camera/jquery.min.js\"></sc"+"ript>");
        document.write("<scr"+"ipt src=\"../../resource/js/camera/jquery.easing.1.3.js\"></sc"+"ript>");
        document.write("<scr"+"ipt src=\"../../resource/js/camera/camera.min.js\"></sc"+"ript>");
        document.write("<scr"+"ipt src=\"../../resource/js/camera/jquery.mobile.customized.min.js\"></sc"+"ript>");
        document.write("<scr"+"ipt src=\"../../resource/js/business/discover.js\"></sc"+"ript>");
    </script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/camera/camera.css" type="text/css" media="all">
    <link rel="stylesheet" href="../../resource/style/business/discover.css" type="text/css" media="all">
    
    <script>
        $(document).ready(function(){
            var i=1;
            for (i = 1; i <= <?php echo count($servicesData);?>; i++) {
                setRate(i, $('#ratevalue' + i).val());
            }
            $("img").error(function () {
                $(this).attr("src", "../../resource/images/head_default.jpg");
            });
        });
    </script>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" data-theme="a" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <a href="../../../Controller/FreelookDispatcher.php?c=getCities" rel="external" data-icon="home" data-shadow="false"><?php echo $servicearea; ?></a>
        <h1>发现</h1>
        <a href="../../../Controller/AuthUserDispatcher.php?c=searchMainPage" rel="external" data-icon="search" data-shadow="false">搜索</a>
    </div>

    <div id="discoverMain" role="main" class="ui-content jqm-content jqm-fullwidth" style="margin: 0px -3px 0px -3px ">

        <div data-role="navbar" style="margin: -5px 0px 10px 0px ">
            <ul>
                <li><a href="#" rel="external" class="ui-btn-active" onclick="setServiceType(1)">旅游</a></li>
                <li><a href="#" rel="external" onclick="setServiceType(2)">留学</a></li>
            </ul>
        </div><!-- /navbar -->

        <div class="fluid_container" onclick="alert();">
            <div class="camera_wrap camera_azure_skin" id="camera_wrap_1" style="margin: 0px 0px 5px 0px" >
                <div data-src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/pics/57790fb728713607/578d004e91059576/20160719001406_1.jpg" data-fx='mosaicReverse' onclick="alert(1);">
                </div>
                <div data-src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/pics/57790fb728713607/578d004e91059576/20160719001407_2.jpg" data-fx='mosaicReverse'>
                </div>
            </div><!-- #camera_wrap_1 -->
        </div><!-- .fluid_container -->

        <div id="serviceType1">
            <?php
            $itemIndx = -1;
            foreach($servicesData as $key => $serviceData)
            {
                $itemIndex++; ?>
                <div style="margin: -5px 0px -5px 0px ">
                    <ul data-role="listview" data-inset="true" data-theme="f">
                        <li data-role="list-divider">
                            <p style="margin: -5px 0px -3px 0px;font-size:14px;" >
                                <?php echo "【".$serviceData['service_area']."】".$serviceData['service_name'];?>
                            </p>
                            <span class="ui-li-count"><div class="rate<?php echo $itemIndex; ?>"></div></span>
                            <input type="hidden" id="ratevalue<?php echo $itemIndex; ?>" value="<?php echo $serviceData['stars'];?>"/>
                        </li>
                        <li style="margin: -5px 0px -5px 0px">
                            <a href="../../../Controller/FreelookDispatcher.php?c=serviceDetails&service_id=<?php echo $serviceData['service_id']; ?>" rel="external">
                                <table border="0" style="margin: -8px 0px -8px 0px">
                                    <tr>
                                        <td style="width:27%">
                                            <div class="headimage">
                                                <img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/pics/<?php echo $serviceData['seller_id']; ?>/<?php echo $serviceData['service_id']; ?>/main.png" height="100%">
                                            </div>
                                        </td>
                                        <td style="73%">
                                            <p style="white-space:pre-wrap;word-break:break-all">卖家:<?php echo $serviceData['seller_name']?></p>
                                            <p style="white-space:pre-wrap;word-break:break-all">简介:<?php echo $serviceData['service_brief']?></p>
                                        </td>
                                    </tr>
                                </table>
                                <p class="ui-li-aside">￥<?php echo $serviceData['service_price']?>/小时</p>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php } ?>

        </div>
        <div id="serviceType2"></div>
    </div>

    <div data-role="content"class="endMsgString"></div>

    <?php include '../common/footer.php';?>
</div>
</body>
</html>