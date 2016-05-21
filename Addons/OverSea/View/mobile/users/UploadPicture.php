<?php

session_start();
$appId=$_SESSION['$appid'];
$timestamp=$_SESSION['$timestamp'];
$nonceStr=$_SESSION['$nonceStr'];
$signature=$_SESSION['$signature'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>上传图片</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../resource/style/weiui/weui.css"/>
    <link rel="stylesheet" href="../../resource/style/weiui/example.css"/>
</head>
<body ontouchstart="">
<div class="wxapi_container">
    <div class="weui_cells_title">上传图片</div>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <div class="weui_uploader">
                    <div class="weui_uploader_hd weui_cell">
                        <div class="weui_cell_bd weui_cell_primary">图片</div>
                        <div class="weui_cell_ft">0/2</div>
                    </div>
                    <ul class="weui_uploader_files">
                        <li class="weui_uploader_file" style="background-image:url(http://www.clcentury.com/weiphp/Uploads/Picture/test/201605020044186186.jpg)"></li>
                        <li class="weui_uploader_file" style="background-image:url(http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/pics/7/1.jpg)"></li>

                        <li class="weui_uploader_file" id="uplaodImages" style="background-image:url(../../resource/images/add.jpg)"></li>
                    </ul>
                </div>
            </div>
        </div>
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
                                    window.location.href = '../../../Controller/Dispatcher.php?serverids=' + images.serverIds;
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
</script>
</html>
