<?php
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
    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <style>
        h5{ color:#33c8ce}
        p{ font-size:18px;}
    </style>

</head>
<body>

<div data-url="panel-fixed-page1" data-role="page" data-theme="a" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>我的</h1>
    </div>

    <div class="container" id="crop-avatar" data-role="content">
        <h5>联系方式</h5>
        <div style="margin: -20px 0px -20px 0px">
            <ul data-role="listview" data-inset="true" data-theme="e">
                <li>
                    <a href="mailto:contact@clcentury.com"><p style="margin: 0px 0px 0px 0px">邮箱: contact@clcentury.com</p></a>
                </li>
                <li>
                    <a href="tel:13520143483"><p style="margin: 0px 0px 0px 0px">电话:400</p></a>
                </li>
                <li>
                    <a href="./suggestion.php" rel="external">
                        <p style="margin: 0px 0px 0px 0px">留言</p>
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <?php include '../common/footer.php';?>
</div>
<script>
    $(document).ready(function(){
        $("img").error(function () {
            $(this).attr("src", "../../resource/images/head_default.jpg");
        });
    });
</script>
</body>
</html>
