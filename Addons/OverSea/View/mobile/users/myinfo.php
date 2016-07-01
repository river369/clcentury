<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
session_start();
require dirname(__FILE__).'/../../../init.php';
use Addons\OverSea\Common\BusinessHelper;
$existedUser = $_SESSION['signedUserInfo'] ;
$status = $existedUser['status'];
$statusString = BusinessHelper::translateRealNameStatus($status);
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
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/cropper/cropper.min.css" />
    <link rel="stylesheet" href="../../resource/style/cropper/main.css" />

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery-ui-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="../../resource/js/tag/tag-it.min.js"></script>
    <script src="../../resource/js/validation/jquery.validate.min.js"></script>
    <script src="../../resource/js/validation/localization/messages_zh.min.js"></script>

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
    <style>
        label.error {
            color: red;
            font-size: 16px;
            font-weight: normal;
            line-height: 1.4;
            margin-top: 0.5em;
            width: 100%;
            float: none;
        }
        em {
            color: red;
            font-weight: bold;
            padding-right: .25em;
        }
    </style>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>个人信息</h1>
    </div>

    <div data-role="content">
        <ul data-role="listview" data-inset="true">
            <li>您的用户状态: <span class="ui-li-count"><?php echo $statusString; ?></span></li>
            <?php if ($status == 40) {?>
                <li>拒绝原因: <span class="ui-li-count"><?php echo $existedUser['check_reason']; ?></span></li>
            <?php } ?>
        </ul>

        <label for="name" >我的头像:</label>
        <!-- Current avatar -->
        <div class="container" id="crop-avatar" data-role="content">
            <div class="avatar-view" title="Change the avatar">
                <img src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/<?php echo $existedUser['id'];?>/head.png?t=<?php echo rand(10,100); ?>" id='myhead' alt="点击上传头像" onclick="chooseImages()">
            </div>
        </div>
        <input type="hidden" id="uid" value="<?php echo $existedUser['id'];?>">
        
        <form id="myinfoForm" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=updateMyinfo">
            <div data-role="fieldcontain">
                <label for="name" >您的昵称:</label>
                <input type="text" name="name" id="name" value="<?php echo isset($existedUser['name']) ? $existedUser['name']: ''; ?>">
                </br>
                <label for="weixin">您的微信号:</label>
                <input type="text" name="weixin" id="weixin" value="<?php echo isset($existedUser['weixin']) ? $existedUser['weixin']: ''; ?>">
                </br>
                <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true">
                    <legend>我是:</legend>
                    <input name="gender" id="radio-choice-c" value="1" <?php if ($existedUser['gender'] == 1) {echo 'checked="true"'; } ?> type="radio">
                    <label for="radio-choice-c">男生</label>
                    <input name="gender" id="radio-choice-d" value="2" <?php if ($existedUser['gender'] == 2) {echo 'checked="true"'; } ?> type="radio">
                    <label for="radio-choice-d">女生</label>
                </fieldset>
                </br>
                <label for="name">您的电子邮件:</label>
                <input type="text" name="email" id="email" value="<?php echo isset($existedUser['email']) ? $existedUser['email']: ''; ?>">
                </br>
                <label for="description">自我介绍:</label>
                <textarea cols="30" rows="8" name="description" id="description" data-mini="true"><?php echo isset($existedUser['description']) ? $existedUser['description']: ''; ?></textarea>
                </br>
                <!--
                <input name="tags" id="methodTags" value="诚实守信,价格合理">
                -->
                <label for="methodTags">
                    <a href="#tagpopup" data-rel="popup" class="ui-controlgroup-label">选取或填写特长:</a>
                </label>
                <ul id="methodTags"></ul>
                <input name="mytags" id="mytags" value="<?php echo isset($existedUser['tag']) ? $existedUser['tag']: ''; ?>" type="hidden">
            </div>
            <input type="submit" name="yzsubmit" id="yzsubmit" value="发布">
        </form>
    </div>

    <div data-role="popup" id="tagpopup" data-overlay-theme="a" data-corners="false" data-tolerance="30,15">
        <!--<p>是否删除该图片?</p>-->
        <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>
        <h3>特长:</h3>
        <div class="ui-grid-a">
            <div class="ui-block-a"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="tagwith('经验丰富1')">经验丰富1</a></div>
            <div class="ui-block-b"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="tagwith('长的帅长的帅长的')">长的长的帅</a></div>
            <div class="ui-block-a"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="tagwith('长的帅1')">长的帅1</a></div>
            <div class="ui-block-b"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="tagwith('长的帅2')">长的帅2</a></div>
            <div class="ui-block-a"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="tagwith('长的帅3')">长的帅3</a></div>
            <div class="ui-block-b"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="tagwith('听话,乖')">听话,乖</a></div>
            <div class="ui-block-a"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="tagwith('经验丰富验丰富')">经验丰富</a></div>
            <div class="ui-block-b"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini" onclick="tagwith('长的帅9')">长的帅9</a></div>
        </div>
    </div>

    <div data-role="popup" id="reviewpopup" class="reviewpopup" data-overlay-theme="a"  data-theme="c" data-corners="false" data-tolerance="30,15">
        <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>

        <div class="modal fade" id="avatar-modal" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form class="avatar-form" action="../../../Controller/AuthUserDispatcher.php?c=submitheadpic" enctype="multipart/form-data" method="post">
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
    function tagwith(tag){
        $('#methodTags').tagit('createTag', tag);
        return false;
    }
</script>
<script src="../../resource/js/cropper/cropper.min.js"></script>
<script src="../../resource/js/cropper/main.js"></script>
<script>
    $(document).ready(function(){
        $("img").error(function () {
            $(this).attr("src", "../../resource/images/head_default.jpg");
        });
    });
    function chooseImages(){
        $('#reviewpopup').popup('open');
        $('#avatarInput').click();
    };
</script>
<script>
    $( "#panel-fixed-page1" ).on( "pageinit", function() {
        $( "#myinfoForm" ).validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    email: true
                }

            },
            errorPlacement: function( error, element ) {
                error.insertAfter( element.parent() );
            }
        });
    });
</script>

</body>
</html>

