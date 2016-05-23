<?php
session_start();
$userData= $_SESSION['userData'];

$objArray;
$objkey='objArray'.$userData['id'];
if (isset($_SESSION[$objkey])){
    $objArray = $_SESSION[$objkey] ;
}
$imageurl='http://clcentury.oss-cn-beijing.aliyuncs.com/';

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
    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile.theme-1.4.5.min.css" />
    <script type="text/javascript" src="../../resource/js/jquery/jquery.simplyscroll.min.js"></script>

    <link rel="stylesheet" href="../../resource/style/jquery/jquery.mobile-1.4.5.min.css" />
    <link rel="stylesheet" href="../../resource/style/jquery/jquery.simplyscroll.css" media="all" type="text/css">

    <style type="text/css">
        /* Container DIV - automatically generated */
        .simply-scroll-container {
            position: relative;
        }

        /* Clip DIV - automatically generated */
        .simply-scroll-clip {
            position: relative;
            overflow: hidden;
        }

        /* UL/OL/DIV - the element that simplyScroll is inited on
        Class name automatically added to element */
        .simply-scroll-list {
            overflow: hidden;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .simply-scroll-list li {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .simply-scroll-list li img {
            border: none;
            display: block;
        }

        /* Custom class modifications - adds to / overrides above

        .simply-scroll is default base class */

        /* Container DIV */
        .simply-scroll {
            width: 576px;
            height: 200px;
            margin-bottom: 1em;
        }

        /* Clip DIV */
        .simply-scroll .simply-scroll-clip {
            width: 576px;
            height: 200px;
        }

        /* Explicitly set height/width of each list item */
        .simply-scroll .simply-scroll-list li {
            float: left; /* Horizontal scroll only */
            width: 290px;
            height: 200px;
        }
    </style>

</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
    <div data-role="header" data-position="fixed">
        <h1><?php echo $userData['name']; ?>的主页</h1>
    </div>

    <div data-role="content">
        <h5>服务宣言:</h5>
        <p><?php echo $userData['description']; ?></p>
    </div>

    <ul id="scroller">
        <?php foreach ($objArray as $obj) { ?>
            <li><img src="<?php echo $imageurl.$obj; ?>" width="290" height="200"></li>
        <?php } ?>
    </ul>

    <div data-role="content">
        <h5>服务信息:</h5>
        <ul data-role="listview" data-inset="true">
            <li><?php echo $userData['stars']; ?>星服务 <span class="ui-li-count">6次咨询</span></li>
            <li>服务地点:<?php echo $userData['servicearea']; ?> <span class="ui-li-count">价格:￥<?php echo $userData['serviceprice']; ?>/小时</span></li>
        </ul>
    </div>
    <div data-role="content">
        <h5>特长</h5>
        <div class="ui-grid-a">
            <?php $tags = $userData['tag'];
            $tagsArray = explode(',',$tags);
            $loc = 'a';
            foreach ($tagsArray as $tag){ ?>
                <div class="ui-block-<?php echo $loc;?>"><a href="#" class="ui-shadow ui-btn ui-corner-all ui-mini"><?php echo $tag;?></a></div>
            <?php
                $loc = $loc=='a'? 'b' : 'a';
            } ?>
        </div>
    </div>


    <div data-role="footer" data-position="fixed">
        <div data-role="navbar">
            <ul>
                <li><a href="../orders/submitorder.html" rel="external" >聊聊看</a></li>
                <li><a href="../orders/submitorder.html" rel="external" >购买</a></li>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function($) {
        $(function() { //on DOM ready
            $("#scroller").simplyScroll();
        });
    })(jQuery);
</script>

</body>
</html>

