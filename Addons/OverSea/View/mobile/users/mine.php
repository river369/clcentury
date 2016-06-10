<?php
session_start();
$customerid = $_SESSION['signedUser'];
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
    <link rel="stylesheet" href="../../resource/style/cropper/cropper.min.css" />
    <link rel="stylesheet" href="../../resource/style/cropper/main.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />

</head>
<body>

<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>我的</h1>
    </div>

    <div class="container" id="crop-avatar" data-role="content">
        <h3>我的头像:</h3>
        <!-- Current avatar -->
        <div class="avatar-view" title="Change the avatar">
            <img src="http://clcentury.oss-cn-beijing.aliyuncs.com/yzphoto/heads/<?php echo $customerid;?>/head.png" id='myhead' alt="Avatar" onclick="chooseImages()">
        </div>
        <input type="hidden" id="uid" value="<?php echo $customerid;?>">

        <h3>服务信息:</h3>
        <ul data-role="listview" data-inset="true">
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=myinfo&customerid=<?php echo $customerid;?>" rel="external">
                    个人信息
                </a>
            </li>
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=publishRealName&userid=<?php echo $customerid;?>" rel="external">
                    申请实名认证
                </a>
            </li>
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=publishService&sellerid=<?php echo $customerid;?>" rel="external">
                    发布易知服务
                </a>
            </li>
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=myServices&sellerid=<?php echo $customerid;?>&status=20" rel="external">
                    我发布的易服务知列表
                </a>
            </li>
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=queryCustomerOrders&customerid=<?php echo $customerid;?>&condition=0,10" rel="external">
                    我购买的服务订单
                </a>
            </li>
            <li>
                <a href="../../../Controller/AuthUserDispatcher.php?c=querySellerOrders&sellerid=<?php echo $customerid;?>&condition=10" rel="external">
                    购买我服务的订单
                </a>
            </li>
            <li>
                <a href="../../../Controller/ClearSessions.php" rel="external">
                    退出登录
                </a>
            </li>
        </ul>
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

    <div data-role="footer" data-position="fixed" data-theme="c">
        <h4>Copyright (c) 2016 .</h4>
    </div>
</div>
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
</body>
</html>
