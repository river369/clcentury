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
$userId = $_SESSION['signedUser'];
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
    <link rel="stylesheet" href="../../resource/style/validation/validation.css" />
    <style>
        label{ color:#33c8ce;}
        table{ table-layout : fixed; width:100% }
        hr{border:0;background-color:#2c2c2c;height:1px;}
        div.headimage {
            height: 75px;
            width: 75px;
        }
    </style>
    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery-ui-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="../../resource/js/tag/tag-it.min.js"></script>
    <script src="../../resource/js/validation/jquery.validate.min.js"></script>

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
        <h1>个人信息</h1>
    </div>
    <ul data-role="listview" data-inset="true" data-theme="f">
        <li data-role="list-divider">用户状态 <span class="ui-li-count"><?php echo $statusString; ?></span></li>
        <?php if ($status == 40) {?>
            <li data-role="list-divider">拒绝原因 <span class="ui-li-count"><?php echo $existedUser['check_reason']; ?></span></li>
        <?php } ?>
    </ul>
    <div data-role="content">

        <table>
            <tr>
                <td style="width:15%">
                    <label  style="font-size:12px;" >头像</label >
                </td>
                <td style="width:85%">
                    <div class="container" id="crop-avatar" data-role="content" style="margin: -25px 0px -25px 0px" >
                        <div class="avatar-view headimage" title="Change the avatar">
                            <img src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/<?php echo $userId;?>/head.png?t=<?php echo rand(10,100); ?>" id='myhead' alt="点击上传头像" onclick="chooseImages()">
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <input type="hidden" id="uid" value="<?php echo $userId;?>">
        
        <form id="myinfoForm" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=updateMyinfo">
            <table>
                <tr>
                    <td style="width:20%">
                        <label for="name"  style="font-size:12px;" >昵称</label >
                    </td>
                    <td style="width:80%">
                        <input type="text" name="name" id="name" value="<?php echo isset($existedUser['name']) ? $existedUser['name']: ''; ?>">
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td style="width:20%">
                        <label for="signature"  style="font-size:12px;" >个性签名</label>
                    </td>
                    <td style="width:80%">
                        <input type="text" name="signature" id="signature" value="<?php echo isset($existedUser['signature']) ? $existedUser['signature']: ''; ?>">
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td style="width:20%">
                        <label for="weixin"  style="font-size:12px;" >微信号</label>
                    </td>
                    <td style="width:80%">
                        <input type="text" name="weixin" id="weixin" value="<?php echo isset($existedUser['weixin']) ? $existedUser['weixin']: ''; ?>">
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td style="width:20%">
                        <label  style="font-size:12px;" >我是<label/>
                    </td>
                    <td style="width:80%">
                        <fieldset data-role="controlgroup" data-type="horizontal" data-mini="true" data-theme="e">
                            <input name="gender" id="radio-choice-c" value="1" <?php if ($existedUser['gender'] == 1) {echo 'checked="true"'; } ?> type="radio">
                            <label for="radio-choice-c">男生</label>
                            <input name="gender" id="radio-choice-d" value="2" <?php if ($existedUser['gender'] == 2) {echo 'checked="true"'; } ?> type="radio">
                            <label for="radio-choice-d">女生</label>
                        </fieldset>
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td style="width:20%">
                        <label for="name"  style="font-size:12px;" >电子邮箱</label>
                    </td>
                    <td style="width:80%">
                        <input type="text" name="email" id="email" value="<?php echo isset($existedUser['email']) ? $existedUser['email']: ''; ?>">
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <label for="description" style="font-size:12px;" >自我介绍</label>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <textarea cols="30" rows="8" name="description" id="description" data-mini="true"><?php echo isset($existedUser['description']) ? $existedUser['description']: ''; ?></textarea>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <label for="methodTags" style="font-size:12px;" >
                            <a href="#tagpopup" data-rel="popup" data-theme="f">选取或填写特长</a>
                        </label>
                    </td>
                </tr>
            </table>
            <table style="margin: -10px 0px 0px 0px" >
                <tr>
                    <td style="width:95%">
                        <ul id="methodTags"></ul>
                        <input name="mytags" id="mytags" value="<?php echo isset($existedUser['tag']) ? $existedUser['tag']: ''; ?>" type="hidden">
                    </td>
                </tr>
            </table>

            <input type="submit" name="yzsubmit" id="yzsubmit" value="发布" data-theme="c" >
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
                    required: true,
                    minlength: 2
                },
                signature: {
                    required: true,
                    minlength: 2
                },
                email: {
                    email: true
                },
                description: {
                    required: true,
                    minlength: 4
                }
                
            },
            messages: {
                name: {
                    required: "昵称不能为空",
                    minlength: "昵称长度不能小于 2 个字"
                },
                signature: {
                    required: "个性签名不能为空",
                    minlength: "个性签名长度不能小于 2 个字"
                },
                email: {
                    email: "邮箱格式不正确"
                },
                description: {
                    required: "自我介绍不能为空",
                    minlength: "自我介绍长度不能小于 4 个字"
                    
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

