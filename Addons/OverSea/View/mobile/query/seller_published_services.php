<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$servicesData= $_SESSION['servicesData'];

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
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <style type="text/css">
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>卖家的全部服务</h1>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <?php
        foreach($servicesData as $key => $serviceData)
        {
            ?>
            <ul data-role="listview" data-inset="true">
                <li data-role="list-divider">
                    <?php $servicetypeDesc = $serviceData['service_type'] ==1 ? '旅游' : '留学';
                    echo $servicetypeDesc.":".$serviceData['stars']?>星服务 <span class="ui-li-count"><?php echo $serviceData['serve_count']; ?>次履行服务</span></li>
                <li>
                    <a href="../../../Controller/FreelookDispatcher.php?c=serviceDetails&service_id=<?php echo $serviceData['id']; ?>" rel="external">
                        <img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/<?php echo $serviceData['seller_id'];?>/head.png" alt="">
                        <h2><?php echo $serviceData['seller_name']?></h2>
                        <p style="white-space:pre-wrap;"><?php echo $serviceData['description']?></p>
                        <p class="ui-li-aside">￥<?php echo $serviceData['service_price']?>/小时</p>
                    </a>
                </li>
                <li data-role="list-divider">
                    <p>
                        <?php $tags = $serviceData['tag'];
                        $tagsArray = explode(',',$tags);
                        foreach ($tagsArray as $tag){ ?>
                            <a href="../../../Controller/AuthUserDispatcher.php?c=searchByKeyWord&keyWord=<?php echo $tag;?> " rel="external"><?php echo $tag; ?></a>
                        <?php } ?>
                    </p>
                </li>
            </ul>
        <?php } ?>
    </div>

    <div data-role="footer" data-position="fixed" data-theme="c">
        <h4>Copyright (c) 2016 .</h4>
    </div>
</div>
</body>
</html>

