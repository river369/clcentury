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
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>易知海外</title>

    <script src="http://oss.clcentury.com/resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="http://oss.clcentury.com/resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="http://oss.clcentury.com/resource/js/rater/rater.min.js"></script>
    <!--    <script src="http://oss.clcentury.com/resource/js/business/discover.js"></script>-->
    <script src="../../resource/js/business/discover.js"></script>
    <script src="../../resource/js/swiper/swiper.min.js"></script>

    <link rel="stylesheet" href="http://oss.clcentury.com/resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="http://oss.clcentury.com/resource/style/themes/my-theme.min.css" />
    <!--    <link rel="stylesheet" href="http://oss.clcentury.com/resource/style/business/discover.css" type="text/css" media="all">-->
    <link rel="stylesheet" href="../../resource/style/swiper/swiper.min.css">
    <link rel="stylesheet" href="../../resource/style/business/discover.css" type="text/css" media="all">

</head>
<body>
<div data-role="page" data-theme="a" data-title="易知海外">
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
                <div class="swiper-container"  id="swiper-container-type1">
                    <div class="swiper-wrapper">
                        <?php foreach ($ads as $key => $ad) {
                            if ($ad['service_type']==1){?>
                                <div class="swiper-slide"><img width="100%" src="<?php echo $imageurl.$ad['service_type'].'/'.$ad['service_id'].'.jpg'; ?>" onclick="goToService('<?php echo $ad['service_id']; ?>');"/></div>
                            <?php } } ?>
                    </div>
                    <div class="swiper-pagination" id="swiper-pagination-type1"></div>
                </div>
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
                            <a href="../service/servicedetails.php?service_id=<?php echo $serviceData['service_id']; ?>" rel="external">
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
                                <p class="ui-li-aside">￥<?php echo $serviceData['service_price'];
                                    echo  $serviceData['service_price_type'] == 1 ? "/小时":"/次";?></p>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php } ?>
        </div>
        <div id="serviceType2">
            <?php if ($service_type_2_ad) {?>
                <div class="swiper-container"  id="swiper-container-type2">
                    <div class="swiper-wrapper">
                        <?php foreach ($ads as $key => $ad) {
                            if ($ad['service_type']==2){?>
                                <div class="swiper-slide"><img width="100%" src="<?php echo $imageurl.$ad['service_type'].'/'.$ad['service_id'].'.jpg'; ?>"/></div>
                            <?php } } ?>
                    </div>
                    <div class="swiper-pagination" id="swiper-pagination-type2"></div>
                </div>
            <?php } ?>
        </div>
    </div>

    <div data-role="content"class="endMsgString"></div>
    <?php include '../common/footer.php';?>
</div>
<script>
    $(document).ready(function(){
        var i=1;
        for (i = 1; i <= <?php echo count($servicesData);?>; i++) {
            setRate(i, $('#ratevalue' + i).val());
        }
        $("img").error(function () {
            $(this).attr("src", "http://oss.clcentury.com/resource/images/head_default.jpg");
        });
        setServiceType(1);
    });

    var mySwiper1 = new Swiper ('#swiper-container-type1', {
        pagination: '#swiper-pagination-type1',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 6000,
        autoplayDisableOnInteraction: false
    });

    var mySwiper2 = new Swiper ('#swiper-container-type2', {
        pagination: '#swiper-pagination-type2',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 6000,
        autoplayDisableOnInteraction: false
    });
</script>
</body>
</html>