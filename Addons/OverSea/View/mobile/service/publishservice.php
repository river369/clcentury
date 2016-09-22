<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
require dirname(__FILE__).'/../../../init.php';
use Addons\OverSea\Common\BusinessHelper;

session_start();
$sellerData = $_SESSION['sellerData'];
$serviceData = $_SESSION['serviceData'];

$status = $serviceData['status'];
$statusString = BusinessHelper::translateServiceCheckStatus($status);

$appId=$_SESSION['$appid'];
$timestamp=$_SESSION['$timestamp'];
$nonceStr=$_SESSION['$nonceStr'];
$signature=$_SESSION['$signature'];

//unset($_SESSION['serviceData'], $_SESSION['sellerData'], $_SESSION['$timestamp'], $_SESSION['$nonceStr'], $_SESSION['$signature']);

$objArray;
if (isset($_SESSION['objArray'])){
    $objArray = $_SESSION['objArray'] ;
}
$maxcount = 5;
$remainingcount = 5 - count($objArray);
$imageurl='http://clcentury.oss-cn-beijing.aliyuncs.com/';

$mainPicUrl = "../../resource/images/addpic.png";
if (isset($_SESSION['objMain'])) {
    $mainPicUrl= $imageurl.$_SESSION['objMain']."?t=".rand(10,100);
}
//if (isset($serviceData)){
//    $mainPicUrl= $imageurl."yzphoto/pics/".$serviceData['seller_id']."/".$serviceData['service_id']."/main.png?t=".rand(10,100);
//}

$cities = $_SESSION['cities'];
$countries = $_SESSION['countries'];

$isPublishService = 1;
?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
    <title>易知海外</title>

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery-ui-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="../../resource/js/tag/tag-it.min.js"></script>
    <script src="../../resource/js/validation/jquery.validate.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/tag/jquery.tagit.css"type="text/css" />
    <link rel="stylesheet" href="../../resource/style/tag/tagit.ui-zendesk.css"type="text/css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/weiui/weui.css"/>
    <link rel="stylesheet" href="../../resource/style/weiui/example.css"/>
    <link rel="stylesheet" href="../../resource/style/cropper/cropper.min.css" />
    <link rel="stylesheet" href="../../resource/style/cropper/main.css" />
    <link rel="stylesheet" href="../../resource/style/validation/validation.css" />
    <style>
        h5{ color:#01A4B5}
        label{ color:#01A4B5}
        table{ table-layout : fixed; width:100% }
        hr{border:0;background-color:#2c2c2c;height:1px;}
        div.headimage {
            height: 75px;
            width: 75px;
        }
    </style>
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
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>发布易知服务</h1>
    </div>

    <ul data-role="listview" data-inset="true" data-theme="f">
        <li data-role="list-divider"><p>服务状态:【<?php echo $statusString; ?>】</p></li>
        <?php if ($status == 40) {?>
            <li data-role="list-divider"><p>拒绝原因:【<?php echo $serviceData['check_reason']; ?>】</p></li>
        <?php } ?>
    </ul>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <label style="font-size:12px;"> 请点击图标上传服务主图片, 主图片显示于求易知列表.</label>
        <div class="container" id="crop-avatar" data-role="content" style="margin: -5px 0px -25px -20px" >
            <div class="avatar-view headimage" title="Change the avatar">
                <img src="<?php echo $mainPicUrl ?>" id='myhead' alt="主图片" onclick="chooseImages()">
            </div>
        </div>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <label style="font-size:12px;"> 请点击图标上传服务详情图片, 最多上传5张.<label>
                <br>
        <input type="hidden" name="remainingCount" id="remainingCount" value="<?php echo $remainingcount; ?> ">
        <!--
        <p>请点击加号添加图片, 最多上传<?=$maxcount?>张. </p>
        <p>您可以点击图片预览或删除.</p>
        -->
        <div class="errmsgstring" style="color:red"></div>
        <ul class="weui_uploader_files">
            <?php foreach ($objArray as $obj) { ?>
                <li class="weui_uploader_file" onclick="changepopup('<?php echo $obj; ?>')" style="background-image:url(<?php echo $imageurl.$obj; ?>); border: 3px solid #fff;border-radius: 5px;box-shadow: 0 0 5px rgba(0,0,0,.15);"></li>
            <?php }
            if ($remainingcount > 0) {?>
                <li class="weui_uploader_file" id="uplaodImages" onclick="selectImages()" style="background-image:url(../../resource/images/addpic.png); border: 3px solid #fff;border-radius: 5px;box-shadow: 0 0 5px rgba(0,0,0,.15);"></li>
            <?php } ?>
        </ul>

        <div data-role="popup" id="reviewPicPopup" class="reviewPicPopup" data-overlay-theme="a" data-corners="false" data-tolerance="30,15">
            <!--<p>是否删除该图片?</p>-->
            <div><a id="deletebutton" href="" onclick="deletePic();" data-theme="c" data-role="button" rel="external">删除此图片</a></div>
            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
            <img src="" alt="review" class="reviewimage">
            <input type="hidden" name="delobj" id="delobj" value="">
        </div>
    </div>

    <div data-role="content">
        <form id="submityz" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=publishServiceInfo">
            <table>
                <tr>
                    <td style="width:20%">
                        <label style="font-size:12px;">服务类型<label/>
                    </td>
                    <td style="width:80%">
                        <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" data-theme="a">
                            <input name="service_type" id="radio-choice-c" value="1" <?php if (!isset($serviceData) ||$serviceData['service_type'] == 1) {echo 'checked="true"'; } ?> type="radio">
                            <label for="radio-choice-c">旅游</label>
                            <input name="service_type" id="radio-choice-d" value="2" <?php if ($serviceData['service_type'] == 2) {echo 'checked="true"'; } ?> type="radio">
                            <label for="radio-choice-d">留学</label>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width:20%">
                        <label style="font-size:12px;" for="service_area">服务地区</label>
                    </td>
                    <td style="width:80%">
                        <select name="service_area" id="service_area" onchange="updateTagList()">
                            <?php foreach($countries as $display_sequence => $country){?>
                                <optgroup label="<?php echo $country; ?>">
                                    <?php foreach($cities[$display_sequence] as $key => $cityname){?>
                                        <option value="<?php echo $cityname; ?>" <?php echo $serviceData['service_area']==$cityname ? 'selected = "selected"' : ''; ?> ><?php echo $cityname; ?></option>
                                    <?php } ?>
                                </optgroup>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width:20%">
                        <label style="font-size:12px;" for="service_name">服务名称</label>
                    </td>
                    <td style="width:80%">
                        <input type="text" name="service_name" id="service_name" value="<?php echo isset($serviceData['service_name']) ? $serviceData['service_name']: ''; ?>" >
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width:20%">
                        <label style="font-size:12px;" for="service_brief">内容简介</label>
                    </td>
                    <td style="width:80%">
                        <input type="text" name="service_brief" id="service_brief" value="<?php echo isset($serviceData['service_brief']) ? $serviceData['service_brief']: ''; ?>" >
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td style="width:20%">
                        <label style="font-size:12px;" for="service_price">服务价格(￥/小时)</label>
                    </td>
                    <td style="width:80%">
                        <input type="number" name="service_price" id="service_price" value="<?php echo isset($serviceData['service_price']) ? $serviceData['service_price']: ''; ?>" >
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td>
                        <label style="font-size:12px;" for="description">服务内容详细介绍</label>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <textarea cols="30" rows="8" name="description" id="description" data-mini="true"><?php echo isset($serviceData['description']) ? $serviceData['description']: ''; ?></textarea>
                    </td>
                </tr>
            </table>
            <table style="margin: 10px 0px 0px 0px" >
                <tr>
                    <td>
                        <a href="#tagpopup" data-rel="popup" class="ui-controlgroup-label"><label style="font-size:12px;">点击选取或填写标签</label></a>
                    </td>
                </tr>
            </table>
            <table >
                <tr>
                    <td style="width:95%">
                        <ul id="methodTags"></ul>
                        <input name="mytags" id="mytags" value="<?php echo isset($serviceData['tag']) ? $serviceData['tag']: ''; ?>" type="hidden">
                    </td>
                </tr>
            </table>
            <table style="margin: 15px 0px 0px 0px" >
                <tr>
                    <td>
                        <a href="./seller_agreement.html" class="ui-controlgroup-label" data-transition="slip"><label style="font-size:12px;">点击阅读服务发布声明</label></a>
<!--                        <a href="#rulePopup" data-rel="popup" class="ui-controlgroup-label"><label style="font-size:12px;">点击阅读服务发布声明</label></a>-->
                    </td>
                    <td style="width:50%">

                    </td>
                </tr>
            </table>
            <table style="margin: -5px 0px 0px 0px" >
                <tr>
                    <td>
                        <input name="agree" id="agree" data-mini="true" type="checkbox" class="{required:true}" data-theme="e">
                        <label style="font-size:12px;" for="agree">我同意上述服务声明</label>
                    </td>
                </tr>
            </table>
            <input type="submit" name="yzsubmit" id="yzsubmit" value="发布信息" data-theme="c">
        </form>
    </div>

    <div data-role="popup" id="tagpopup" data-overlay-theme="f" data-corners="false" data-tolerance="60,30" style="max-width:300px;width:275px;">
        <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
        <h3>选取我的特长项:</h3>
        <div class="ui-grid-a" id="tagList">
        </div>
    </div>

    <div data-role="popup" id="reviewpopup" class="reviewpopup" data-overlay-theme="a"  data-theme="c" data-corners="false" data-tolerance="30,15">
        <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>

        <div class="modal fade" id="avatar-modal" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form class="avatar-form" action="../../../Controller/AuthUserDispatcher.php?c=submitServiceMainPic" enctype="multipart/form-data" method="post">
                        <div class="modal-body">
                            <div class="avatar-body">
                                <div>
                                    <h4 >请截取头像</h4>
                                </div>

                                <!-- Crop and preview -->
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="avatar-wrapper"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="avatar-preview preview-md"></div>
                                    </div>

                                </div>

                                <div class="row avatar-btns">
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-block avatar-save">保存头像</button>
                                    </div>
                                </div>

                                <!-- Upload image and data -->
                                <div class="avatar-upload" >
                                    <input type="hidden" class="avatar-src" name="avatar_src">
                                    <input type="hidden" class="avatar-data" name="avatar_data">

                                    <div style="opacity: 0;">
                                        <input type="file" class="avatar-input" id="avatarInput" name="avatar_file" accept="image/*" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.modal -->
        <div class="loading" id="loading" aria-label="Loading" role="img" tabindex="-1"></div>
    </div>
    <?php include '../common/footer.php';?>
</div>

<script>
    // this is for select service main image
    function chooseImages(){
        $('#reviewpopup').popup('open');
        $('#avatarInput').click();
    };
</script>
<script src="../../resource/js/weixin/jweixin-1.0.0.js"></script>
<script src="../../resource/js/cropper/cropper.min.js"></script>
<script src="../../resource/js/cropper/main.js"></script>
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

    function tagwith(tag){
        $('#methodTags').tagit('createTag', tag);
        return false;
    };

    function updateTagList(){
        var serviceArea = $('#service_area').val();
        var serviceType = $("input[name='service_type']:checked").val();
        $.ajax({
            url:'../../../Controller/FreelookDispatcher.php?c=getTagsByCityBusinessType&service_area=' + serviceArea + '&service_type=' + serviceType,
            type:'GET',
            dataType:'json',
            async:false,
            success:function(result) {
                //alert(result.status);
                if (result.status == 0){
                    var htmlString = '';
                    var loc = "a";
                    for(var i in result.objLists) {
                        htmlString = htmlString + '<div class="ui-block-' + loc + '"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="tagwith(\''+ result.objLists[i]['tag'] + '\')"><h5>' + result.objLists[i]['tag'] + '</h5></a></div>';
                        loc = 'b';
                    }
                    //alert (htmlString);
                    $('#tagList').html(htmlString);
                } else {
                    $(".errmsgstring").html('Error:' + result.msg);
                }
            },
            error:function(msg){
                $(".errmsgstring").html('Error:' + msg.toSource());
            }
        })
        return false;
    };

    $(document).ready(function(){
        updateTagList();
    });
</script>
<script>
    $( "#panel-fixed-page1" ).on( "pageinit", function() {
        $( "#submityz" ).validate({
            rules: {
                service_name: {
                    required: true,
                    minlength: 2,
                    //maxlength: 10
                },
                service_brief: {
                    required: true,
                    minlength: 2
                },
                service_price: {
                    required: true,
                    number:true,
                    min:10
                },
                description: {
                    required: true,
                    minlength: 4
                },
                agree: "required"
            },
            messages: {
                service_name: {
                    required: "服务名称不能为空",
                    minlength: "服务名称长度不能小于 2 个字",
                    //maxlength: "服务名称长度不能大于 20 个字",
                },
                service_brief: {
                    required: "内容简介不能为空",
                    minlength: "内容简介长度不能小于 2 个字"
                },
                service_price: {
                    required: "服务价格不能为空",
                    number:"服务价格必须为数字",
                    min:"服务单价最少为10元"
                },
                description: {
                    required: "服务内容详情不能为空",
                    minlength: "服务内容详情长度不能小于 4 个字"
                },
                agree: "请接受我们的声明"
            },
            errorPlacement: function( error, element ) {
                error.insertAfter( element.parent() );
            }
        });
    });
</script>
</body>
</html>

