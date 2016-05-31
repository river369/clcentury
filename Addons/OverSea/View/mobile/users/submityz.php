<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 07:52
 */
session_start();
$existedUser = $_SESSION['signedUserInfo'] ;
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

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery-ui-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="../../resource/js/tag/tag-it.min.js"></script>

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
        <h1>易知信息</h1>
    </div>

    <div data-role="content">
        <form id="submityz" data-ajax="false" method="post" action="../../../Controller/SubmitYZ.php">
            <label for="name">您的姓名:</label>
            <input type="text" name="name" id="name" value="<?php echo isset($existedUser['name']) ? $existedUser['name']: ''; ?> ">
            <label for="weixin">您的微信号:</label>
            <input type="text" name="weixin" id="weixin" value="<?php echo isset($existedUser['weixin']) ? $existedUser['weixin']: ''; ?> ">

            <fieldset data-role="controlgroup">
                <legend>你的服务类型:</legend>
                <input name="service-1" id="service-1" type="checkbox" <?php if ($existedUser['servicetype'] == 1 || $existedUser['servicetype'] == 99999) {echo 'checked="true"'; } ?> >
                <label for="service-1">旅游</label>
                <input name="service-2" id="service-2" type="checkbox" <?php if ($existedUser['servicetype'] == 2 || $existedUser['servicetype'] == 99999) {echo 'checked="true"'; } ?>>
                <label for="service-2">留学</label>
            </fieldset>

            <label for="servicearea">您的服务地点:</label>
            <select name="servicearea" id="servicearea">
                <optgroup label="美国">
                    <option value="西雅图" <?php echo $existedUser['servicearea']=='西雅图'? 'selected = "selected"' : ''; ?> >西雅图</option>
                    <option value="旧金山" <?php echo $existedUser['servicearea']=='旧金山'? 'selected = "selected"' : ''; ?> >旧金山</option>
                    <option value="纽约" <?php echo $existedUser['servicearea']=='纽约'? 'selected = "selected"' : ''; ?> >纽约</option>
                    <option value="洛杉矶" <?php echo $existedUser['servicearea']=='洛杉矶'? 'selected = "selected"' : ''; ?> >洛杉矶</option>
                </optgroup>
                <optgroup label="中国">
                    <option value="北京市" <?php echo $existedUser['servicearea']=='北京市'? 'selected = "selected"' : ''; ?>>北京市</option>
                    <option value="上海市" <?php echo $existedUser['servicearea']=='上海市'? 'selected = "selected"' : ''; ?>>上海市</option>
                    <option value="天津市" <?php echo $existedUser['servicearea']=='天津市'? 'selected = "selected"' : ''; ?>>天津市</option>
                    <option value="重庆市" <?php echo $existedUser['servicearea']=='重庆市'? 'selected = "selected"' : ''; ?>>重庆市</option>
                </optgroup>
            </select>

            <label for="serviceprice">您的服务价格(￥/小时):</label>
            <input type="text" name="serviceprice" id="serviceprice" value="<?php echo isset($existedUser['serviceprice']) ? $existedUser['serviceprice']: ''; ?>" >

            <label for="description">自我介绍:</label>
            <textarea cols="30" rows="8" name="description" id="description" data-mini="true">
                <?php echo isset($existedUser['description']) ? $existedUser['description']: ''; ?>
            </textarea>

            <!--
            <input name="tags" id="methodTags" value="诚实守信,价格合理">
            -->
            <label for="methodTags">
                <a href="#tagpopup" data-rel="popup" class="ui-controlgroup-label ui-shadow ui-corner-all">选取或填写特长:</a>
            </label>
            <ul id="methodTags"></ul>
            <input name="mytags" id="mytags" value="<?php echo isset($existedUser['tag']) ? $existedUser['tag']: ''; ?>" type="hidden">
            
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

