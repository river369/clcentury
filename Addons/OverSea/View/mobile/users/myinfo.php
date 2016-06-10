<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
session_start();
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
    <!--
    <style>
        label{
            display: inline-block;
            padding: 0 10px;
            vertical-align: middle;
        }
        input{
            outline: none;
            border: 1px solid rgb(216, 216, 216);
            padding: 2px 10px 2px 10px;
        }
        input[type="text"]{
            height: 14px;
            line-height: 14px;
            border-radius: 5px;
            padding:10px 10px;
            vertical-align: middle;
            color:#666;
        }
        input[type="button"]{
            padding: 0px 10px;
            height:40px;
        }
    </style>
    -->
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

    <div data-role="content">
        <ul data-role="listview" data-inset="true">
            <li>您的用户状态: <span class="ui-li-count"><?php echo $statusString; ?></span></li>
            <?php if ($status == 40) {?>
                <li>拒绝原因: <span class="ui-li-count"><?php echo $existedUser['check_reason']; ?></span></li>
            <?php } ?>
        </ul>
        <form id="submityz" data-ajax="false" method="post" action="../../../Controller/AuthUserDispatcher.php?c=updateMyinfo">
            <div data-role="fieldcontain">
                <label for="name" >您的昵称:</label>
                <input type="text" name="name" id="name" value="<?php echo isset($existedUser['name']) ? $existedUser['name']: ''; ?> ">
                </br>
                <label for="weixin">您的微信号:</label>
                <input type="text" name="weixin" id="weixin" value="<?php echo isset($existedUser['weixin']) ? $existedUser['weixin']: ''; ?> ">
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
                <input type="text" name="email" id="email" value="<?php echo isset($existedUser['email']) ? $existedUser['email']: ''; ?> ">
                </br>
                <label for="description">自我介绍:</label>
                <textarea cols="30" rows="8" name="description" id="description" data-mini="true">
                    <?php echo isset($existedUser['description']) ? $existedUser['description']: ''; ?>
                </textarea>
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

    <div data-role="footer" data-position="fixed" data-theme="c">
        <h4>Copyright (c) 2016 .</h4>
    </div>
</div>

<script>
    function tagwith(tag){
        $('#methodTags').tagit('createTag', tag);
        return false;
    }
</script>

</body>
</html>

