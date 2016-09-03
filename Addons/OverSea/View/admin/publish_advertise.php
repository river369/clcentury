<?php
require("../mobile/common/locations.php");
session_start();
$city = $_SESSION['city'];
$type = $_SESSION['type'];
$service_id = $_SESSION['service_id'];

$appId=$_SESSION['$appid'];
$timestamp=$_SESSION['$timestamp'];
$nonceStr=$_SESSION['$nonceStr'];
$signature=$_SESSION['$signature'];

//unset($_SESSION['serviceData'], $_SESSION['sellerData'], $_SESSION['$timestamp'], $_SESSION['$nonceStr'], $_SESSION['$signature']);

$imageurl='http://clcentury.oss-cn-beijing.aliyuncs.com/';
$obj='yzphoto/advertise/'.$city.'/'.$type.'/'.$service_id.'.jpg';

$isMine = 1;
?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
    <title>易知海外</title>

    <script src="../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../resource/js/jquery/jquery-ui-1.11.1.min.js"></script>
    <script src="../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>

    <link rel="stylesheet" href="../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../resource/style/weiui/weui.css"/>
    <link rel="stylesheet" href="../resource/style/weiui/example.css"/>
    <link rel="stylesheet" href="../resource/style/cropper/main.css" />
    <style>
        label{ color:#01A4B5}
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>发布易知广告</h1>
    </div>
    <div data-role="content">
        <table>
            <tr>
                <td style="width:20%">
                    <label style="font-size:12px;">广告范围<label/>
                </td>
                <td style="width:80%">
                    <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" data-theme="a">
                        <input name="ad_area_type" id="radio-choice-c" value="1" <?php if (!isset($city) || $city != '地球') {echo 'checked="true"'; } ?> type="radio">
                        <label for="radio-choice-c">地区</label>
                        <input name="ad_area_type" id="radio-choice-d" value="2" <?php if ($city == '地球') {echo 'checked="true"'; } ?> type="radio">
                        <label for="radio-choice-d">地球</label>
                    </fieldset>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width:20%">
                    <label style="font-size:12px;" for="service_id">服务编号</label>
                </td>
                <td style="width:80%">
                    <input type="text" name="service_id" id="service_id" value="<?php echo isset($service_id) ? $service_id: ''; ?>" >
                </td>
            </tr>
        </table>

    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">

        <label style="font-size:12px;"> 请点击图标上传服务详情图片, 最多上传1张.</label>

        <div class="errmsgstring" style="color:red"></div>
        <ul class="weui_uploader_files">
            <li class="weui_uploader_file" id="uplaodImages" onclick="selectImages();" style="background-image:url(../resource/images/addpic.png); border: 3px solid #fff;border-radius: 5px;box-shadow: 0 0 5px rgba(0,0,0,.15);"></li>
        </ul>

        <div id="preview_image">
            <img src="<?php echo $imageurl.$obj; ?>" height="80" width="100%" alt="review" >
        </div>
    </div>

    <?php include './footer.php';?>
</div>

<script src="../resource/js/weixin/jweixin-1.0.0.js"></script>
<script src="../resource/js/cropper/cropper.min.js"></script>
<script src="../resource/js/cropper/main.js"></script>
<script>
    // this is for select service pictures
    function selectImages(){
        if ($('#service_id').val()==''){
            alert("服务编号不能为空");
        } else {
            // 5 图片接口
            // 5.1 拍照、本地选图
            var images = {
                localIds: [],
                serverIds: []
            };
            wx.chooseImage({
                count: 1,
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
                                    publishServicePics(images.serverIds);
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
        }

    };

    function publishServicePics(serverIds) {
        $(".errmsgstring").html('');
        $.ajax({
            url:'../../Controller/AuthUserDispatcher.php?c=publishAdvertise&serverids=' + serverIds,
            type:'POST',
            data : "service_id=" + $('#service_id').val() + "&ad_area_type="+$("input:radio[name=ad_area_type]:checked").val(),
            dataType:'json',
            async:false,
            success:function(result) {
                //alert(result.status);
                if (result.status == 0){
                    var htmlString = '';
                    for(var i in result.objLists) {
                        htmlString = htmlString + "<img src='<?php echo $imageurl;?>" + result.objLists[i] + "?t=" + Math.random()+ "' height='80' width='100%'>";
                        //htmlString = htmlString + '<li class="weui_uploader_file" onclick="changepopup(\'' + result.objLists[i] + '\')" style="background-image:url(<?php echo $imageurl; ?>' + result.objLists[i] + '); border: 3px solid #fff;border-radius: 5px;box-shadow: 0 0 5px rgba(0,0,0,.15);"></li>';
                    }
                    $('#preview_image').html(htmlString);
                } else {
                    $(".errmsgstring").html('Error:图片上传失败.' + result.msg);
                }
            },
            error:function(msg){
                $(".errmsgstring").html('Error:图片上传失败.' + msg.toSource());
            }
        })
        return false;
    }

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
    
</script>
</body>
</html>

