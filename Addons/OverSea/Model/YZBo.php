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
use Addons\OverSea\Common\HttpHelper;
use Addons\OverSea\Model\UsersDao;
use Addons\OverSea\Model\YZDao;

require dirname(__FILE__).'/PicCrop.php';

class YZBo
{
    public function __construct() {
    }

    public function getCurrentYZ() {
        $sellerid = HttpHelper::getVale('sellerid');
        $service_id = HttpHelper::getVale('service_id');
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$sellerid." ".$service_id);
        self::getCurrentSellerInfo($sellerid);
        self::getServiceInfo($service_id);
        self::prepareWeixinPicsParameters();
        self::getYZPictures($sellerid, $service_id);
    }

    /**
     * Get a user by seller id
     */
    public function getCurrentSellerInfo($sellerid) {
        unset($_SESSION['sellerData']);
        $sellerData = UsersDao::getUserById($sellerid);
        $_SESSION['sellerData']= $sellerData;
    }

    /**
     * Get a user by seller id
     */
    public function getServiceInfo($service_id) {
        unset($_SESSION['serviceData']);
        if (!is_null($service_id) && strlen($service_id) >0 ){
            $serviceData = YZDao::getYZById($service_id);
            $_SESSION['serviceData']= $serviceData;
        } else {
            $sellerData = $_SESSION['sellerData'] ;
            $serviceData = array();
            $serviceData['seller_id'] = $sellerData['id'];
            $serviceData['seller_name'] = $sellerData['name'];
            $serviceData['status'] = 0;
            $serviceid = YZDao::insertYZ($serviceData);
            $serviceData['id'] = $serviceid;
            $_SESSION['serviceData']= $serviceData;
            if ($serviceid <= 0) {
                $_SESSION['submityzstatus'] = '失败';
                header('Location:'."../View/mobile/users/submityzsuccess.php");
                exit;
            }
        }
    }

    /**
     * YZ 图片处理
     */
    public function prepareWeixinPicsParameters() {
        // Create sessions
        $_SESSION['$appid'] = WeixinHelper::getAppid();
        $nonceStr = WeixinHelper::make_nonceStr();
        $_SESSION['$nonceStr'] = $nonceStr;
        $timestamp = time();
        $_SESSION['$timestamp'] = $timestamp;
        $jsapi_ticket = WeixinHelper::make_ticket();
        //$url = 'http://'.$_SERVER['HTTP_HOST']."/weiphp/Addons/OverSea/View/mobile/users/mine1.html";
        //$url = 'http://'.$_SERVER['HTTP_HOST']."/weiphp/Addons/OverSea/View/mobile/users/yzpictures.php";
        $url = 'http://'.$_SERVER['HTTP_HOST']."/weiphp/Addons/OverSea/View/mobile/service/publishyz.php";
        $signature = WeixinHelper::make_signature($nonceStr,$timestamp,$jsapi_ticket,$url);
        $_SESSION['$signature'] = $signature;
    }

    /*
    * get picture info by seller id
    */
    public function getYZPictures($sellerid, $service_id) {
        unset($_SESSION['objArray'.$sellerid]);

        // list data
        $object = "yzphoto/pics/".$sellerid."/".$service_id."/";
        //echo $object;
        $objectList = OSSHelper::listObjects($object);
        $objArray = array();
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                $objArray[] = $objectInfo->getKey();
            }

            $_SESSION['objArray'.$sellerid] = $objArray;
        }
    }

    /**
     * YZ 图片处理
     */
    public function publishServicePics() {
        $userID = $_SESSION['signedUser'];
        $serviceId = $_SESSION['serviceData']['id'] ;
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__." userid=".$userID." serviceid=".$serviceId);
        // upload image if need to
        if (isset($_GET ['serverids'])){
            $serverids = $_GET ['serverids'];
            //echo $serverids;
            $serveridsArray = explode(',',$serverids);
            $i=1;
            foreach ($serveridsArray as $serverid){
                self::savePictureFromWeixin($serverid, $userID, $serviceId, $i);
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

        // list data
        $object = "yzphoto/pics/".$userID."/".$serviceId."/";
        //echo $object;
        $objectList = OSSHelper::listObjects($object);
        $objArray = array();
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                $objArray[] = $objectInfo->getKey();
                Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$objectInfo->getKey());
            }
        }
        echo json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
        exit;
    }
    
    /**
     * YZ 图片处理 to be delete
     */
    public function publishServicePicsbak() {
        $userID = $_SESSION['signedUser'];
        $serviceId = $_SESSION['yzData']['id'] ;

        // upload image if need to
        if (isset($_GET ['serverids'])){
            $serverids = $_GET ['serverids'];
            //echo $serverids;
            $serveridsArray = explode(',',$serverids);
            $i=1;
            foreach ($serveridsArray as $serverid){
                self::savePictureFromWeixin($serverid, $userID, $serviceId, $i);
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
        //$url = 'http://'.$_SERVER['HTTP_HOST']."/weiphp/Addons/OverSea/View/mobile/users/yzpictures.php";
        $url = 'http://'.$_SERVER['HTTP_HOST']."/weiphp/Addons/OverSea/View/mobile/service/publishyz.php";
        $signature = WeixinHelper::make_signature($nonceStr,$timestamp,$jsapi_ticket,$url);
        $_SESSION['$signature'] = $signature;
        
        // list data
        $object = "yzphoto/pics/".$userID."/".$serviceId."/";
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



    // 获yz取图片地址
    function savePictureFromWeixin($media_id, $userID, $yzId, $i){
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $access_token = WeixinHelper::getAccessTokenWithLocalFile();
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
        $object = "yzphoto/pics/".$userID."/".$yzId."/".date('YmdHis')."_".$i.".jpg";
        $options = array();
        OSSHelper::putObject($object, file_get_contents($url), $options);
        return ;
    }


    /**
     * 头像处理
     */
    public function handleHeads() {
        $userID = $_SESSION['signedUser'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",Userid=".$userID);
        $crop = new CropAvatar( $userID,
            isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
            isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
            isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null
        );
        if (is_null($crop -> getMsg())
            && !is_null($crop -> getResult()) && file_exists($crop -> getResult())) {
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",Uploading to OSS");
            self::savePictureFromFile($crop -> getResult(), $userID);
        }

        $response = array(
            'status'  => 200,
            'msg' => $crop -> getMsg(),
            'result' => $crop -> getResult()
        );
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",msg=".$crop -> getMsg());
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",result=".$crop -> getResult());
        echo json_encode($response);

        exit;

    }
    function savePictureFromFile($path, $userID){
        $object = "yzphoto/heads/".$userID."/head.png";
        $options = array();
        OSSHelper::uploadFile($object, $path, $options);
        return ;
    }

}
