<?php
session_start();
require dirname(__FILE__).'/../../../init.php';
use Addons\OverSea\Common\BusinessHelper;

//service info
$serviceData= $_SESSION['serviceData'];
$seller_id = $serviceData['seller_id'];
$service_id = $serviceData['service_id'];
$servicetype = $serviceData['service_type'];
$servicetypeDesc;
if ($servicetype==1){
    $servicetypeDesc = '旅游';
} else if ($servicetype==2){
    $servicetypeDesc = '留学';
}

//service picture info
$objArray;
$objkey='objArray';
if (isset($_SESSION[$objkey])){
    $objArray = $_SESSION[$objkey] ;
}
$imageurl='http://clcentury.oss-cn-beijing.aliyuncs.com/';

//seller info
$sellerData = $_SESSION['sellerData'];
$realnameStatusString = BusinessHelper::translateRealNameStatus($sellerData['status']);

$commentsData = $_SESSION['commentsData'];

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
    <script src="../../resource/js/camera/jquery.min.js"></script>
    <script src="../../resource/js/camera/jquery.easing.1.3.js"></script>
    <script src="../../resource/js/camera/camera.min.js"></script>
    <script src="../../resource/js/camera/jquery.mobile.customized.min.js"></script>


    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/camera/camera.css" type="text/css" media="all">

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
    </style>

    <style>
        body {
            margin: 0;
            padding: 0;
        }
        a {
            color: #09f;
        }
        a:hover {
            text-decoration: none;
        }
        #back_to_camera {
            clear: both;
            display: block;
            height: 80px;
            line-height: 40px;
            padding: 20px;
        }
        .fluid_container {
            margin: 0 auto;
            max-width: 1000px;
            width: 100%;
        }
    </style>

    <script>
        jQuery(function(){
            jQuery('#camera_wrap_1').camera({
                thumbnails: false,
                loader: 'none',
                portrait :false,
                pagination : true,
                height: '450px',
                navigation : false,
                playPause : false,
                transPeriod: 500

            });
        });
    </script>

</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1><?php echo $serviceData['seller_name']; ?>的服务-<?php echo $serviceData['service_name']; ?></h1>
    </div>

    <div role="main" class="ui-content">
        <div data-role="navbar">
            <ul>
                <li><a href="javascript:showServices()" class="ui-btn-active" data-theme="d">服务信息</a></li>
                <li><a href="javascript:showSellers()" data-theme="d">卖家信息</a></li>
                <li><a href="javascript:showComments()" data-theme="d">服务评论</a></li>
            </ul>
        </div><!-- /navbar -->

        <div data-role="content" id="serviceInfo">

            <h4 style="color:steelblue"> 图片:</h4>
            <?php if (sizeof($objArray) > 0) { ?>
                <div class="fluid_container">
                    <div class="camera_wrap camera_azure_skin" id="camera_wrap_1">
                        <?php foreach ($objArray as $obj) { ?>
                        <div data-src="<?php echo $imageurl.$obj; ?>" data-fx='mosaicReverse'></div>
                        <?php } ?>
                    </div><!-- #camera_wrap_1 -->
                </div><!-- .fluid_container -->
            <?php } else { ?>
                <p>未上传图片</p>
            <?php } ?>

            <h4 style="color:steelblue">服务介绍:</h4>
            <p><?php echo $serviceData['description']; ?></p>

            <h4 style="color:steelblue">服务信息:</h4>
            <ul data-role="listview" data-inset="true">
                <li>服务等级: <span class="ui-li-count"><div class="servicerate"/></span></li>
                <input type="hidden" id="serviceratevalue" value="<?php echo $serviceData['stars'];?>">
                <li>服务地点: <span class="ui-li-count"><?php echo $serviceData['service_area']; ?></span></li>
                <li>服务类型: <span class="ui-li-count"><?php echo $servicetypeDesc; ?></span></li>
                <li>服务价格: <span class="ui-li-count">￥<?php echo $serviceData['service_price']; ?>/小时</span></li>
            </ul>

            <?php
            $tags = $serviceData['tag'];
            $tagsArray = explode(',',$tags);
            if (strlen($tags) >0 && count($tagsArray) >0) {
            ?>
            <h4 style="color:steelblue">服务标签:</h4>
            <div class="ui-grid-a">
                <?php
                $loc = 'a';
                foreach ($tagsArray as $tag){ ?>
                    <div class="ui-block-<?php echo $loc;?>"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini"><?php echo $tag;?></a></div>
                <?php
                    $loc = $loc=='a'? 'b' : 'a';
                } ?>
            </div>
            <?php }?>
        </div>


        <div data-role="content" id="sellerInfo">
            <h4 style="color:steelblue">卖家介绍:</h4>
            <p><?php echo $sellerData['description']; ?></p>

            <h4 style="color:steelblue">卖家信息:</h4>
            <ul data-role="listview" data-inset="true">
                <li>卖家等级: <span class="ui-li-count"><div class="sellerrate"/></span></li>
                <li>实名认证: <span class="ui-li-count"><?php echo $realnameStatusString; ?></span></li>
                <input type="hidden" id="sellerratevalue" value="<?php echo $sellerData['stars'];?>">
            </ul>

            <?php
            $tagsSeller = $sellerData['tag'];
            $tagsSellerArray = explode(',',$tagsSeller);
            if (count($tagsSellerArray) >0) {
                ?>
                <h4 style="color:steelblue">卖家标签</h4>
                <div class="ui-grid-a">
                    <?php
                    $loc = 'a';
                    foreach ($tagsSellerArray as $tagSeller){ ?>
                        <div class="ui-block-<?php echo $loc;?>"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini"><?php echo $tagSeller;?></a></div>
                        <?php
                        $loc = $loc=='a'? 'b' : 'a';
                    } ?>
                </div>
            <?php }?>

            <h4 style="color:steelblue">卖家<?php echo $serviceData['seller_name'];?>的主页:</h4>
            <a href="../users/user_profile.php?&sellerid=<?php echo $seller_id;?>" rel="external">点击查看</a>
        </div>

        <div data-role="content" id="commentsInfo">
            <?php if (isset($commentsData) && count($commentsData) >0) {
                foreach ($commentsData as $comment){ ?>
                    <div>
                        <p>评论者:<?php echo $comment['customer_name'];?> 于 <?php echo $comment['creation_date']?><p>
                        <p>服务体验:<?php echo BusinessHelper::translateOrderFeeling($comment['stars']); ?><p>
                        <p>详细意见:<?php echo $comment['comments'];?><p>
                    </div>
                    <hr>
                <?php } ?>
            <?php } else {?>
                <h5>暂无评论</h5>
            <?php } ?>
        </div>
        <div data-theme="c">
            <a href="../../../Controller/AuthUserDispatcher.php?c=submitOrder&$service_id=<?php echo $service_id; ?>" data-theme="c" rel="external" class="ui-shadow ui-btn ui-corner-all ui-mini">购买</a>
        </div>
    </div>

    <?php include '../common/footer.php';?>

</div>
<script>
$(document).ready(function(){
    setRateSeller($('#sellerratevalue').val());
    setRateService($('#serviceratevalue').val());
});
function setRateSeller(star) {
    var options = {
        max_value: 5,
        step_size: 0.5,
        initial_value: star,
    };
    $(".sellerrate").rate(options);
};
function setRateService(star) {
    var options = {
        max_value: 5,
        step_size: 0.5,
        initial_value: star,
    }
    $(".servicerate").rate(options);
};

$('#serviceInfo').show();
$('#sellerInfo').hide();
$('#commentsInfo').hide();

function showServices() {
    $('#serviceInfo').show();
    $('#sellerInfo').hide();
    $('#commentsInfo').hide();
}

function showSellers() {
    $('#serviceInfo').hide();
    $('#sellerInfo').show();
    $('#commentsInfo').hide();
}

function showComments() {
    $('#serviceInfo').hide();
    $('#sellerInfo').hide();
    $('#commentsInfo').show();
}
</script>
</body>
</html>

