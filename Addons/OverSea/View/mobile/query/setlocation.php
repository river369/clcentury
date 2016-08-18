<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$servicearea = '地球';
if (isset($_SESSION ['servicearea'])){
    $servicearea = $_SESSION ['servicearea'];
} else if (isset($_SESSION ['userSetting'])){
    $userSetting = $_SESSION ['userSetting'];
    if (isset($userSetting['selected_service_area'])){
        $servicearea = $userSetting['selected_service_area'];
    }
}
$citites = $_SESSION['citites'];
$countries = $_SESSION['countries'];
$isDiscover = 1;
?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>易知海外</title>

    <script src="../../resource/js/jquery/jquery-1.11.1.min.js"></script>
    <script src="../../resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
    <script src="../../resource/js/bootstrap/bootstrap.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/themes/my-theme.min.css" />
    <link rel="stylesheet" href="../../resource/style/bootstrap/bootstrap.min.css">
    <link href="../../resource/style/sidebar/simple-sidebar.css" rel="stylesheet">
    <link href="../../resource/style/city/style.css" rel="stylesheet">
    <style>
        p{ font-size:14px; white-space:pre-wrap; word-break:break-all}
        label{ color:#01A4B5; font-size:14px;}
        h5{ color:#01A4B5;}
    </style>
</head>

<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed" data-theme="c">
        <h1>选择城市</h1>
    </div>

    <!-- wrapper -->
    <div id="wrapper">
        <input type="hidden" name="display_sequence" id="display_sequence"  value="1"/>
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav nav nav-tabs" id="sidebar-nav">
                <li class="sidebar-brand">
                    <h5>地区</h5>
                </li>
                <?php
                foreach($countries as $display_sequence => $country){?>
                    <li <?php echo $display_sequence == 1 ? 'class="active"' : '' ?>>
                        <a href="#tab<?php echo $display_sequence?>" sequence="<?php echo $display_sequence?>"><?php echo $country?></a>
                    </li>
                <?php }?>
                <li class=''>
                    <a href="#tab" sequence="3" onclick="setArea()">地球</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div class="tab-content">
            <?php
            foreach($countries as $display_sequence => $country){?>
                <div class="tab-pane" id="tab<?php echo $display_sequence;?>">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <h5>城市</h5>
                                <div class="container_cn" style="z-index: 9999; ">
                                    <div class="city">
                                        <?php
                                        foreach($citites[$display_sequence] as $pinyin => $citynames){?>
                                            <div class="city-list"><span class="city-letter" id="<?php echo $pinyin.$display_sequence; ?>"><?php echo $pinyin; ?></span>
                                                <?php
                                                foreach($citites[$display_sequence][$pinyin] as $key => $cityname){?>
                                                    <p style="margin: 0px 0px 0px 0px"><a href="../../../Controller/FreelookDispatcher.php?c=setLocation&servicearea=<?php echo $cityname;?>" class="ui-mini" rel="external"><?php echo $cityname;?></a></p>
                                                <?php }?>
                                            </div>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }?>

        </div>
        <!-- Page Content -->
    </div>
    <!-- wrapper -->

    <div class="letter">
        <ul>
            <li><a href="javascript:;">A</a></li>
            <li><a href="javascript:;">B</a></li>
            <li><a href="javascript:;">C</a></li>
            <li><a href="javascript:;">D</a></li>
            <li><a href="javascript:;">E</a></li>
            <li><a href="javascript:;">F</a></li>
            <li><a href="javascript:;">G</a></li>
            <li><a href="javascript:;">H</a></li>
            <li><a href="javascript:;">J</a></li>
            <li><a href="javascript:;">K</a></li>
            <li><a href="javascript:;">L</a></li>
            <li><a href="javascript:;">M</a></li>
            <li><a href="javascript:;">N</a></li>
            <li><a href="javascript:;">P</a></li>
            <li><a href="javascript:;">Q</a></li>
            <li><a href="javascript:;">R</a></li>
            <li><a href="javascript:;">S</a></li>
            <li><a href="javascript:;">T</a></li>
            <li><a href="javascript:;">W</a></li>
            <li><a href="javascript:;">X</a></li>
            <li><a href="javascript:;">Y</a></li>
            <li><a href="javascript:;">Z</a></li>
        </ul>
    </div>
    <!-- /#wrapper -->

    <?php include '../common/footer.php';?>
</div>

<script src="../../resource/js/city/zepto.js"></script>
<script>
    $(document).ready(function(){
        $('#sidebar-nav a:last').tab('show');
        $('#sidebar-nav li:eq(1) a').tab('show');
    });
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

    $('#sidebar-nav a').click(function (e) {
        e.preventDefault();
        $('#display_sequence').val($(this).attr('sequence'));
        $(this).tab('show');
    });
    function setArea() {
        window.location.href="../../../Controller/FreelookDispatcher.php?c=setLocation&servicearea=地球";
    };
    $('body').on('click', '.letter a', function () {
        var s = $(this).html();
        var seq = $('#display_sequence').val();
        $(window).scrollTop($('#' + s + seq).offset().top - 100);
    });
</script>

</body>
</html>