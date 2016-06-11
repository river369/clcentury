<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
session_start();
$userid = $_SESSION['signedUser'];
$existedUser = $_SESSION['signedUserInfo'] ;
$status = $existedUser['status'];
$statusString = '用户已经注册,请提交实名认证申请';
switch ($status)
{
    case 20:
        $statusString = "实名认证审核中";
        break;
    case 40:
        $statusString = "实名认证被拒绝";
        break;
    case 60:
        $statusString = "实名认证已通过";
        break;

}

$appId=$_SESSION['$appid'];
$timestamp=$_SESSION['$timestamp'];
$nonceStr=$_SESSION['$nonceStr'];
$signature=$_SESSION['$signature'];

$objArray;
$objkey='objArray';
if (isset($_SESSION[$objkey])){
    $objArray = $_SESSION[$objkey] ;
}
$maxcount = 2;
$remainingcount = 2 - count($objArray);
$imageurl='http://clcentury.oss-cn-beijing.aliyuncs.com/';

?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
    <title>易知海外</title>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/tag/jquery.tagit.css"type="text/css" />

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery-ui-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/weiui/weui.css"/>
    <link rel="stylesheet" href="../../resource/style/weiui/example.css"/>


</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="d">
        <h1>申请实名认证</h1>
    </div>
   
    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <ul data-role="listview" data-inset="true">
            <li>您的用户状态: <span class="ui-li-count"><?php echo $statusString; ?></span></li>
            <?php if ($status == 40) {?>
                <li>拒绝原因: <span class="ui-li-count"><?php echo $existedUser['check_reason']; ?></span></li>
            <?php } ?>
        </ul>
        <ul data-role="listview" data-inset="true">
            <li data-role="list-divider"><p style="white-space:pre-wrap;">请点击加号添加本人手持身份证正反面照片或护照首页照片.</p></li>
        </ul>
        <input type="hidden" name="remainingCount" id="remainingCount" value="<?php echo $remainingcount; ?> ">
        <div class="errmsgstring" style="color:red"></div>
        <ul class="weui_uploader_files">
            <?php foreach ($objArray as $obj) { ?>
                <li class="weui_uploader_file" onclick="changepopup('<?php echo $obj; ?>')" style="background-image:url(<?php echo $imageurl.$obj; ?>)"></li>
            <?php }
            if ($remainingcount > 0) {?>
                <li class="weui_uploader_file" id="uplaodImages" onclick="selectImages()" style="background-image:url(../../resource/images/add.jpg)"></li>
            <?php } ?>
        </ul>

        <div data-role="popup" id="reviewpopup" class="reviewpopup" data-overlay-theme="a" data-corners="false" data-tolerance="30,15">
            <!--<p>是否删除该图片?</p>-->
            <div><a id="deletebutton" href="" onclick="deletePic();" rel="external" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-mini">删除此图片</a></div>
            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
            <img src="" alt="review" class="reviewimage">
            <input type="hidden" name="delobj" id="delobj" value="">
        </div>
    </div>

    <div data-role="content">
        <form id="submityz" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=publishRealNameInfo">
            <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
                <legend>你的认证类型:</legend>
                <input name="certificate_type" id="radio-choice-c" value="1" <?php if (!isset($existedUser) ||$existedUser['certificate_type'] == 1) {echo 'checked="true"'; } ?> type="radio">
                <label for="radio-choice-c">身份证</label>
                <input name="certificate_type" id="radio-choice-d" value="2" <?php if ($existedUser['certificate_type'] == 2) {echo 'checked="true"'; } ?> type="radio">
                <label for="radio-choice-d">护照</label>
            </fieldset>
            </br>
            <label for="real_name">您的真实姓名:</label>
            <input type="text" name="real_name" id="real_name" value="<?php echo isset($existedUser['real_name']) ? $existedUser['real_name']: ''; ?>" >
            </br>
            <label for="certificate_no">您的身份证号或护照号:</label>
            <input type="text" name="certificate_no" id="certificate_no" value="<?php echo isset($existedUser['certificate_no']) ? $existedUser['certificate_no']: ''; ?>" >
            </br>

            <input type="submit" name="yzsubmit" id="yzsubmit" value="提交认证">
        </form>
    </div>

    <div data-role="popup" id="tagpopup" data-overlay-theme="a" data-corners="false" data-tolerance="30,15">
        <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
        <h3>特长:</h3>
        <div class="ui-grid-a">
            <div class="ui-block-a"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="tagwith('某大学1')">某大学1</a></div>
            <div class="ui-block-b"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="tagwith('某大学8')">某大学8</a></div>
        </div>
    </div>

    <div data-role="footer" data-position="fixed" data-theme="d">
        <h4>Copyright (c) 2016 .</h4>
    </div>
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
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
                                publishRealNamePics(images.serverIds);
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

    function publishRealNamePics(serverIds) {
        $.ajax({
            url:'../../../Controller/AuthUserDispatcher.php?c=publishRealNamePics&serverids=' + serverIds,
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
                        //alert(result.objLists[i]);
                        htmlString = htmlString + '<li class="weui_uploader_file" onclick="changepopup(\'' + result.objLists[i] + '\')" style="background-image:url(<?php echo $imageurl; ?>' + result.objLists[i] + ')"></li>';
                        rcount--;
                    }
                    $('#remainingCount').val(rcount);
                    if (rcount > 0) {
                        htmlString = htmlString + '<li class="weui_uploader_file" id="uplaodImages" onclick="selectImages()" style="background-image:url(../../resource/images/add.jpg)"></li>';
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
            url:'../../../Controller/AuthUserDispatcher.php?c=publishRealNamePics&objtodelete=' + delobj,
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
                    htmlString = htmlString + '<li class="weui_uploader_file" id="uplaodImages" onclick="selectImages()" style="background-image:url(../../resource/images/add.jpg)"></li>';
                    htmlString = htmlString + '</ul>';
                    $('.weui_uploader_files').html(htmlString);
                    $('.reviewpopup').popup('close');
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
        $('.reviewpopup').popup('open');
    }

    function tagwith(tag){
        $('#methodTags').tagit('createTag', tag);
        return false;
    };

</script>

</body>
</html>
