<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/9
 * Time: 21:54
 */
session_start();
$usersData= $_SESSION['usersData'];
$serviceType = isset($_SESSION['servicetype'])? $_SESSION['servicetype'] : 1;

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
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外" data-theme="d">
    <div data-role="header" data-position="fixed" data-theme="c">
        <a href="#nav-panel"data-icon="home">地球</a>
        <h1>发现</h1>
        <a href="../query/search.html" rel="external" data-icon="search">搜索</a>
    </div>

    <div role="main" class="ui-content jqm-content jqm-fullwidth">
        <div data-role="navbar">
            <ul>
                <li><a href="../../../Controller/Discover.php?servicetype=1" rel="external" <?php if ($serviceType == 1) { ?> class="ui-btn-active" <?php } ?> >旅游</a></li>
                <li><a href="../../../Controller/Discover.php?servicetype=2" rel="external" <?php if ($serviceType == 2) { ?> class="ui-btn-active" <?php } ?>>留学</a></li>
            </ul>
        </div><!-- /navbar -->
            <?php
                foreach($usersData as $key => $userData)
                {
            ?>
        <ul data-role="listview" data-inset="true">
            <li data-role="list-divider"><?php echo $userData['stars']?>星服务 <span class="ui-li-count">6次咨询</span></li>
            <li>
                <a href="../../../Controller/FreelookDispatcher.php?c=sellerdetails&sellerid=<?php echo $userData['id']; ?>" rel="external">
                    <img class="weui_media_appmsg_thumb" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAMAAAAOusbgAAAAeFBMVEUAwAD///+U5ZTc9twOww7G8MYwzDCH4YcfyR9x23Hw+/DY9dhm2WZG0kbT9NP0/PTL8sux7LFe115T1VM+zz7i+OIXxhes6qxr2mvA8MCe6J6M4oz6/frr+us5zjn2/fa67rqB4IF13XWn6ad83nxa1loqyirn+eccHxx4AAAC/klEQVRo3u2W2ZKiQBBF8wpCNSCyLwri7v//4bRIFVXoTBBB+DAReV5sG6lTXDITiGEYhmEYhmEYhmEYhmEY5v9i5fsZGRx9PyGDne8f6K9cfd+mKXe1yNG/0CcqYE86AkBMBh66f20deBc7wA/1WFiTwvSEpBMA2JJOBsSLxe/4QEEaJRrASP8EVF8Q74GbmevKg0saa0B8QbwBdjRyADYxIhqxAZ++IKYtciPXLQVG+imw+oo4Bu56rjEJ4GYsvPmKOAB+xlz7L5aevqUXuePWVhvWJ4eWiwUQ67mK51qPj4dFDMlRLBZTqF3SDvmr4BwtkECu5gHWPkmDfQh02WLxXuvbvC8ku8F57GsI5e0CmUwLz1kq3kD17R1In5816rGvQ5VMk5FEtIiWislTffuDpl/k/PzscdQsv8r9qWq4LRWX6tQYtTxvI3XyrwdyQxChXioOngH3dLgOFjk0all56XRi/wDFQrGQU3Os5t0wJu1GNtNKHdPqYaGYQuRDfbfDf26AGLYSyGS3ZAK4S8XuoAlxGSdYMKwqZKM9XJMtyqXi7HX/CiAZS6d8bSVUz5J36mEMFDTlAFQzxOT1dzLRljjB6+++ejFqka+mXIe6F59mw22OuOw1F4T6lg/9VjL1rLDoI9Xzl1MSYDNHnPQnt3D1EE7PrXjye/3pVpr1Z45hMUdcACc5NVQI0bOdS1WA0wuz73e7/5TNqBPhQXPEFGJNV2zNqWI7QKBd2Gn6AiBko02zuAOXeWIXjV0jNqdKegaE/kJQ6Bfs4aju04lMLkA2T5wBSYPKDGF3RKhFYEa6A1L1LG2yacmsaZ6YPOSAMKNsO+N5dNTfkc5Aqe26uxHpx7ZirvgCwJpWq/lmX1hA7LyabQ34tt5RiJKXSwQ+0KU0V5xg+hZrd4Bn1n4EID+WkQdgLfRNtvil9SPfwy+WQ7PFBWQz6dGWZBLkeJFXZGCfLUjCgGgqXo5TuSu3cugdcTv/HjqnBTEMwzAMwzAMwzAMwzAMw/zf/AFbXiOA6frlMAAAAABJRU5ErkJggg==" alt="">
                    <h2><?php echo $userData['name']?></h2>
                    <p style="white-space:pre-wrap;"><?php echo $userData['description']?></p>
                    <p class="ui-li-aside">￥<?php echo $userData['serviceprice']?>/小时</p>
                </a>
            </li>
            <li data-role="list-divider">
                <p>
            <?php $tags = $userData['tag'];
                $tagsArray = explode(',',$tags);
                foreach ($tagsArray as $tag){ ?>
                    <a href="javascript:alert('developing...');"> <?php echo $tag; ?></a>
            <?php } ?>
                </p>
            </li>
        </ul>
            <?php } ?>
    </div>

    <div data-role="footer" data-position="fixed" data-theme="c">
        <h4>Copyright (c) 2016 .</h4>
    </div>

    <div data-role="panel" data-position-fixed="true" data-display="push" data-theme="o" id="nav-panel">
        <ul data-role="listview">
            <li data-role="list-divider" data-icon="delete"><a href="#" data-rel="close">返回</a></li>
            <li data-role="list-divider">美国</li>
            <li><a onclick="alert();" data-rel="close" >西雅图</a></li>
            <li><a href="../common/location.html" rel="external" data-rel="close" >西雅图</a></li>
            <li><a href="./discover.html" rel="external" data-rel="close" >西雅图</a></li>
            <li><a>西雅图</a></li>
            <li><a>西雅图</a></li>
            <li><a>西雅图</a></li>
            <li><a>西雅图</a></li>
            <li><a>西雅图</a></li>
            <li><a>西雅图</a></li>
            <li><a>西雅图</a></li>
            <li><a>西雅图</a></li>
            <li><a>西雅图</a></li>
            <li><a>西雅图</a></li>
            <li><a>西雅图</a></li>
            <li data-role="list-divider">中国</li>
            <li><a>北京市</a></li>
            <li><a>天津市</a></li>
            <li><a>上海市</a></li>
            <li><a>重庆市</a></li>
            <li><a>北京市</a></li>
            <li><a>天津市</a></li>
            <li><a>上海市</a></li>
            <li><a>重庆市</a></li>
            <li><a>北京市</a></li>
            <li><a>天津市</a></li>
            <li><a>上海市</a></li>
            <li><a>重庆市</a></li>
        </ul>
    </div><!-- /panel -->
</div>
</body>
</html>

