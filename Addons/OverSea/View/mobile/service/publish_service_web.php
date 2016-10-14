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

$objArray;
if (isset($_SESSION['objArray'])){
    $objArray = $_SESSION['objArray'] ;
}
$maxcount = 5;
$remainingcount = $maxcount - count($objArray);
$imageurl='http://clcentury.oss-cn-beijing.aliyuncs.com/';

$mainPicUrl = "../../resource/images/addpic.png";
if (isset($_SESSION['objMain'])) {
    $mainPicUrl= $imageurl.$_SESSION['objMain']."?t=".rand(10,100);
}
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
    <link rel="stylesheet" href="../../resource/style/tag/jquery.tagit.css" type="text/css" />
    <link rel="stylesheet" href="../../resource/style/tag/tagit.ui-zendesk.css" type="text/css" />
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
                <div class="loading" id="loading" aria-label="Loading" role="img" tabindex="-1"></div>
            </div>
        </div>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <label style="font-size:12px;"> 请点击图标上传服务详情图片, 最多上传5张.<label>
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
    <form id="picUploadForm" action="" enctype="multipart/form-data" method="post">
        <div style="opacity: 0;">
            <input style="display:none" onchange="submitPicture()" type="file" class="selected_file" id="selected_file" name="selected_file" accept="image/*" >
            <div class="loading" id="loadingPicUpload" aria-label="Loading" role="img" tabindex="-1"></div>
        </div>
    </form>

    <div data-role="content">
        <form id="submityz" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=publishServiceInfo">
            <table>
                <tr>
                    <td style="width:20%">
                        <label style="font-size:12px;">服务类型</label>
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
                        <a href="#nav-page" class="ui-btn ui-corner-all ui-shadow ui-btn-mini" data-transition="pop"><h5 id="regionDesc"><?php echo isset($serviceData['service_area']) ?$serviceData['service_area'] : "北京"; ?></h5></a>
                        <input type="hidden" name="service_area" id="service_area" value="<?php echo isset($serviceData['service_area']) ?$serviceData['service_area'] : "北京";?>">
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width:20%">
                        <label style="font-size:12px;">服务语言</label>
                    </td>
                    <td style="width:80%">
                        <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" data-theme="a">
                            <input name="service_language" id="radio-choice-e" value="中文普通话" <?php if (!isset($serviceData) ||$serviceData['service_language'] == "中文普通话" ) {echo 'checked="true"'; } ?> type="radio">
                            <label for="radio-choice-e">中文普通话</label>
                            <input name="service_language" id="radio-choice-f" value="英语" <?php if ($serviceData['service_language'] == "英语") {echo 'checked="true"'; } ?> type="radio">
                            <label for="radio-choice-f">英语</label>
                            <input name="service_language" id="radio-choice-g" value="当地语言" <?php if ($serviceData['service_language'] == "当地语言") {echo 'checked="true"'; } ?> type="radio">
                            <label for="radio-choice-g">当地语言</label>
                        </fieldset>
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
                        <label style="font-size:12px;">收费类型</label>
                    </td>
                    <td style="width:80%">
                        <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" data-theme="a">
                            <input name="service_price_type" id="radio-choice-h" value="1" <?php if (!isset($serviceData) ||$serviceData['service_price_type'] == 1) {echo 'checked="true"'; } ?> type="radio">
                            <label for="radio-choice-h">按小时(￥/小时)</label>
                            <input name="service_price_type" id="radio-choice-i" value="2" <?php if ($serviceData['service_price_type'] == 2) {echo 'checked="true"'; } ?> type="radio">
                            <label for="radio-choice-i">按次数(￥/次)</label>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width:20%">
                        <label style="font-size:12px;" for="service_price">费用单价</label>
                    </td>
                    <td style="width:80%">
                        <input type="number" name="service_price" id="service_price" value="<?php echo isset($serviceData['service_price']) ? $serviceData['service_price']: ''; ?>" >
                    </td>
                </tr>
            </table>

            <div style="margin: 12px 0px 0px 0px" >
                <label style="font-size:12px;" for="description">服务内容详细介绍</label>
                <textarea cols="30" rows="8" name="description" id="description" data-mini="true"><?php echo isset($serviceData['description']) ? $serviceData['description']: ''; ?></textarea>
            </div>
            <div style="margin: 15px 0px 0px 0px" >
                <a href="#tagpopup" data-rel="popup" class="ui-controlgroup-label"><label style="font-size:12px;">点击选取或填写标签</label></a>
                <ul id="methodTags"></ul>
                <input name="mytags" id="mytags" value="<?php echo isset($serviceData['tag']) ? $serviceData['tag']: ''; ?>" type="hidden">
            </div>
            
            <table style="margin: 15px 0px 0px 0px" >
                <tr>
                    <td>
                        <a href="./seller_agreement.html" class="ui-controlgroup-label" data-transition="slip"><label style="font-size:12px;">点击阅读服务发布声明</label></a>
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
        <h3>选取服务标签:</h3>
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

<div data-role="page" id="nav-page">
    <ul data-role="listview">
        <li data-role="list-divider" data-icon="back"><a href="#" data-rel="back">返回</a></li>
        <?php
        foreach ($countries as $display_sequence => $country) {
            ?>
            <li data-role="list-divider"><?php echo $country; ?></li>
            <?php
            foreach($cities[$display_sequence] as $key => $cityname){?>
                <li><a onclick="setCity('<?php echo $cityname; ?>');" data-rel="back"><label style="font-size:12px;"><?php echo $cityname?></label></a></li>
            <?php } ?>
        <?php } ?>
    </ul>
</div><!-- page -->

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
        $('#selected_file').click();
    };

    function submitPicture(){
        $picUploadForm = $('#picUploadForm');
        var data = new FormData($picUploadForm[0]);
        $.ajax({
            url:'../../../Controller/AuthUserDispatcher.php?c=publishServicePicsWeb',
            type: 'post',
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            success:function(result) {
                //alert(result.status);
                if (result.status == 0){
                    //var rcount = $('#remainingCount').val();
                    var rcount = <?=$maxcount?>;
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
            },
            beforeSend: function () {
                $loading  = $('#loadingPicUpload');
                $loading.fadeIn();
            },
            complete: function () {
                $loading  = $('#loadingPicUpload');
                $loading.fadeOut();
            },
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
    function setCity(city) {
        $('#regionDesc').html(city);
        $('#service_area').val(city);
    };
</script>
</body>
</html>

