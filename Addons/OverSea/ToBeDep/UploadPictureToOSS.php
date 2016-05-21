<?php

require dirname(__FILE__).'/../init.php';

use Addons\OverSea\Common\OSSHelper;
use Addons\OverSea\Common\WeixinHelper;

session_start();

$userID = $_SESSION['signedUser'];

if (isset($_GET ['serverids'])){
    $serverids = $_GET ['serverids'];
    //echo $serverids;
    $serveridsArray = explode(',',$serverids);
    $i=1;
    foreach ($serveridsArray as $serverid){
        //echo $i;
        getmedia($appId, $appsecret, $serverid, $userID,$i);
        $i++;
    }
}

// 获取图片地址
function getmedia($appId,$appsecret, $media_id, $userID, $i){
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $access_token = WeixinHelper::getAccessTokenWithLocalFile();
    $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
    $object = "yzphoto/pics/".$userID."/".$i.".jpg";
    $options = array();
    OSSHelper::putObject($object, file_get_contents($url), $options);
    return ;
}