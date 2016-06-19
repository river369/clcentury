<?php
session_start();
require dirname(__FILE__).'/../../../init.php';
use Addons\OverSea\Common\BusinessHelper;

//service info
$serviceData= $_SESSION['serviceData'];
$seller_id = $serviceData['seller_id'];
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
    <script src="../../resource/js/camera/jquery.min.js"></script>
    <script src="../../resource/js/camera/jquery.easing.1.3.js"></script>
    <script src="../../resource/js/camera/camera.min.js"></script>
    <script type="text/javascript" src="../../resource/js/camera/jquery.mobile.customized.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile.theme-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/camera/camera.css" type="text/css" media="all">

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
        <h1><?php echo $serviceData['seller_name']; ?>的服务</h1>
    </div>

    <div data-role="content">
        <div style="text-align:center">
            <h2 style="color:steelblue">服务信息</h2>
        </div>
        <h5>服务介绍:</h5>
        <p><?php echo $serviceData['description']; ?></p>
  
        <h5>图片:</h5>
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
 
        <h5>服务信息:</h5>
        <ul data-role="listview" data-inset="true">
            <li><?php echo $serviceData['stars']; ?>星服务 <span class="ui-li-count"><?php echo $serviceData['serve_count']; ?>次咨询</span></li>
            <li>服务地点: <span class="ui-li-count"><?php echo $serviceData['service_area']; ?></span></li>
            <li>服务类型: <span class="ui-li-count"><?php echo $servicetypeDesc; ?></span></li>
            <li>服务价格: <span class="ui-li-count">￥<?php echo $serviceData['service_price']; ?>/小时</span></li>
        </ul>


        <?php
        $tags = $serviceData['tag'];
        $tagsArray = explode(',',$tags);
        if (strlen($tags) >0 && count($tagsArray) >0) {
        ?>
            <h5>服务标签</h5>
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

        <div style="text-align:center">
            <h2 style="color:steelblue">卖家信息</h2>
        </div>

        <h5>卖家介绍:</h5>
        <p><?php echo $sellerData['description']; ?></p>

        <h5>卖家信息:</h5>
        <ul data-role="listview" data-inset="true">
            <li><?php echo $sellerData['stars']; ?>星卖家 <span class="ui-li-count"><?php echo $sellerData['serve_count']; ?>次履行服务</span></li>
            <li>实名认证: <span class="ui-li-count"><?php echo $realnameStatusString; ?></span></li>
        </ul>


        <?php
        $tagsSeller = $sellerData['tag'];
        $tagsSellerArray = explode(',',$tagsSeller);
        if (count($tagsSellerArray) >0) {
            ?>
            <h5>卖家标签</h5>
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

        <div style="text-align:center">
            <h2 style="color:steelblue">服务评论</h2>
        </div>
        <?php if (isset($commentsData) && count($commentsData) >0) {
            foreach ($commentsData as $comment){ ?>
                <div>
                    <p>评论者:<?php echo $comment['customer_name'];?> 于 <?php echo $comment['creation_date']?><p>
                    <p>评分等级:<?php echo $comment['stars'];?>星<p>
                    <p>详细意见:<?php echo $comment['comments'];?><p>
                </div>
                <hr>
            <?php } ?>
        <?php } else {?>
            <h5>暂无评论</h5>
        <?php } ?>

    </div>

    <div data-role="footer" data-position="fixed">
        <div data-role="navbar">
            <ul>
                <li><a href="../../../Controller/AuthUserDispatcher.php?c=submitOrder&$service_id=<?php echo $seller_id; ?>" rel="external" class="ui-btn-active">购买</a></li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>

