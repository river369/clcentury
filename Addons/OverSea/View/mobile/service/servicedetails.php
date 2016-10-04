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
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>易知海外</title>

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="../../resource/js/rater/rater.min.js"></script>
    <script src="../../resource/js/camera/jquery.min.js"></script>
    <script src="../../resource/js/camera/jquery.mobile.customized.min.js"></script>
    <script src="../../resource/js/swiper/swiper.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/swiper/swiper.min.css">

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
        .swiper-container {
            width: 100%;
            height: 200px;
        }
        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;

            /* Center slide text vertically */
            display: -webkit-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            -webkit-justify-content: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            -webkit-align-items: center;
            align-items: center;
        }
        div.rounded-head-image {
            height: 85px;
            width: 85px;
            border-radius: 50%;
            overflow: hidden;
        }
        h5{ color:#01A4B5}
        p{ font-size:14px; white-space:pre-wrap; word-break:break-all}
        label{ color:#01A4B5; font-size:14px;}
        table{ table-layout : fixed; width:100% }
    </style>
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
                    <pre style="white-space:pre-wrap;">服务:<?php echo $serviceData['service_name']; ?></pre>
                    <pre style="white-space:pre-wrap;">卖家:<?php echo $serviceData['seller_name'];?></pre>
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
            <div style="margin: -10px -16px 0px -16px">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($objArray as $obj) {?>
                            <div class="swiper-slide"><img src="<?php echo $imageurl.$obj; ?>"/></div>
                        <?php } ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            <?php } else { ?>
                <p>未上传图片</p>
            <?php } ?>

            <h5 style="margin: 10px 0px -5px 0px">服务内容简介</h5>
            <p><?php echo $serviceData['service_brief']; ?></p>

            <h5 style="margin: 0px 0px -5px 0px">服务内容详细介绍</h5>
            <p><?php echo $serviceData['description']; ?></p>

            <h5 style="margin: 0px 0px -5px 0px">服务信息</h5>
            <ul data-role="listview" data-inset="true" data-theme="f" style="font-size:14px;">
                <li>服务等级 <span class="ui-li-count"><div class="servicerate"/></span></li>
                <input type="hidden" id="serviceratevalue" value="<?php echo $serviceData['stars'];?>">
                <li>服务地点 <span class="ui-li-count"><?php echo $serviceData['service_area']; ?></span></li>
                <li>服务类型 <span class="ui-li-count"><?php echo $servicetypeDesc; ?></span></li>
                <li>服务价格 <span class="ui-li-count">￥<?php echo $serviceData['service_price'];
                        echo  $serviceData['service_price_type'] == 1 ? "/小时":"/次";
                        ?></span></li>
            </ul>

            <?php
            $tags = trim($serviceData['tag']);
            $tagsArray = explode(',',$tags);
            if (strlen($tags) >0 && $tags!='' && count($tagsArray) >0) {
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
            <p><?php echo $sellerData['signature']; ?></p>
            <h5 style="margin: 0px 0px -5px 0px">卖家介绍</h5>
            <p><?php echo $sellerData['description']; ?></p>
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
                        <div style="font-size:10px;" class="ui-block-<?php echo $loc;?>"><p><?php echo $tagSeller;?></p></div>
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
                        <ul data-role="listview" data-inset="true" data-theme="f">
                            <li style="margin: -5px 0px -5px 0px">
                                <table border="0">
                                    <tr>
                                        <td style="width:17%">
                                            <p style="color:#33c8ce;">评论者</p>
                                        </td>
                                        <td style="width:53%">
                                            <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo $comment['customer_name'];?></p>
                                        </td>
                                        <td style="width:10%">
                                            <p style="color:#33c8ce;">打分</p>
                                        </td>
                                        <td style="width:20%">
                                            <p style="white-space:pre-wrap; color:#6f6f6f;"><?php echo BusinessHelper::translateOrderFeeling($comment['stars']); ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </li>
                            <li style="margin: -20px 0px -10px 0px">
                                <p style="white-space:pre-wrap;" >意见:<?php echo (isset($comment['comments']) && !is_null($comment['comments']) && $comment['comments'] !='')? $comment['comments']: "我很懒,没有留下评论。";?><p>
                                <p>日期:<?php echo substr($comment['creation_date'], 0, 10 );?><p>
                            </li>
                        </ul>
                    </div>

                <?php } ?>
            <?php
                    if (count($commentsData) == 1) {
                        echo "<br><br><br><br><br>";
                    }
                } else {?>
                <h5>暂无评论</h5>
                <br><br><br><br><br><br><br><br><br><br>
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

    //initialize swiper when document ready
    var mySwiper = new Swiper ('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 6000,
        autoplayDisableOnInteraction: false
    })
});

function setRateSeller(star) {
    var options = {
        max_value: 5,
        step_size: 0.5,
        initial_value: star,
        readonly:true,
    };
    $(".sellerrate").rate(options);
};
function setRateService(star) {
    var options = {
        max_value: 5,
        step_size: 0.5,
        initial_value: star,
        readonly:true,
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
jQuery(window).load(function () {
    $('.swiper-slide img').each(function() {
        var width = $(this).width();    // 图片实际宽度
        var height = $(this).height();  // 图片实际高度
        // 检查图片是否超宽
        //alert(width+ "  " + height);
        if( height >= 3 * width){
        //if( height > width){
            $(this).css("width", '50%');
        } else {
            $(this).css("width", '100%');
        }
    });
});
</script>
</body>
</html>

