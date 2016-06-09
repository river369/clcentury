<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
session_start();
$sellerData = $_SESSION['sellerData'];
$serviceData = $_SESSION['serviceData'];
$appId=$_SESSION['$appid'];
$timestamp=$_SESSION['$timestamp'];
$nonceStr=$_SESSION['$nonceStr'];
$signature=$_SESSION['$signature'];

//unset($_SESSION['serviceData'], $_SESSION['sellerData'], $_SESSION['$timestamp'], $_SESSION['$nonceStr'], $_SESSION['$signature']);

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
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
    <title>易知海外</title>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/tag/jquery.tagit.css"type="text/css" />
    <link rel="stylesheet" href="../../resource/style/tag/tagit.ui-zendesk.css"type="text/css" />

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery-ui-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="../../resource/js/tag/tag-it.min.js"></script>
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/weiui/weui.css"/>
    <link rel="stylesheet" href="../../resource/style/weiui/example.css"/>

    <script>
        $(function(){
            var sampleTags = ['c++', 'lua'];

            //-------------------------------
            // Tag-it methods
            //-------------------------------
            $('#methodTags').tagit({
                availableTags: sampleTags,
                // This will make Tag-it submit a single form value, as a comma-delimited field.
                singleField: true,
                singleFieldNode: $('#mytags'),
                removeConfirmation: true
            });
        });
    </script>

</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外" data-theme="d">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>发布易知服务</h1>
    </div>
    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <ul data-role="listview" data-inset="true">
            <li data-role="list-divider">请点击加号添加图片, 最多上传<?=$maxcount?>张.</li>
        </ul>
        <input type="hidden" name="remainingCount" id="remainingCount" value="<?php echo $remainingcount; ?> ">
        <!--
        <p>请点击加号添加图片, 最多上传<?=$maxcount?>张. </p>
        <p>您可以点击图片预览或删除.</p>
        -->
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
        <form id="submityz" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=publishServiceInfo">
            <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
                <legend>你的服务类型:</legend>
                <input name="service_type" id="radio-choice-c" value="1" <?php if (!isset($serviceData) ||$serviceData['service_type'] == 1) {echo 'checked="true"'; } ?> type="radio">
                <label for="radio-choice-c">旅游</label>
                <input name="service_type" id="radio-choice-d" value="2" <?php if ($serviceData['service_type'] == 2) {echo 'checked="true"'; } ?> type="radio">
                <label for="radio-choice-d">留学</label>
            </fieldset>

            <label for="service_area">您的服务地点:</label>
            <select name="service_area" id="service_area">
                <optgroup label="美国">
                    <option value="西雅图" <?php echo $serviceData['service_area']=='西雅图'? 'selected = "selected"' : ''; ?> >西雅图</option>
                    <option value="旧金山" <?php echo $serviceData['service_area']=='旧金山'? 'selected = "selected"' : ''; ?> >旧金山</option>
                    <option value="纽约" <?php echo $serviceData['service_area']=='纽约'? 'selected = "selected"' : ''; ?> >纽约</option>
                    <option value="洛杉矶" <?php echo $serviceData['service_area']=='洛杉矶'? 'selected = "selected"' : ''; ?> >洛杉矶</option>
                </optgroup>
                <optgroup label="中国">
                    <option value="北京市" <?php echo $serviceData['service_area']=='北京市'? 'selected = "selected"' : ''; ?>>北京市</option>
                    <option value="上海市" <?php echo $serviceData['service_area']=='上海市'? 'selected = "selected"' : ''; ?>>上海市</option>
                    <option value="天津市" <?php echo $serviceData['service_area']=='天津市'? 'selected = "selected"' : ''; ?>>天津市</option>
                    <option value="重庆市" <?php echo $serviceData['service_area']=='重庆市'? 'selected = "selected"' : ''; ?>>重庆市</option>
                </optgroup>
            </select>

            <label for="service_price">您的服务价格(￥/小时):</label>
            <input type="text" name="service_price" id="service_price" value="<?php echo isset($serviceData['service_price']) ? $serviceData['service_price']: ''; ?>" >

            <label for="description">自我介绍:</label>
            <textarea cols="30" rows="8" name="description" id="description" data-mini="true">
                <?php echo isset($serviceData['description']) ? $serviceData['description']: ''; ?>
            </textarea>

            <label for="methodTags">
                <a href="#tagpopup" data-rel="popup" class="ui-controlgroup-label ui-shadow ui-corner-all">选取或填写标签:</a>
            </label>
            <ul id="methodTags"></ul>
            <input name="mytags" id="mytags" value="<?php echo isset($serviceData['tag']) ? $serviceData['tag']: ''; ?>" type="hidden">
            
            <input type="submit" name="yzsubmit" id="yzsubmit" value="提交审核">
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

    <div data-role="footer" data-position="fixed" data-theme="c">
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
            url:'../../../Controller/AuthUserDispatcher.php?c=publishServicePics&serverids=' + serverIds,
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
            url:'../../../Controller/AuthUserDispatcher.php?c=publishServicePics&objtodelete=' + delobj,
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

