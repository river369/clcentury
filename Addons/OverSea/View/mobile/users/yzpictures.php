<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$appId=$_SESSION['$appid'];
$timestamp=$_SESSION['$timestamp'];
$nonceStr=$_SESSION['$nonceStr'];
$signature=$_SESSION['$signature'];
unset($_SESSION['$timestamp'], $_SESSION['$nonceStr'], $_SESSION['$signature']);

$objArray;
$objkey='objArray'.$_SESSION['signedUser'];
if (isset($_SESSION[$objkey])){
    $objArray = $_SESSION[$objkey] ;
}
$maxcount = 5;
$remainingcount = 5 - count($objArray);
$imageurl='http://clcentury.oss-cn-beijing.aliyuncs.com/';

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
    <link rel="stylesheet" href="../../resource/style/weiui/weui.css"/>
    <link rel="stylesheet" href="../../resource/style/weiui/example.css"/>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed">
        <h1>个人图片</h1>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <ul data-role="listview" data-inset="true">
            <li data-role="list-divider">请点击加号添加图片, 最多上传<?=$maxcount?>张.</li>
        </ul>
        <!--
        <p>请点击加号添加图片, 最多上传<?=$maxcount?>张. </p>
        <p>您可以点击图片预览或删除.</p>
        -->
        <ul class="weui_uploader_files">
            <?php foreach ($objArray as $obj) { ?>
                <li class="weui_uploader_file" onclick="changepopup('<?php echo $obj; ?>')" style="background-image:url(<?php echo $imageurl.$obj; ?>)"></li>
            <?php }
            if ($remainingcount > 0) {?>
            <li class="weui_uploader_file" id="uplaodImages" style="background-image:url(../../resource/images/add.jpg)"></li>
            <?php } ?>
        </ul>

        <div data-role="popup" id="reviewpopup" class="reviewpopup" data-overlay-theme="a" data-corners="false" data-tolerance="30,15">
            <!--<p>是否删除该图片?</p>-->
            <div><a id="deletebutton" href="" rel="external" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-mini">删除此图片</a></div>
            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
            <img src="" alt="review" class="reviewimage">
        </div>
    </div>

    <div data-role="footer" data-position="fixed">
        <h4>Copyright (c) 2016 .</h4>
    </div>
</div>

</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
        debug: false,
        appId: '<?=$appId?>',
        timestamp: <?=$timestamp?>,
        nonceStr: '<?=$nonceStr?>',
        signature: '<?=$signature?>',
        jsApiList: [
            'chooseImage',
            'uploadImage',
        ]
    });

    wx.ready(function () {

        // 5 图片接口
        // 5.1 拍照、本地选图
        var images = {
            localIds: [],
            serverIds: []
        };

        document.querySelector('#uplaodImages').onclick = function () {
            wx.chooseImage({
                count: <?=$remainingcount?>,
                success: function (res) {
                    images.localIds = res.localIds;
                    //alert('已选择 ' + res.localIds.length + ' 张图片');

                    var i = 0, length = images.localIds.length;
                    images.serverIds = [];
                    function upload() {
                        wx.uploadImage({
                            localId: images.localIds[i],
                            success: function (res) {
                                i++;
                                //alert('已上传：' + i + '/' + length);
                                images.serverIds.push(res.serverId);
                                if (i < length) {
                                    upload();
                                } else {
                                    window.location.href = '../../../Controller/Dispatcher.php?c=submityzpic&serverids=' + images.serverIds;
                                }
                            },
                            fail: function (res) {
                                alert(JSON.stringify(res));
                            }
                        });
                    }

                    upload();
                }
            });
        };
    });

    function changepopup(uri) {
        $('.reviewimage').attr('src','<?php echo $imageurl; ?>'+uri);
        var link = "../../../Controller/Dispatcher.php?c=submityzpic&objtodelete=" + uri;
        //alert (link);
        //$('.deletebutton').html('<a href="'+ link +'" rel="external" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-mini">删除此图片</a>');
        $('#deletebutton').attr('href', link);
        $('.reviewpopup').popup('open');
    }

</script>
</html>

