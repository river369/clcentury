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

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile.theme-1.4.5.min.css" />
    <script type="text/javascript" src="../../resource/js/jquery/jquery.simplyscroll.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/jquery/jquery.simplyscroll.css" media="all" type="text/css">
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    
    <style type="text/css">
        /* Container DIV - automatically generated */
        .simply-scroll-container {
            position: relative;
        }

        /* Clip DIV - automatically generated */
        .simply-scroll-clip {
            position: relative;
            overflow: hidden;
        }

        /* UL/OL/DIV - the element that simplyScroll is inited on
        Class name automatically added to element */
        .simply-scroll-list {
            overflow: hidden;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .simply-scroll-list li {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .simply-scroll-list li img {
            border: none;
            display: block;
        }

        /* Custom class modifications - adds to / overrides above

        .simply-scroll is default base class */

        /* Container DIV */
        .simply-scroll {
            width: 576px;
            height: 200px;
            margin-bottom: 1em;
        }

        /* Clip DIV */
        .simply-scroll .simply-scroll-clip {
            width: 576px;
            height: 200px;
        }

        /* Explicitly set height/width of each list item */
        .simply-scroll .simply-scroll-list li {
            float: left; /* Horizontal scroll only */
            width: 290px;
            height: 200px;
        }
    </style>

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
            <ul id="scroller">
                <?php foreach ($objArray as $obj) { ?>
                    <li><img src="<?php echo $imageurl.$obj; ?>" width="290" height="200"></li>
                <?php } ?>
            </ul>
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

<script type="text/javascript">
    (function($) {
        $(function() { //on DOM ready
            $("#scroller").simplyScroll();
        });
    })(jQuery);
</script>

</body>
</html>

