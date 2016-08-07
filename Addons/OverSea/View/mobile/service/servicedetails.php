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
$isDiscover = 1;
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
        div.headimage {
            height: 65px;
            width: 65px;
        }
        div.rounded-head-image {
            height: 85px;
            width: 85px;
            border-radius: 50%;
            overflow: hidden;
        }
        h5{ color:#33c8ce}
        p{ font-size:14px;}
        label{ color:#33c8ce; font-size:14px;}
        table{ table-layout : fixed; width:100% }
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
<div data-url="panel-fixed-page1" data-role="page" data-theme="a" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>详细服务信息</h1>
    </div>

    <div>
        <table border="0" bgcolor="#f6f6f6">
            <tr>
                <td style="width:10%"></td>
                <td style="width:30%">
                    <div class="rounded-head-image" style="margin: 0px -20px 0px 0px">
                        <img src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/pics/<?php echo $seller_id;?>/<?php echo $service_id;?>/main.png" height="100%" alt="">
                    </div>
                </td>
                <td style="width:60%">
                    <h3>卖家:<?php echo $serviceData['seller_name'];?></h3>
                    <pre style="white-space:pre-wrap;">服务:<?php echo $serviceData['service_name']; ?></pre>
                </td>

            </tr>
        </table>
    </div>

    <div role="main" class="ui-content">
        <div data-role="navbar">
            <ul>
                <li><a href="#" onclick="showServices()" class="ui-btn-active">服务信息</a></li>
                <li><a href="#" onclick="showSellers()" >卖家信息</a></li>
                <li><a href="#" onclick="showComments()" >服务评论</a></li>
            </ul>
        </div><!-- /navbar -->

        <div data-role="content" id="serviceInfo">
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

            <h5 style="margin: 0px 0px -5px 0px">服务内容简介</h5>
            <p>&emsp;&emsp;<?php echo $serviceData['service_brief']; ?></p>

            <h5 style="margin: 0px 0px -5px 0px">服务内容详细介绍</h5>
            <p>&emsp;&emsp;<?php echo $serviceData['description']; ?></p>

            <h5 style="margin: 0px 0px -5px 0px">服务信息</h5>
            <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                <li>服务等级 <span class="ui-li-count"><div class="servicerate"/></span></li>
                <input type="hidden" id="serviceratevalue" value="<?php echo $serviceData['stars'];?>">
                <li>服务地点 <span class="ui-li-count"><?php echo $serviceData['service_area']; ?></span></li>
                <li>服务类型 <span class="ui-li-count"><?php echo $servicetypeDesc; ?></span></li>
                <li>服务价格 <span class="ui-li-count">￥<?php echo $serviceData['service_price']; ?>/小时</span></li>
            </ul>

            <?php
            $tags = $serviceData['tag'];
            $tagsArray = explode(',',$tags);
            if (strlen($tags) >0 && count($tagsArray) >0) {
            ?>
            <h5  style="margin: 0px 0px 3px 0px">服务标签</h5>
            <div class="ui-grid-b">
                <?php
                $loc = 'a';
                foreach ($tagsArray as $tag){ ?>
                    <div style="font-size:10px;" class="ui-block-<?php echo $loc;?>"><a href="../../../Controller/AuthUserDispatcher.php?c=searchByKeyWord&keyWord=<?php echo $tag;?>" rel="external" data-theme="d"  data-role="button"><?php echo $tag;?></a></div>
                <?php
                    if ($loc=='a') {
                        $loc = 'b';
                    } else if ($loc=='b'){
                        $loc = 'c';
                    } else {
                        $loc = 'a';
                    }
                } ?>
            </div>
            <?php }?>
        </div>


        <div data-role="content" id="sellerInfo">
            <h5 style="margin: 0px 0px -5px 0px">个性签名</h5>
            <p>&emsp;&emsp;<?php echo $sellerData['signature']; ?></p>
            <h5 style="margin: 0px 0px -5px 0px">卖家介绍</h5>
            <p>&emsp;&emsp;<?php echo $sellerData['description']; ?></p>
            <h5 style="margin: 0px 0px -5px 0px">卖家信息</h5>
            <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                <li>卖家等级 <span class="ui-li-count"><div class="sellerrate"/></span></li>
                <li>实名认证 <span class="ui-li-count"><?php echo $realnameStatusString; ?></span></li>
                <input type="hidden" id="sellerratevalue" value="<?php echo $sellerData['stars'];?>">
            </ul>

            <?php
            $tagsSeller = $sellerData['tag'];
            $tagsSellerArray = explode(',',$tagsSeller);
            if (count($tagsSellerArray) >0) {
                ?>
                <h5 style="margin: 0px 0px 5px 0px">卖家标签</h5>
                <div class="ui-grid-b">
                    <?php
                    $loc = 'a';
                    foreach ($tagsSellerArray as $tagSeller){ ?>
                        <div style="font-size:10px;" class="ui-block-<?php echo $loc;?>"><a href="../../../Controller/AuthUserDispatcher.php?c=searchByKeyWord&keyWord=<?php echo $tagSeller;?>" rel="external" data-theme="d"  data-role="button"><?php echo $tagSeller;?></a></div>
                        <?php
                        if ($loc=='a') {
                            $loc = 'b';
                        } else if ($loc=='b'){
                            $loc = 'c';
                        } else {
                            $loc = 'a';
                        }
                    } ?>
                </div>
            <?php }?>
            <h5 style="margin: 10px 0px 5px 0px">卖家<?php echo $serviceData['seller_name'];?>的主页</h5>
            <div class="ui-grid-b">
                <div style="font-size:10px;" class="ui-block-a"><a href="../users/user_profile.php?sellerid=<?php echo $seller_id;?>" rel="external" data-theme="d"  data-role="button">点击查看</a></div>
            </div>
        </div>

        <div data-role="content" id="commentsInfo">
            <?php if (isset($commentsData) && count($commentsData) >0) {
                foreach ($commentsData as $comment){ ?>
                    <div>
                        <ul data-role="listview" data-inset="true" data-theme="f" style="margin: -5px 0px 2px 0px">
                            <li data-role="list-divider">评论者:<?php echo $comment['customer_name'];?> <span class="ui-li-count"><?php echo BusinessHelper::translateOrderFeeling($comment['stars']); ?></span></li>
                            <li style="margin: -10px 0px -10px 0px">
                                <p>意见:<?php echo $comment['comments'];?><p>
                                <p>日期:<?php echo substr($comment['creation_date'], 0, 10 );?><p>
                            </li>
                        </ul>
                    </div>
                    <hr>
                <?php } ?>
            <?php } else {?>
                <h5>暂无评论</h5>
            <?php } ?>
        </div>
        <div>
            <a href="../../../Controller/AuthUserDispatcher.php?c=submitOrder&$service_id=<?php echo $service_id; ?>" data-theme="c" data-role="button" rel="external">购买</a>
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

