<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$adsData= $_SESSION['adsData'];
$isMine = 1;
?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>易知海外</title>

    <script src="../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>

    <link rel="stylesheet" href="../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../resource/style/themes/my-theme.min.css" />
    <style type="text/css">
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>广告位列表</h1>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <?php
        foreach($adsData as $key => $ad)
        {
            $city= $ad['city_name'];
            $type = $ad['service_type'];
            $service_id = $ad['service_id'];
            $id =  $ad['id'];
            $imageurl='http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/advertise/'.$city.'/'.$type.'/'.$service_id.'.jpg';
            ?>
        <ul data-role="listview" data-inset="true" data-theme="f">
            <li data-role="list-divider"><?php echo $city . ":" . ($type==1? '旅游' : '留学');?></span></li>
            <li>
                <a href="../../Controller/AuthUserDispatcher.php?c=prepareAdvertise&service_id=<?php echo $service_id; ?>&city=<?php echo $city;?>&type=<?php echo $type;?>" rel="external">
                    <img class="weui_media_appmsg_thumb" src="<?php echo $imageurl;?>" alt="" height="100%">
                    <h2> <?php echo $ad['service_id'];?> </h2>
                </a>
            </li>
            <li>
                <div class="ui-grid-a">
                    <div class="ui-block-a"><a href="#checkDialog" data-rel="popup" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="checkPopup('<?php echo $service_id; ?>', '<?php echo $id; ?>', 0)">删除广告</a></div>
                </div>
            </li>
        </ul>
        <?php } ?>

        <div data-role="popup" id="checkDialog" data-overlay-theme="a" data-theme="a" style="max-width:400px;">
            <div data-role="header" data-theme="a">
                <h1>检查广告</h1>
            </div>
            <div role="main" class="ui-content">
                <h3 class="ui-title" id="checkString">.</h3>
                <form id="checkService" data-ajax="false" method="post" action="../../Controller/AuthUserDispatcher.php?c=deleteAdvertiseOfService">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="submit" name="cancelsubmit" id="cancelsubmit" value="确定">
                </form>
                <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
            </div>
        </div>

    </div>

    <div data-role="footer" data-position="fixed" data-theme="c">
               <h4>Copyright (c) 2016 .</h4>
    </div>
</div>
<script>
    function checkPopup(service_id, id, type) {
        var messages = "确定";
        if (type == 0){
            messages = messages + "删除";
        } else {
            messages = messages + "";
        }
        messages = messages + "服务" + service_id + "的广告?";
        //alert(messages);
        $('#checkString').html(messages);
        $('#id').val(id);
        $('#checkDialog').popup('open');
    };
    $(document).ready(function(){
        $("img").error(function () {
            $(this).attr("src", "../resource/images/head_default.jpg");
        });
    });
</script>
</body>
</html>

