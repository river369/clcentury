<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$usersData= $_SESSION['usersData'];
$serviceType = isset($_SESSION['servicetype'])? $_SESSION['servicetype'] : 1;
require("../common/locations.php");
$servicearea = '地球';
if (isset($_SESSION ['servicearea'])){
    $servicearea = $_SESSION ['servicearea'];
}
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
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <a href="#nav-panel"data-icon="home"><?php echo $servicearea; ?></a>
        <h1>发现</h1>
        <a href="../query/search.html" rel="external" data-icon="search">搜索</a>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <div data-role="navbar">
            <ul>
                <li><a href="../../../Controller/Discover.php?servicetype=1" rel="external" <?php if ($serviceType == 1) { ?> class="ui-btn-active" <?php } ?> >旅游</a></li>
                <li><a href="../../../Controller/Discover.php?servicetype=2" rel="external" <?php if ($serviceType == 2) { ?> class="ui-btn-active" <?php } ?>>留学</a></li>
            </ul>
        </div><!-- /navbar -->
            <?php
                foreach($usersData as $key => $userData)
                {
            ?>
        <ul data-role="listview" data-inset="true">
            <li data-role="list-divider"><?php echo $userData['stars']?>星服务 <span class="ui-li-count">6次咨询</span></li>
            <li>
                <a href="../../../Controller/FreelookDispatcher.php?c=sellerdetails&sellerid=<?php echo $userData['id']; ?>" rel="external">
                    <img class="weui_media_appmsg_thumb" src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/<?php echo $userData['id'];?>/head.png" alt="">
                    <h2><?php echo $userData['name']?></h2>
                    <p style="white-space:pre-wrap;"><?php echo $userData['description']?></p>
                    <p class="ui-li-aside">￥<?php echo $userData['serviceprice']?>/小时</p>
                </a>
            </li>
            <li data-role="list-divider">
                <p>
            <?php $tags = $userData['tag'];
                $tagsArray = explode(',',$tags);
                foreach ($tagsArray as $tag){ ?>
                    <a href="javascript:alert('developing...');"> <?php echo $tag; ?></a>
            <?php } ?>
                </p>
            </li>
        </ul>
            <?php } ?>
    </div>

    <div data-role="footer" data-position="fixed" data-theme="c">
        <h4>Copyright (c) 2016 .</h4>
    </div>

    <div data-role="panel" data-position-fixed="true" data-display="push" data-theme="o" id="nav-panel">
        <ul data-role="listview">
            <li data-role="list-divider" data-icon="delete"><a href="#" data-rel="close">返回</a></li>
            <li><a href="../../../Controller/Discover.php?servicearea=地球" rel="external" data-rel="close"> 地球</a></li>
            <?php
                foreach ($country_city as $key => $value) {
            ?>
                    <li data-role="list-divider"><?php echo $key; ?></li>
                    <?php
                        foreach ($value as $city) {
                    ?>
                        <li><a href="../../../Controller/Discover.php?servicearea=<?php echo $city; ?>" rel="external" data-rel="close" ><?php echo $city; ?></a></li>
                    <?php } ?>
            <?php } ?>
        </ul>
    </div><!-- /panel -->
</div>

<script>
    $(document).ready(function(){
        $("img").error(function () {
            $(this).attr("src", "../../resource/images/head_default.jpg");
        });
    });
</script>
</body>
</html>

