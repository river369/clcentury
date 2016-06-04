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
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Model\CropAvatar;

require dirname(__FILE__).'/PicCrop.php';

class YZPicBo
{
    public function __construct() {
    }

    public function handleHeads() {
        $userID = $_SESSION['signedUser'];
        Logs::writeClcLog("YZPicBo,handleHeads(),"."Userid=".$userID);
        $crop = new CropAvatar( $userID,
            isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
            isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
            isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null
        );
        if (is_null($crop -> getMsg())
            && !is_null($crop -> getResult()) && file_exists($crop -> getResult())) {
            Logs::writeClcLog("YZPicBo,handleHeads(),"."Uploading to OSS");
            self::savePictureFromFile($crop -> getResult(), $userID);
        }

        $response = array(
            'status'  => 200,
            'msg' => $crop -> getMsg(),
            'result' => $crop -> getResult()
        );
        Logs::writeClcLog("YZPicBo,handleHeads(),"."msg=".$crop -> getMsg());
        Logs::writeClcLog("YZPicBo,handleHeads(),"."result=".$crop -> getResult());
        echo json_encode($response);

        exit;
        
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
                self::savePictureFromWeixin($serverid, $userID, $i);
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
        //$url = 'http://'.$_SERVER['HTTP_HOST']."/weiphp/Addons/OverSea/View/mobile/users/mine1.html";
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
            $_SESSION['objArray'.$userID] = $objArray;
        }
    }
    // 获取图片地址
    function savePictureFromWeixin($media_id, $userID, $i){
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $access_token = WeixinHelper::getAccessTokenWithLocalFile();
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
        $object = "yzphoto/pics/".$userID."/".date('YmdHis')."_".$i.".jpg";
        $options = array();
        OSSHelper::putObject($object, file_get_contents($url), $options);
        return ;
    }

    function savePictureFromFile($path, $userID){
        $object = "yzphoto/heads/".$userID."/head.png";
        $options = array();
        OSSHelper::uploadFile($object, $path, $options);
        return ;
    }

}
