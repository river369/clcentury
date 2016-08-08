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
use Addons\OverSea\Common\HttpHelper;
use Addons\OverSea\Common\BusinessHelper;

HttpHelper::saveServerQueryStringVales($_SERVER['QUERY_STRING']);
$serviceBo = new ServicesBo();
$serviceBo->getSellerPublishedServices();
$servicesData= $_SESSION['servicesData'];

$serviceBo->getCurrentSellerInfo(HttpHelper::getVale('sellerid'));
$sellerData = $_SESSION['sellerData'] ;
$realnameStatusString = BusinessHelper::translateRealNameStatus($sellerData['status']);
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
    <link rel="stylesheet" href="../../resource/style/cropper/main.css" />

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
        h5{ color:#33c8ce}
        p{ font-size:14px;}
        table{ table-layout : fixed; width:100% }
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>个人主页</h1>
    </div>

    <div>
        <table bgcolor="#f6f6f6" border="0">
            <tr>
                <td style="width:10%"></td>
                <td style="width:28%">
                    <div class="rounded-head-image" style="margin: 0px -20px 0px 0px">
                        <img src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/<?php echo $sellerData['user_id'];?>/head.png" height="100%" alt="">
                    </div>
                </td>
                <td style="width:60%">
                    <h3><?php echo $sellerData['name'];?></h3>
                    <pre style="white-space:pre-wrap;"><?php echo $sellerData['signature'];?></pre>
                </td>
                <td style="width:2%"></td>
            </tr>
        </table>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">

        <h5>卖家介绍:</h5>
        <p>&emsp;&emsp;<?php echo $sellerData['description']; ?></p>

        <h5>卖家信息:</h5>
        <ul data-role="listview" data-inset="true" data-theme="f">
            <li>卖家等级: <span class="ui-li-count"><div class="sellerrate"/></span></li>
            <li>实名认证: <span class="ui-li-count"><?php echo $realnameStatusString; ?></span></li>
            <input type="hidden" id="sellerratevalue" value="<?php echo $sellerData['stars'];?>">
        </ul>

        <?php
        $tagsSeller = $sellerData['tag'];
        $tagsSellerArray = explode(',',$tagsSeller);
        if (count($tagsSellerArray) >0) {
            ?>
            <h5>卖家标签</h5>
            <div class="ui-grid-b">
                <?php
                $loc = 'a';
                foreach ($tagsSellerArray as $tagSeller){ ?>
                    <div style="font-size:10px;" class="ui-block-<?php echo $loc;?>"><a href="../../../Controller/AuthUserDispatcher.php?c=searchByKeyWord&keyWord=<?php echo $tagSeller;?>" rel="external" data-theme="d"  data-role="button" ><?php echo $tagSeller;?></a></div>
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

        <h5>卖家全部服务:</h5>
        <?php
        $itemIndx = -1;
        foreach($servicesData as $key => $serviceData)
        {
            $itemIndex++; ?>
            <div style="margin: -5px -9px 0px -9px ">
                <ul data-role="listview" data-inset="true" data-theme="f">
                    <li data-role="list-divider">
                        <p style="margin: -5px 0px -3px 0px;font-size:14px;" ><?php $servicetypeDesc = $serviceData['service_type'] ==1 ? '旅游' : '留学';
                        echo "【".$serviceData['service_area'].":".$servicetypeDesc."】".$serviceData['service_name']?> </p><span class="ui-li-count"><div class="rate<?php echo $itemIndex; ?>"></span>
                        <input type="hidden" id="ratevalue<?php echo $itemIndex; ?>" value="<?php echo $serviceData['stars'];?>"/>
                    </li>
                    <li>
                        <a href="../../../Controller/FreelookDispatcher.php?c=serviceDetails&service_id=<?php echo $serviceData['service_id']; ?>" rel="external">
                            <table style="margin: -8px 0px -8px 0px" border="0">
                                <tr>
                                    <td style="width:27%">
                                        <div class="headimag">
                                            <img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/pics/<?php echo $serviceData['seller_id']; ?>/<?php echo $serviceData['service_id']; ?>/main.png" height="100%">
                                        </div>
                                    </td>
                                    <td style="width:73%">
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

    <?php include '../common/footer.php';?>
</div>
<script>
    $(document).ready(function(){
        var i=1
        for (i = 1; i <= <?php echo count($servicesData);?>; i++) {
            setRate(i, $('#ratevalue' + i).val());
        }
        setRateSeller($('#sellerratevalue').val());
    });

    function setRate(index, star) {
        var options = {
            max_value: 5,
            step_size: 0.5,
            initial_value: star,
        }
        $(".rate" + index).rate(options);
    };

    function setRateSeller(star) {
        var options = {
            max_value: 5,
            step_size: 0.5,
            initial_value: star,
        };
        $(".sellerrate").rate(options);
    };


</script>
</body>
</html>

