<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$serviceYPlusItems = $_SESSION['serviceYPlusItems'];
$sellerid = $_SESSION['sellerId'];
$service_id = $_SESSION['service_id'];
$isMine = 1;
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
    <style>
        h5{ color:#01A4B5}
        label{ color:#01A4B5}
        table{ table-layout : fixed; width:100% }
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>更多图文攻略</h1>
    </div>

    <div data-role="content">
        <h5 style="font-size:12px;">已添加攻略</h5>
        <?php if (count($serviceYPlusItems) >0) {?>

        <ul data-role="listview" data-split-icon="delete"  data-inset="true">
        <?php foreach($serviceYPlusItems as $key => $yPlusItem) {?>
            <li data-theme="e">
                <a href="../../../Controller/AuthUserDispatcher.php?c=editYPlusItem&sellerid=<?php echo $sellerid; ?>&service_id=<?php echo $service_id;?>&service_yplus_item_id=<?php echo $yPlusItem['id']; ?>" rel="external"><p style="margin: 0px 0px 0px 0px"><?php echo isset($yPlusItem['yplus_subject'])? $yPlusItem['yplus_subject']:"未填写攻略主题"; ?></p></a>
                <a href="../../../Controller/AuthUserDispatcher.php?c=deleteYPlusItem&sellerid=<?php echo $sellerid; ?>&service_id=<?php echo $service_id;?>&service_yplus_item_id=<?php echo $yPlusItem['id']; ?>">删除</a>
            </li>
        <?php } ?>
        </ul>
        <?php } ?>

        <div class="ui-grid-a" style="margin: 15px 0px 0px 0px;font-size:10px;">
            <div class="ui-block-a">
                <a href="../../../Controller/AuthUserDispatcher.php?c=editYPlusItem&sellerid=<?php echo $sellerid;?>&service_id=<?php echo $service_id; ?>" rel="external" data-theme="c"  data-role="button">+ 添加新攻略</a>
            </div>
            <div class="ui-block-b">
                <a href="../../../Controller/AuthUserDispatcher.php?c=myServices&sellerid=<?php echo $sellerid;?>&status=20" rel="external" data-theme="c" data-role="button">返回服务列表</a>
            </div>
        </div>
    </div>


    <?php include '../common/footer.php';?>

</div>
</body>
</html>

