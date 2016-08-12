<?php?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>易知海外</title>
    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="../../resource/js/rater/rater.min.js"></script>

    <script src="../../resource/js/camera/jquery.min.js"></script>
    <script src="../../resource/js/camera/jquery.easing.1.3.js"></script>
    <script src="../../resource/js/camera/camera.min.js"></script>
    <script src="../../resource/js/camera/jquery.mobile.customized.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/camera/camera.css" type="text/css" media="all">

    <style>
        .rate-base-layer
        {
            color: #aaa;
        }
        .rate-hover-layer
        {
            color: orange;
        }
        .rate-select-layer
        {
            color:orange;
        }
        /*.ui-btn { border: none !important; }*/
        a {
            outline:0;
        }
        body {
            margin: 0;
            padding: 0;
        }
        a {
            color: #09f;
        }
        a:hover {
            text-decoration: none;
        }
        #back_to_camera {
            clear: both;
            display: block;
            height: 80px;
            line-height: 40px;
            padding: 20px;
        }
        .fluid_container {
            margin: 0 auto;
            max-width: 1000px;
            width: 100%;
        }
        div.headimage {
            height: 65px;
            width: 65px;
        }
        h5{ color:#33c8ce}
        p{ font-size:14px;}
        table{ table-layout : fixed; width:100% }
    </style>

    <script>
        jQuery(function(){
            jQuery('#camera_wrap_1').camera({
                thumbnails: false,
                loader: 'none',
                portrait :false,
                pagination : false,
                height: '120px',
                navigation : false,
                playPause : false,
                transPeriod: 1000,
                fx:'scrollHorz',
                loaderPadding: '10px',
                onEndTransition: function(){
                    var ind = $('.camera_target .cameraSlide.cameracurrent').index();
                    //alert(ind);
                }

            });
        });
    </script>
</head>

<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>选择城市</h1>
    </div>
    I am nothing but a php with a little js css included

    <?php include '../common/footer.php';?>
</div>

</body>
</html>
