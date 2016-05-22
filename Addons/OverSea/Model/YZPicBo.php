<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/18
 * Time: 10:08
 */

namespace Addons\OverSea\Model;
use Addons\OverSea\Common\OSSHelper;
use Addons\OverSea\Common\WeixinHelper;

class YZPicBo
{
    public function __construct() {
    }

    public function handlePics() {
        $userID = $_SESSION['signedUser'];

        // upload image if need to
        if (isset($_GET ['serverids'])){
            $serverids = $_GET ['serverids'];
            //echo $serverids;
            $serveridsArray = explode(',',$serverids);
            $i=1;
            foreach ($serveridsArray as $serverid){
                self::getmedia($serverid, $userID, $i);
                $i++;
            }
        }

        // delete image if need
        if (isset($_GET ['objtodelete'])){
            $obj = $_GET ['objtodelete'];
            //echo $obj;
            OSSHelper::deleteObject($obj);
            //exit(1);
        }

        // Create sessions
        $_SESSION['$appid'] = WeixinHelper::getAppid();
        $nonceStr = WeixinHelper::make_nonceStr();
        $_SESSION['$nonceStr'] = $nonceStr;
        $timestamp = time();
        $_SESSION['$timestamp'] = $timestamp;
        $jsapi_ticket = WeixinHelper::make_ticket();
        $url = 'http://'.$_SERVER['HTTP_HOST']."/weiphp/Addons/OverSea/View/mobile/users/yzpictures.php";
        $signature = WeixinHelper::make_signature($nonceStr,$timestamp,$jsapi_ticket,$url);
        $_SESSION['$signature'] = $signature;
        
        // list data
        $object = "yzphoto/pics/".$userID."/";
        //echo $object;
        $objectList = OSSHelper::listObjects($object);
        $objArray = array();
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                $objArray[] = $objectInfo->getKey();
            }
            $_SESSION['$objArray'] = $objArray;
        }
    }
    // 获取图片地址
    function getmedia($media_id, $userID, $i){
        echo "fk";
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $access_token = WeixinHelper::getAccessTokenWithLocalFile();
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
        $object = "yzphoto/pics/".$userID."/".date('YmdHis')."_".$i.".jpg";
        $options = array();
        OSSHelper::putObject($object, file_get_contents($url), $options);
        return ;
    }

}
