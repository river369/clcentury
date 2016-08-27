<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
require dirname(__FILE__).'/../../../init.php';
use Addons\OverSea\Model\ServicesBo;
use Addons\OverSea\Model\UsersBo;
use Addons\OverSea\Common\HttpHelper;
if (isset($_GET['islogoff'])){
    unset($_COOKIE["signedUser"]);
}
HttpHelper::saveServerQueryStringVales($_SERVER['QUERY_STRING']);
$userBo = new UsersBo();
$userBo -> index();
$serviceBo = new ServicesBo();
$serviceBo->getServices();
$servicesData= $_SESSION['servicesData'];

$ads = $_SESSION['ads'];
unset($_SESSION['ads']);
$service_type_1_ad = 0;
$service_type_2_ad = 0;
foreach($ads as $key => $ad){
    if ($ad['service_type']==1){
        $service_type_1_ad = 1;
    } else {
        $service_type_2_ad = 1;
    }
}

$servicearea = '地球';
if (isset($_SESSION ['servicearea'])){
    $servicearea = $_SESSION ['servicearea'];
} else if (isset($_SESSION ['userSetting'])){
    $userSetting = $_SESSION ['userSetting'];
    if (isset($userSetting['selected_service_area'])){
        $servicearea = $userSetting['selected_service_area'];
    }
}
$imageurl='http://oss.clcentury.com/yzphoto/advertise/'.$servicearea.'/';

$isDiscover = 1;
?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>易知海外</title>

    <script src="http://oss.clcentury.com/resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="http://oss.clcentury.com/resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="http://oss.clcentury.com/resource/js/rater/rater.min.js"></script>
    <script src="http://oss.clcentury.com/resource/js/camera/jquery.min.js"></script>
    <script src="http://oss.clcentury.com/resource/js/camera/jquery.easing.1.3.js"></script>
    <script src="http://oss.clcentury.com/resource/js/camera/camera.min.js"></script>
    <script src="http://oss.clcentury.com/resource/js/camera/jquery.mobile.customized.min.js"></script>
    <script src="http://oss.clcentury.com/resource/js/business/discover.js"></script>

    <link rel="stylesheet" href="http://oss.clcentury.com/resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="http://oss.clcentury.com/resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="http://oss.clcentury.com/resource/style/camera/camera.css" type="text/css" media="all">
    <link rel="stylesheet" href="http://oss.clcentury.com/resource/style/business/discover.css" type="text/css" media="all">

    <script>
        $(document).ready(function(){
            var i=1;
            for (i = 1; i <= <?php echo count($servicesData);?>; i++) {
                setRate(i, $('#ratevalue' + i).val());
            }
            $("img").error(function () {
                $(this).attr("src", "http://oss.clcentury.com/resource/images/head_default.jpg");
            });
        });
        
        <?php
        $i=0;
        foreach($ads as $key => $ad){
            if ($ad['service_type']==1){
                echo "lyArray[".$i."]='".$ad['service_id']."';";
                $i++;
            }
        }?>
        <?php
        $i=0;
        foreach($ads as $key => $ad){
            if ($ad['service_type']==2){
                echo "lxArray[".$i."]='".$ad['service_id']."';";
                $i++;
            }
        }?>

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

        <div id="serviceType1">
            <?php if ($service_type_1_ad) {?>
                <div class="fluid_container" onclick="goToLYService();">
                    <div class="camera_wrap camera_azure_skin" id="camera_wrap_1" style="margin: 0px 0px 5px 0px" >
                        <?php foreach($ads as $key => $ad){
                            if ($ad['service_type']==1){
                                echo "<div data-src='".$imageurl.$ad['service_type'].'/'.$ad['service_id'].".jpg' data-fx='mosaicReverse'></div>";
                            }
                        }?>
                    </div><!-- #camera_wrap_1 -->
                </div><!-- .fluid_container -->
            <?php } ?>

            <?php
            $itemIndx = -1;
            foreach($servicesData as $key => $serviceData)
            {
                $itemIndex++; ?>
                <div style="margin: -5px 0px -5px 0px">
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
                                                <img class="weui_media_appmsg_thumb" src="http://oss.clcentury.com/yzphoto/pics/<?php echo $serviceData['seller_id']; ?>/<?php echo $serviceData['service_id']; ?>/main.png" height="100%">
                                            </div>
                                        </td>
                                        <td style="73%">
                                            <p style="white-space:pre-wrap;word-break:break-all">卖家:<?php echo $serviceData['seller_name']?></p>
                                            <p style="white-space:pre-wrap;word-break:break-all">简介:<?php echo (isset($serviceData['service_brief']) && !is_null($serviceData['service_brief']))? $serviceData['service_brief']: "";?></p>
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
        <div id="serviceType2">
            <?php if ($service_type_2_ad) {?>
                <div class="fluid_container" id=fc2 onclick="goToLXService();">
                    <div class="camera_wrap camera_azure_skin" id="camera_wrap_2" style="margin: 0px 0px 5px 0px" >
                        <?php foreach($ads as $key => $ad){
                            if ($ad['service_type']==2){
                                echo "<div data-src='".$imageurl.$ad['service_type'].'/'.$ad['service_id'].".jpg' data-fx='mosaicReverse'></div>";
                            }
                        }?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div data-role="content"class="endMsgString"></div>

    <?php include '../common/footer.php';?>
</div>
</body>
</html>