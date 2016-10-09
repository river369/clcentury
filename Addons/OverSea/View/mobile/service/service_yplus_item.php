<?php
session_start();
$serviceYPlusItem = $_SESSION['serviceYPlusItemData'];
$sellerid = $_SESSION['sellerId'];
$service_id = $_SESSION['service_id'];

$appId=$_SESSION['$appid'];
$timestamp=$_SESSION['$timestamp'];
$nonceStr=$_SESSION['$nonceStr'];
$signature=$_SESSION['$signature'];

$objArray;
if (isset($_SESSION['service_yplus_obj_array'])){
    $objArray = $_SESSION['service_yplus_obj_array'] ;
}
$maxcount = 5;
$remainingcount = 5 - count($objArray);
$imageurl='http://clcentury.oss-cn-beijing.aliyuncs.com/';

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
    <script src="../../resource/js/validation/jquery.validate.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/weiui/weui.css"/>
    <link rel="stylesheet" href="../../resource/style/weiui/example.css"/>
    <link rel="stylesheet" href="../../resource/style/validation/validation.css" />

    <style>
        h5{ color:#01A4B5}
        label{ color:#01A4B5}
        table{ table-layout : fixed; width:100% }
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>攻略详情</h1>
    </div>
    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <label style="font-size:12px;"> 请点击图标上传更多攻略图片, 最多上传5张.<label>
        <br>
        <input type="hidden" name="remainingCount" id="remainingCount" value="<?php echo $remainingcount; ?> ">
        <div class="errmsgstring" style="color:red"></div>
        <ul class="weui_uploader_files">
            <?php foreach ($objArray as $obj) { ?>
                <li class="weui_uploader_file" onclick="changepopup('<?php echo $obj; ?>')" style="background-image:url(<?php echo $imageurl.$obj; ?>); border: 3px solid #fff;border-radius: 5px;box-shadow: 0 0 5px rgba(0,0,0,.15);"></li>
            <?php }
            if ($remainingcount > 0) {?>
                <li class="weui_uploader_file" id="uplaodImages" onclick="selectImages()" style="background-image:url(../../resource/images/addpic.png); border: 3px solid #fff;border-radius: 5px;box-shadow: 0 0 5px rgba(0,0,0,.15);"></li>
            <?php } ?>
        </ul>

        <div data-role="popup" id="reviewPicPopup" class="reviewPicPopup" data-theme="c" data-corners="false" data-tolerance="30,15">
            <!--<p>是否删除该图片?</p>-->
            <img src="" alt="review" class="reviewimage">
            <input type="hidden" name="delobj" id="delobj" value="">
            <div class="ui-grid-a">
                <div class="ui-block-a">
                    <a id="deletebutton" href="" onclick="deletePic();" data-role="button" rel="external">删除此图片</a>
                </div>
                <div class="ui-block-b">
                    <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow" >再想想</a>
                </div>
            </div>
        </div>
    </div>

    <div data-role="content">
        <form id="submityz" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=publishServiceYPlusItem">
            <div style="margin: 12px 0px 0px 0px" >
                <label style="font-size:12px;" for="yplus_subject">攻略主题</label>
                <input type="text" name="yplus_subject" id="yplus_subject" value="<?php echo isset($serviceYPlusItem['yplus_subject']) ? $serviceYPlusItem['yplus_subject']: ''; ?>" >
            </div>
            <div style="margin: 12px 0px 0px 0px" >
                <label style="font-size:12px;" for="yplus_brief">文字描述</label>
                <textarea cols="30" rows="8" name="yplus_brief" id="yplus_brief" data-mini="true"><?php echo isset($serviceYPlusItem['yplus_brief']) ? $serviceYPlusItem['yplus_brief']: ''; ?></textarea>
            </div>
            <input type="hidden" name="sellerid" id="sellerid" value="<?php echo $sellerid; ?>">
            <input type="hidden" name="service_yplus_item_id" id="service_yplus_item_id" value="<?php echo $serviceYPlusItem['id']; ?>">
            <input type="hidden" name="service_id" id="service_id" value="<?php echo $service_id; ?>">

            <div class="ui-grid-a" style="margin: 15px 0px 0px 0px;font-size:10px;">
                <div class="ui-block-a">
                    <input type="submit" name="yzsubmit" id="yzsubmit" value="保存" data-theme="c">
                </div>
                <div class="ui-block-b">
                    <a href="../../../Controller/AuthUserDispatcher.php?c=getYPlusList&sellerid=<?php echo $sellerid; ?>&service_id=<?php echo $service_id; ?>" rel="external" data-theme="c" data-role="button">返回列表</a>
                </div>
            </div>
            
        </form>
    </div>

    <?php include '../common/footer.php';?>

</div>

<script src="../../resource/js/weixin/jweixin-1.0.0.js"></script>
<script>
    // this is for select service pictures
    function selectImages(){
        // 5 图片接口
        // 5.1 拍照、本地选图
        var images = {
            localIds: [],
            serverIds: []
        };
        var rcount = parseInt($('#remainingCount').val());
        wx.chooseImage({
            count: rcount,
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
    };

    function publishServicePics(serverIds) {
        $.ajax({
            url:'../../../Controller/AuthUserDispatcher.php?c=publishServiceYPlusItemPics&serverids=' + serverIds,
            type:'POST',
            data : $('#submityz').serialize(),
            dataType:'json',
            async:false,
            success:function(result) {
                //alert(result.status);
                if (result.status == 0){
                    var rcount = $('#remainingCount').val();
                    var htmlString = '<ul class="weui_uploader_files">';
                    for(var i in result.objLists) {
                        htmlString = htmlString + '<li class="weui_uploader_file" onclick="changepopup(\'' + result.objLists[i] + '\')" style="background-image:url(<?php echo $imageurl; ?>' + result.objLists[i] + '); border: 3px solid #fff;border-radius: 5px;box-shadow: 0 0 5px rgba(0,0,0,.15);"></li>';
                        rcount--;
                    }
                    $('#remainingCount').val(rcount);
                    if (rcount > 0) {
                        htmlString = htmlString + '<li class="weui_uploader_file" id="uplaodImages" onclick="selectImages()" style="background-image:url(../../resource/images/addpic.png)"></li>';
                    }
                    htmlString = htmlString + '</ul>';
                    $('.weui_uploader_files').html(htmlString);
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

    function deletePic(serverIds) {
        var delobj = $('#delobj').val();
        $.ajax({
            url:'../../../Controller/AuthUserDispatcher.php?c=publishServiceYPlusItemPics&objtodelete=' + delobj,
            type:'POST',
            data : $('#submityz').serialize(),
            dataType:'json',
            async:false,
            success:function(result) {
                //alert(result.status);
                if (result.status == 0){
                    var rcount = $('#remainingCount').val();
                    rcount++;
                    $('#remainingCount').val(rcount);
                    var htmlString = '<ul class="weui_uploader_files">';
                    for(var i in result.objLists) {
                        htmlString = htmlString + '<li class="weui_uploader_file" onclick="changepopup(\'' + result.objLists[i] + '\')" style="background-image:url(<?php echo $imageurl; ?>' + result.objLists[i] + ')"></li>';
                    }
                    htmlString = htmlString + '<li class="weui_uploader_file" id="uplaodImages" onclick="selectImages()" style="background-image:url(../../resource/images/addpic.png)"></li>';
                    htmlString = htmlString + '</ul>';
                    $('.weui_uploader_files').html(htmlString);
                    $('.reviewPicPopup').popup('close');
                } else {
                    $(".errmsgstring").html('Error:图片删除失败.' + result.msg);
                }
            },
            error:function(msg){
                $(".errmsgstring").html('Error:图片删除失败.' + msg.toSource());
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

    function changepopup(uri) {
        $('.reviewimage').attr('src','<?php echo $imageurl; ?>'+uri);
        $('#delobj').val(uri);
        //var link = "../../../Controller/AuthUserDispatcher.php?c=publishServicePics&objtodelete=" + uri;
        //$('#deletebutton').attr('href', link);
        $('.reviewPicPopup').popup('open');
    }
</script>
</body>
</html>

