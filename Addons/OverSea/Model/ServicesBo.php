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
use Addons\OverSea\Model\UserInfosDao;
use Addons\OverSea\Model\UserAccountsDao;
use Addons\OverSea\Model\QueryHistoryDao;
use Addons\OverSea\Model\ServicesDao;
use Addons\OverSea\Model\CommentsDao;
use Addons\OverSea\Model\CitiesDao;
use Addons\OverSea\Model\CitiesTagDao;
use Addons\OverSea\Model\AdvertiseDao;

require dirname(__FILE__).'/PicCrop.php';

class ServicesBo
{
    public function __construct() {
    }

    //=======================================================//
    //  Services                                             //
    //=======================================================//
    /**
     * Prepare for service info for service create or update
     */
    public function getCurrentService() {
        $sellerid = HttpHelper::getVale('sellerid');
        $userID = $_SESSION['signedUser'];
        if (is_null($sellerid) || strlen($sellerid) == 0 ){
            $sellerid = $userID;
        }
        $sellerData = self::getCurrentSellerInfo($sellerid);
        $sellerPayAccountsDao = new SellerPayAccountsDao();
        $activeAccount = $sellerPayAccountsDao -> getPayAccountsByUserIdStatus($sellerid, 1);

        $ready = 1;
        $errMsg = "用户信息不完善,无法创建易知。";
        $errLinkName = null;
        $gotoLink = null;
        $sellerName = $sellerData['name'];
        $weixin = $sellerData['weixin'];
        if (!isset($sellerName) || strlen($sellerName) == 0 || !isset($weixin) || strlen($weixin) == 0){
            $ready = 0;
            $missedMyInfoMessage = "请前往'个人信息'页面填写";
            if(!isset($sellerName) || strlen($sellerName) == 0) {
                $missedMyInfoMessage = $missedMyInfoMessage . "昵称";
            }
            if(!isset($weixin) || strlen($weixin) == 0) {
                if(!isset($sellerName) || strlen($sellerName) == 0) {
                    $missedMyInfoMessage = $missedMyInfoMessage . "、";
                }
                $missedMyInfoMessage = $missedMyInfoMessage . "微信号";
            }
            $errMsg = $errMsg . $missedMyInfoMessage . "。";
            $gotoLink = "../../../Controller/AuthUserDispatcher.php?c=myinfo&customerid=".$sellerid;
            $errLinkName =  '去填写个人信息';
        }
        if (!isset($activeAccount['id'])) {
            $ready = 0;
            $errMsg = $errMsg .  "请前往'确认账号'页面选择卖家收款账号。";
            if (is_null($gotoLink)) {
                $gotoLink = "../../../Controller/AuthUserDispatcher.php?c=getSellerPayInfo&userid=" . $sellerid;
                $errLinkName =  '去选择卖家收款账号';
            } else {
                $_SESSION['nextGotoLink'] = $sellerid;
            }
        }
        if ($ready == 0) {
            $_SESSION['status'] = 'f';
            $_SESSION['message'] = $errMsg;
            $_SESSION['link_name'] = $errLinkName;
            $_SESSION['goto'] = $gotoLink;
            header('Location:../View/mobile/common/message.php');
            exit;
        }
        
        $service_id = HttpHelper::getVale('service_id');
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userId=".$userID.",sellerId=".$sellerid.",service_id=".$service_id);
        if (is_null($service_id) || strlen($service_id) == 0 ){
            $service = self::getSellerNotPublishedService($sellerid);
            if (!is_null($service)){
                $service_id = $service['service_id'];
            } else {
                self::createNewService();
            }
        } else {
            self::getServiceInfo($service_id);
        }

        self::getServicePictures($sellerid, $service_id);
        self::getAllCities();

        if (strpos($_SERVER['HTTP_USER_AGENT'], "MicroMessenger")){
            WeixinHelper::prepareWeixinPicsParameters("/weiphp/Addons/OverSea/View/mobile/service/publishservice.php");
        } else {
            header('Location:../View/mobile/service/publish_service_web.php');
            exit;
        }

    }

    /**
     * Prepare for service info for service read only when discover
     */
    public function getServiceById() {
        $service_id = $_GET['service_id'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_id=".$service_id);
        $service = self::getServiceInfo($service_id);
        $seller_id = $service['seller_id'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",$seller_id=".$seller_id);
        self::getCurrentSellerInfo($seller_id);
        self::getCommentForService($service_id);
        self::getServicePictures($seller_id, $service_id);
        self::getYPlusForService($seller_id, $service_id);
    }

    /**
     * Prepare for service info for order confirm
     */
    public function getServiceInfoById() {
        $service_id = HttpHelper::getVale('service_id');
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_id=".$service_id);
        $service = self::getServiceInfo($service_id);
    }

    /**
     * Get a user by seller id
     */
    public function getCurrentSellerInfo($sellerid) {
        unset($_SESSION['sellerData']);
        $userInfoDao = new UserInfosDao();
        $sellerData = $userInfoDao ->getByKv('user_id', $sellerid);
        $_SESSION['sellerData']= $sellerData;
        return $sellerData;
    }

    /**
     * Get a service by service id
     */
    private function getServiceInfo($service_id) {
        unset($_SESSION['serviceData']);
        if (!is_null($service_id) && strlen($service_id) >0 ){
            $serviceDao = new ServicesDao();
            $serviceData = $serviceDao ->getByKv('service_id', $service_id);
            $_SESSION['serviceData']= $serviceData;
            return $serviceData;
        }
    }

    private function getSellerNotPublishedService($sellerid) {
        unset($_SESSION['serviceData']);
        $serviceDao = new ServicesDao();
        $myServices = $serviceDao->getServiceByUserStatus($sellerid, 0);
        if (isset($myServices) && count($myServices) >0){
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",Got unPublishedService");
            $serviceData = $myServices[0];
            $_SESSION['serviceData']= $serviceData;
            return $serviceData;
        }
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",Not get unPublishedService");
        return null;
    }

    /**
     * Get comments for a service
     */
    public function getCommentForService($serviceId) {
        unset($_SESSION['commentsData']);
        $commentsDao = new CommentsDao();
        $commentsData = $commentsDao ->getCommentsByServiceId($serviceId);
        $_SESSION['commentsData']= $commentsData;
    }

    /*
    * get picture info by seller id
    */
    private function getServicePictures($sellerid, $service_id) {
        unset($_SESSION['objMain'],$_SESSION['objArray']);

        // list data
        $object = "yzphoto/pics/".$sellerid."/".$service_id."/";
        //echo $object;
        $objectList = OSSHelper::listObjects($object);
        $objArray = array();
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                if (strstr($objectInfo->getKey(), "main")){
                    $_SESSION['objMain'] = $objectInfo->getKey();
                } else {
                    $objArray[] = $objectInfo->getKey();
                }
            }
            $retObjArray =  json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",ret=".$retObjArray);
            $_SESSION['objArray'] = $objArray;
        }
    }
    /**
     * yz main picture 处理
     */
    public function publishServiceMainPic() {
        $userID = $_SESSION['signedUser'];
        if (!isset($_SESSION['serviceData'])){
            $response = array(
                'status'  => 500,
                'msg' => 'Session过期,请刷新页面重试!',
                'result' => ''
            );
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",response=".json_encode($response));
            echo json_encode($response);
            exit;
        }
        $serviceId = $_SESSION['serviceData']['service_id'] ;
        $userID = $_SESSION['signedUser'];
        $object = "yzphoto/pics/".$userID."/".$serviceId."/main.png";
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",Userid=".$userID);
        $crop = new CropAvatar( $userID,
            isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
            isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
            isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null
        );
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",msg=".$crop -> getMsg());
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",result=".$crop -> getResult());
        if (is_null($crop -> getMsg())
            && !is_null($crop -> getResult()) && file_exists($crop -> getResult())) {
            OSSHelper::uploadFile($object, $crop -> getResult(), array());
        }

        $response = array(
            'status'  => 200,
            'msg' => $crop -> getMsg(),
            'result' => $object
        );
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",response=".json_encode($response));
        echo json_encode($response);
        exit;
    }

    /**
     * YZ 图片处理
     */
    public function publishServicePics() {
        $userID = $_SESSION['signedUser'];
        if (!isset($_SESSION['serviceData'])){
            $response = array(
                'status'  => 500,
                'msg' => 'Session过期,请刷新页面重试!',
                'result' => ''
            );
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",response=".json_encode($response));
            echo json_encode($response);
            exit;
        }
        $serviceId = $_SESSION['serviceData']['service_id'] ;
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userid=".$userID." serviceid=".$serviceId);
        // upload image if need to
        if (isset($_GET ['serverids'])){
            $serverids = $_GET ['serverids'];
            //echo $serverids;
            $serveridsArray = explode(',',$serverids);
            $i=1;
            foreach ($serveridsArray as $serverid){
                $object = "yzphoto/pics/".$userID."/".$serviceId."/".date('YmdHis')."_".$i.".jpg";
                self::savePictureFromWeixin($serverid,$object);
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
                if (strstr($objectInfo->getKey(), "main")){
                } else {
                    $objArray[] = $objectInfo->getKey();
                }
            }
        }
        //$retJson =  json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
        //Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",retJson=".$retJson);
        echo json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
        exit;
    }

    public function publishServicePicsWeb() {
        $userID = $_SESSION['signedUser'];
        if (!isset($_SESSION['serviceData'])){
            $response = array(
                'status'  => 500,
                'msg' => 'Session过期,请刷新页面重试!',
                'result' => ''
            );
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",response=".json_encode($response));
            echo json_encode($response);
            exit;
        }
        $serviceId = $_SESSION['serviceData']['service_id'] ;
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userid=".$userID." serviceid=".$serviceId);

        // upload image if need to
        $filename = 'selected_file';
        $imagePath ="/home/www/uploads/".$userID."_".$serviceId."_".date('YmdHis').".jpg";
        if(!is_uploaded_file($_FILES[$filename]['tmp_name'])){//验证上传文件是否存在
            echo "请选择你想要上传的图片";
            exit;
        }
        if(!move_uploaded_file ($_FILES[$filename]['tmp_name'], $imagePath)) {//上传文件
            echo "上传文件失败";
            exit;
        }
        $object = "yzphoto/pics/".$userID."/".$serviceId."/".date('YmdHis').".jpg";
        OSSHelper::uploadFile($object, $imagePath, array());

        // list data
        $object = "yzphoto/pics/".$userID."/".$serviceId."/";
        //echo $object;
        $objectList = OSSHelper::listObjects($object);
        $objArray = array();
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                if (strstr($objectInfo->getKey(), "main")){
                } else {
                    $objArray[] = $objectInfo->getKey();
                }
            }
        }
        //$retJson =  json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
        //Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",retJson=".$retJson);
        echo json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
        exit;
    }
    
    // Copy file from weixin to oss
    private function savePictureFromWeixin($media_id, $object){
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $access_token = WeixinHelper::getAccessTokenWithLocalFile();
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
        $options = array();
        OSSHelper::putObject($object, file_get_contents($url), $options);
        return ;
    }

    /**
     * User update service info
     */
    public function publishServiceInfo(){
        if (!isset($_SESSION['serviceData'])){
            $_SESSION['status'] = 'f';
            $_SESSION['message'] = 'Session过期,请刷新页面重试!';
            $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=mine";
        } else {
            $serviceData = $_SESSION['serviceData'];
            $serviceData['status'] = 20;// change satus to waiting for approve
            $serviceData['service_area'] = isset($_POST ['service_area']) ? $_POST ['service_area'] : '';
            $serviceData['service_language'] = isset($_POST ['service_language']) ? $_POST ['service_language'] : '';
            $serviceData['service_name'] = isset($_POST ['service_name']) ? $_POST ['service_name'] : '';
            $serviceData['service_brief'] = isset($_POST ['service_brief']) ? $_POST ['service_brief'] : '';
            $serviceData['description'] = isset($_POST ['description']) ? trim($_POST ['description']) : '';
            $serviceData['service_type'] = $_POST ['service_type'];
            $serviceData['service_price_type'] = $_POST ['service_price_type'];
            $serviceData['service_price'] = isset($_POST ['service_price']) ? $_POST ['service_price'] : '';
            $serviceData['tag'] = (isset($_POST ['mytags']) && $_POST ['mytags']!='') ? trim($_POST ['mytags']) : ' ';

            try{
                $serviceDao = new ServicesDao();
                $serviceDao ->update($serviceData, $serviceData['id']);
                header('Location:../Controller/AuthUserDispatcher.php?c=myServices&sellerid='.$serviceData['seller_id'].'&status=20');
                exit;
            } catch (\Exception $e){
                $_SESSION['status'] = 'f';
                $_SESSION['message'] = '提交易知服务信息失败!';
                $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=mine";
            }
            //$_SESSION['$serviceData']= $serviceData;
            //header('Location:../View/mobile/users/submityzsuccess.php');
        }
    }

    /**
     * create new service
     */
    private function createNewService(){
        if(!isset($_SESSION['sellerData'])){
            $userID = $_SESSION['signedUser'];
            self::getCurrentSellerInfo($userID);
        }
        $sellerData = $_SESSION['sellerData'] ;
        $serviceData = array();
        $serviceData['service_id'] = uniqid().mt_rand(100, 999);
        $serviceData['seller_id'] = $sellerData['user_id'];
        $serviceData['seller_name'] = $sellerData['name'];
        $serviceData['status'] = 0;
        $serviceDao = new ServicesDao();
        $serviceid = $serviceDao ->insert($serviceData);
        $serviceData['id'] = $serviceid;
        $_SESSION['serviceData']= $serviceData;
    }

    /**
     * Get my service list
     */
    public function getMyServicesByStatus(){
        $sellerid = HttpHelper::getVale('sellerid');
        $userID = $_SESSION['signedUser'];
        if ($userID == $sellerid)  {
            $status = HttpHelper::getVale('status');
            $serviceDao = new ServicesDao();
            $myServices = $serviceDao->getServiceByUserStatus($sellerid, $status);
            $_SESSION['myServices'] = self::fixDataLength($myServices);
            $_SESSION['sellerId'] = $sellerid;
            $_SESSION['querystatus'] = $status;
        }
    }

    /**
     * Get my service list
     */
    public function getSellerPublishedServices(){
        $sellerid = HttpHelper::getVale('sellerid');
        $serviceDao = new ServicesDao();
        $servicesData = $serviceDao->getSellerPublishedServices($sellerid);
        $_SESSION['servicesData'] = self::fixDataLength($servicesData);
    }
    
    /**
     * delete a service with service id, reason for seller themselves
     */
    public function deleteService(){
        $userID = $_SESSION['signedUser'];
        $serviceId = $_POST['deleteServiceId'];
        $deleteReason = $_POST['deletereason'];
        $serviceDao = new ServicesDao(); 
        $serviceDao -> deleteService($serviceId,  $deleteReason, $userID);
        self::getMyServicesByStatus();
    }
    /**
     * seller pauase service
     */
    public function pauseService(){
        $serviceId = $_POST['pauseServiceId'];
        $reason = $_POST['pausereason'];
        $status = 100;
        $serviceDao = new ServicesDao();
        $serviceDao -> checkByKv('service_id', $serviceId,  $reason, $status);
        self::getMyServicesByStatus();
    }
    public function recoverService(){
        $serviceId = $_POST['recoverServiceId'];
        $status = 60;
        $serviceDao = new ServicesDao();
        $serviceDao -> checkByKv('service_id', $serviceId,  "", $status);
        self::getMyServicesByStatus();
    }

    /**
     * Get the pending review service (admin now)
     */
    public function getServicesByStatus() {
        $status = HttpHelper::getVale('status');
        $serviceDao = new ServicesDao();
        $allServices = $serviceDao->getByStatus($status);
        $_SESSION['allServices'] = $allServices;
    }

    /**
     * Admin reject or approve service  (admin now)
     */
    public function checkService(){
        $serviceId = $_POST['serviceId'];
        $reason = $_POST['checkreason'];
        $action = $_POST['checkaction'];
        $status = 60;
        if ($action == 1){
            $status = 40;
        }
        $serviceDao = new ServicesDao();
        $serviceDao -> checkByKv('service_id', $serviceId,  $reason, $status);
        self::getServicesByStatus();
    }
    
    /**
     * Get the pending review service (admin now)
     */
    public function getServices() {
        $servicearea = 地球;
        if (isset($_SESSION ['servicearea'])){
            $servicearea = $_SESSION ['servicearea'];
        } else if (isset($_SESSION ['userSetting'])){
            $userSetting = $_SESSION ['userSetting'];
            if (isset($userSetting['selected_service_area'])){
                $servicearea = $userSetting['selected_service_area'];
            }
        }
        $serviceType = isset($_GET ['servicetype'])? $_GET ['servicetype'] : 1;
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",servicearea=".$servicearea.",servicetype=".$serviceType);

        $servicesData = null;
        $serviceDao = new ServicesDao();

        $pageIndex = isset($_GET ['pageIndex']) ? $_GET ['pageIndex'] : 0;
        $pageIndexRange = $pageIndex * 5 .",". '5';

        if (isset($servicearea) && !empty($servicearea) && !is_null($servicearea) && $servicearea != '地球'){
            $servicesData=$serviceDao->getServicesByServiceTypeInAreaWithPage($serviceType, $servicearea, $pageIndexRange);
        } else {
            $servicesData=$serviceDao->getServicesByServiceTypeWithPage($serviceType, $pageIndexRange);
        }

        if ($pageIndex == 0 && $serviceType == 1){
            $_SESSION['servicetype'] = $serviceType;
            $_SESSION['servicesData']= self::fixDataLength($servicesData);

            $adDao = new AdvertiseDao();
            $ads = $adDao->getAdvertiseByCity($servicearea, 0);
            $_SESSION['ads'] = $ads;

        } else {
            //$retJson =  json_encode(array('status'=> 0, 'msg'=> 'done', 'serviceLists' => $servicesData));
            //Logs::writeClcLog(__CLASS__.",".__FUNCTION__." retJson=".$retJson);
            echo json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => self::fixDataLength($servicesData)));
            exit;
        }

//        if ($pageIndex >= 0){
//            //$retJson =  json_encode(array('status'=> 0, 'msg'=> 'done', 'serviceLists' => $servicesData));
//            //Logs::writeClcLog(__CLASS__.",".__FUNCTION__." retJson=".$retJson);
//            echo json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => self::fixDataLength($servicesData)));
//            exit;
//        } else {
//            $_SESSION['servicetype'] = $serviceType;
//            $_SESSION['servicesData']= self::fixDataLength($servicesData);
//        }
    }

    public function getServicesByKey() {
        if (isset($_SESSION['signedUser'])) {
            $servicearea = 地球;
            if (isset($_SESSION ['servicearea'])){
                $servicearea = $_SESSION ['servicearea'];
            } else if (isset($_SESSION ['userSetting'])){
                $userSetting = $_SESSION ['userSetting'];
                if (isset($userSetting['selected_service_area'])){
                    $servicearea = $userSetting['selected_service_area'];
                }
            }
            $keyWord = '';
            if (isset($_POST ['keyWord'])) {
                $keyWord = $_POST ['keyWord'];
            }
            if (isset($_GET ['keyWord'])) {
                $keyWord = $_GET ['keyWord'];
            }
            $_SESSION['keyWord'] = $keyWord;
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",servicearea=" . $servicearea . ",keyWord=" . $keyWord);

            $servicesData = null;
            $serviceDao = new ServicesDao();

            if (isset($_GET ['pageIndex'])){
                $pageIndex =  $_GET ['pageIndex'] ;
                $pageIndexRange = $pageIndex * 5 . "," . '5';

                if (isset($servicearea) && !empty($servicearea) && !is_null($servicearea) && $servicearea != '地球') {
                    $servicesData = $serviceDao->getServicesByKeyWordInAreaWithPage($keyWord, $servicearea, $pageIndexRange);
                } else {
                    $servicesData = $serviceDao->getServicesByKeyWordWithPage($keyWord, $pageIndexRange);
                }

                if ($pageIndex == 0) {
                    $queryHistory = array();
                    $queryHistory['key_word'] = $keyWord;
                    $queryHistory['user_id'] = $_SESSION['signedUser'];
                    $queryHistoryDao = new QueryHistoryDao();
                    $queryHistoryDao->insert($queryHistory);
                }

                if ($pageIndex >= 0) {
                    //$retJson =  json_encode(array('status'=> 0, 'msg'=> 'done', 'serviceLists' => $servicesData));
                    //Logs::writeClcLog(__CLASS__.",".__FUNCTION__." retJson=".$retJson);
                    echo json_encode(array('status' => 0, 'msg' => 'done', 'objLists' => self::fixDataLength($servicesData)));
                    exit;
                } else {
                    // use less code now
                    $_SESSION['servicesData'] = self::fixDataLength($servicesData);
                }

            }
        }
    }

    public function fixDataLength($servicesData){
        foreach($servicesData as $key => $serviceData)
        {
            if (mb_strlen($serviceData['service_name'])>30){
                $servicesData[$key]['service_name'] = mb_substr($serviceData['service_name'],0, 10,"utf-8")."...";
            }
            if (mb_strlen($serviceData['service_brief'])>117){
                $servicesData[$key]['service_brief'] = mb_substr($serviceData['service_brief'],0, 39,"utf-8")."...";
            }
            if (mb_strlen($serviceData['seller_name'])>30){
                $servicesData[$key]['seller_name'] = mb_substr($serviceData['seller_name'],0, 10,"utf-8")."...";
            }
        }
        return $servicesData;
    }
    //=======================================================//
    //  Service Y Plus page                                  //
    //=======================================================//
    public function getYPlusList() {
        $sellerid = HttpHelper::getVale('sellerid');
        $userID = $_SESSION['signedUser'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userId=".$userID.",sellerId=".$sellerid);
        if ($userID == $sellerid)  {
            $service_id = HttpHelper::getVale('service_id');
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_id=".$service_id);
            $serviceYPlusDao = new ServiceYPlusDao();
            $serviceYPlusItems = $serviceYPlusDao->getServiceYPlusItemsByUserStatus($service_id, 0);
            $_SESSION['serviceYPlusItems'] = $serviceYPlusItems;
            $_SESSION['sellerId'] = $sellerid;
            $_SESSION['service_id'] = $service_id;
        }
    }
    public function editYPlusItem() {
        $sellerid = HttpHelper::getVale('sellerid');
        $userID = $_SESSION['signedUser'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userId=".$userID.",sellerId=".$sellerid);
        if ($userID == $sellerid)  {
            $service_id = HttpHelper::getVale('service_id');
            $service_yplus_item_id = HttpHelper::getVale('service_yplus_item_id');
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_id=".$service_id.",service_yplus_item_id=".$service_yplus_item_id);
            if (is_null($service_yplus_item_id) || strlen($service_yplus_item_id) == 0 ){
                $service_yplus_item_id = self::createServiceYPlusItem($service_id);
            } else {
                self::getServiceYPlusItemInfo($service_yplus_item_id);
            }
            self::getServiceYPlusPictures($sellerid, $service_id, $service_yplus_item_id);
            $_SESSION['sellerId'] = $sellerid;
            $_SESSION['service_id'] = $service_id;
            if (strpos($_SERVER['HTTP_USER_AGENT'], "MicroMessenger")){
                WeixinHelper::prepareWeixinPicsParameters("/weiphp/Addons/OverSea/View/mobile/service/service_yplus_item.php");
            } else {
                header('Location:../View/mobile/service/service_yplus_item_web.php');
                exit;
            }
        }
    }
    public function deleteYPlusItem() {
        $sellerid = HttpHelper::getVale('sellerid');
        $userID = $_SESSION['signedUser'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userId=".$userID.",sellerId=".$sellerid);
        if ($userID == $sellerid) {
            $service_id = HttpHelper::getVale('service_id');
            $service_yplus_item_id = HttpHelper::getVale('service_yplus_item_id');
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",service_id=" . $service_id . ",service_yplus_item_id=" . $service_yplus_item_id);
            $serviceYPlusItem = array();
            $serviceYPlusItem['status'] = 1;
            $serviceYPlusDao = new ServiceYPlusDao();
            $serviceYPlusDao -> update($serviceYPlusItem, $service_yplus_item_id);
            self::getYPlusList();
        }
    }

    /**
     * create new service
     */
    private function createServiceYPlusItem($service_id){
        unset($_SESSION['serviceYPlusItemData']);
        $serviceYPlusItem = array();
        $serviceYPlusItem['service_id'] = $service_id;
        $serviceYPlusItem['status'] = 0;
        date_default_timezone_set('PRC');
        $serviceYPlusItem['creation_date'] = date('y-m-d H:i:s',time());

        $serviceYPlusDao = new ServiceYPlusDao();
        $serviceYPlusItemId = $serviceYPlusDao ->insert($serviceYPlusItem);
        $serviceYPlusItem['id'] = $serviceYPlusItemId;
        $_SESSION['serviceYPlusItemData']= $serviceYPlusItem;
        return $serviceYPlusItemId;
    }
    
    /**
     * Get a service yplus item by service id
     */
    private function getServiceYPlusItemInfo($service_yplus_item_id) {
        unset($_SESSION['serviceYPlusItemData']);
        if (!is_null($service_yplus_item_id) && strlen($service_yplus_item_id) >0 ){
            $serviceYPlusDao = new ServiceYPlusDao();
            $serviceYPlusItemData = $serviceYPlusDao ->getById($service_yplus_item_id);
            $_SESSION['serviceYPlusItemData']= $serviceYPlusItemData;
            return $serviceYPlusItemData;
        }
    }

    /**
     * User update service yplus item info
     */
    public function publishServiceYPlusItem(){
        $sellerid = isset($_POST ['sellerid']) ? $_POST ['sellerid'] : '';
        $userID = $_SESSION['signedUser'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userId=".$userID.",sellerId=".$sellerid);
        if ($userID == $sellerid) {
            $serviceYPlusItem = array();
            $serviceYPlusItem['yplus_brief'] = isset($_POST ['yplus_brief']) ? $_POST ['yplus_brief'] : '';
            $serviceYPlusItem['yplus_subject'] = isset($_POST ['yplus_subject']) ? $_POST ['yplus_subject'] : '';
            $service_yplus_item_id = isset($_POST ['service_yplus_item_id']) ? $_POST ['service_yplus_item_id'] : '';
            $service_id = isset($_POST ['service_id']) ? $_POST ['service_id'] : '';
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_id=".$service_id.",service_yplus_item_id=".$service_yplus_item_id);
            try {
                $serviceYPlusDao = new ServiceYPlusDao();
                $serviceYPlusDao->update($serviceYPlusItem, $service_yplus_item_id);
                header('Location:../Controller/AuthUserDispatcher.php?c=getYPlusList&sellerid='.$sellerid.'&service_id='.$service_id);
                exit;
            } catch (\Exception $e) {
                $_SESSION['status'] = 'f';
                $_SESSION['message'] = '保存易知服务YPlus条目!';
                $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=mine";
            }
        }
    }

    /**
     * YZ yplus 图片处理
     */
    public function publishServiceYPlusItemPics() {
        $sellerid = isset($_POST ['sellerid']) ? $_POST ['sellerid'] : '';
        $userID = $_SESSION['signedUser'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userId=".$userID.",sellerId=".$sellerid);
        if ($userID == $sellerid) {
            $service_yplus_item_id = isset($_POST ['service_yplus_item_id']) ? $_POST ['service_yplus_item_id'] : '';
            $service_id = isset($_POST ['service_id']) ? $_POST ['service_id'] : '';
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_id=".$service_id.",service_yplus_item_id=".$service_yplus_item_id);
            // upload image if need to
            if (isset($_GET ['serverids'])){
                $serverids = $_GET ['serverids'];
                //echo $serverids;
                $serveridsArray = explode(',',$serverids);
                $i=1;
                foreach ($serveridsArray as $serverid){
                    $object = "yzphoto/yplus/".$userID."/".$service_id."/".$service_yplus_item_id."_".date('YmdHis')."_".$i.".jpg";
                    self::savePictureFromWeixin($serverid,$object);
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
            $object = "yzphoto/yplus/".$userID."/".$service_id."/".$service_yplus_item_id;
            //echo $object;
            $objectList = OSSHelper::listObjects($object);
            $objArray = array();
            if (!empty($objectList)) {
                foreach ($objectList as $objectInfo) {
                    //if ( substr_compare ( $objectInfo->getKey() , $service_yplus_item_id."_" , 0 , strlen ( $service_yplus_item_id."_" ) ) === 0 ){
                        $objArray[] = $objectInfo->getKey();
                    //}
                }
            }
            //$retJson =  json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
            //Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",retJson=".$retJson);
            echo json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
            exit;
        }
    }

    public function publishServiceYPlusItemPicsWeb() {
        $sellerid = isset($_POST ['sellerid']) ? $_POST ['sellerid'] : '';
        $userID = $_SESSION['signedUser'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userId=".$userID.",sellerId=".$sellerid);
        if ($userID == $sellerid) {
            $service_yplus_item_id = isset($_POST ['service_yplus_item_id']) ? $_POST ['service_yplus_item_id'] : '';
            $service_id = isset($_POST ['service_id']) ? $_POST ['service_id'] : '';
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_id=".$service_id.",service_yplus_item_id=".$service_yplus_item_id);

            // upload image if need to
            $filename = 'selected_file';
            $imagePath ="/home/www/uploads/".$userID."_".$service_id."_".$service_yplus_item_id."_".date('YmdHis').".jpg";
            if(!is_uploaded_file($_FILES[$filename]['tmp_name'])){//验证上传文件是否存在
                echo "请选择你想要上传的图片";
                exit;
            }
            if(!move_uploaded_file ($_FILES[$filename]['tmp_name'], $imagePath)) {//上传文件
                echo "上传文件失败";
                exit;
            }
            $object = "yzphoto/yplus/".$userID."/".$service_id."/".$service_yplus_item_id."_".date('YmdHis').".jpg";
            OSSHelper::uploadFile($object, $imagePath, array());

            // delete image if need
            if (isset($_GET ['objtodelete'])){
                $obj = $_GET ['objtodelete'];
                //echo $obj;
                OSSHelper::deleteObject($obj);
                //exit(1);
            }

            // list data
            $object = "yzphoto/yplus/".$userID."/".$service_id."/".$service_yplus_item_id;
            //echo $object;
            $objectList = OSSHelper::listObjects($object);
            $objArray = array();
            if (!empty($objectList)) {
                foreach ($objectList as $objectInfo) {
                    //if ( substr_compare ( $objectInfo->getKey() , $service_yplus_item_id."_" , 0 , strlen ( $service_yplus_item_id."_" ) ) === 0 ){
                    $objArray[] = $objectInfo->getKey();
                    //}
                }
            }
            //$retJson =  json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
            //Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",retJson=".$retJson);
            echo json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
            exit;
        }
    }

    /*
    * get pictures info by seller id
    */
    private function getServiceYPlusPictures($sellerid, $service_id, $service_yplus_item_id) {
        unset($_SESSION['service_yplus_obj_array']);
        // list data
        $object = "yzphoto/yplus/".$sellerid."/".$service_id."/".$service_yplus_item_id;
        //echo $object;
        $objectList = OSSHelper::listObjects($object);
        $service_yplus_obj_array = array();
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                $service_yplus_obj_array[] = $objectInfo->getKey();
            }
            //$retObjArray =  json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $service_yplus_obj_array));
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",ret=".json_encode($service_yplus_obj_array));
            $_SESSION['service_yplus_obj_array'] = $service_yplus_obj_array;
        }
        return $service_yplus_obj_array;
    }

    /**
     * Get comments for a service
     */
    public function getYPlusForService($seller_id, $service_id) {
        unset($_SESSION['serviceYPlusItems']);
        $serviceYPlusDao = new ServiceYPlusDao();
        $serviceYPlusItems = $serviceYPlusDao->getServiceYPlusItemsByUserStatus($service_id, 0);
        $service_yplus_obj_array = self::getServiceYPlusPictures($seller_id, $service_id, '');
        foreach($serviceYPlusItems as $key => $yPlusItem) {
            $objArray = array();
            if (count($service_yplus_obj_array)>0) {
                foreach ($service_yplus_obj_array as $objectInfo) {
                    if (strstr($objectInfo, $yPlusItem['id']."_")){
                        $objArray[] = $objectInfo;
                    }
                }
            }
            $serviceYPlusItems[$key]['objArray'] = $objArray;
        }
        $_SESSION['serviceYPlusItems'] = $serviceYPlusItems;
    }


    //=======================================================//
    //  Query history                                        //
    //=======================================================//
    public function getQueryHistory() {
        if (isset($_SESSION['signedUser'])){
            unset($_SESSION['queryHistories']);
            $userID = $_SESSION['signedUser'];
            $queryHistoryDao = new QueryHistoryDao();
            $queryHistories = $queryHistoryDao->getQueryHistoryByUserId($userID);
            $_SESSION['queryHistories'] = $queryHistories;
        }
    }
    public function deleteKeyWordById() {
        if (isset($_SESSION['signedUser'])){
            $query_id = $_GET['query_id'];
            $queryHistory = array();
            $queryHistory['status'] = 1;
            $queryHistory['user_id'] = $_SESSION['signedUser'];
            $queryHistoryDao = new QueryHistoryDao();
            $queryHistoryDao -> update($queryHistory, $query_id);
            self::getQueryHistory();
        }
    }

    //=======================================================//
    //  Query location                                       //
    //=======================================================//
    public function getAllCitiesWithPinyin(){
        $citiesDao = new CitiesDao();
        $allCities = $citiesDao->getAllCities();
        $countries = array();
        $cities = array();
        foreach($allCities as $key => $city)
        {
            $cid = $city['display_sequence'];
            $countries[$cid] = $city['country_name'];
            
            $pinyin = $city['first_char_pinyin'];
            if (!isset($cities[$cid])){
                $cityList = array();
                $cities[$cid] = $cityList;
            }
            if (!isset($cities[$cid][$pinyin])){
                $firstCharPinyin = array();
                $cities[$cid][$pinyin] = $firstCharPinyin;
            }
            $cities[$cid][$pinyin][] = $city['city_name'];
        }
        $_SESSION['cities'] = $cities;
        $_SESSION['countries'] = $countries;
        //Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",cities=".json_encode($cities));
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",countries=".json_encode($countries));
    }

    public function getAllCities(){
        $citiesDao = new CitiesDao();
        $allCities = $citiesDao->getAllCities();
        $countries = array();
        $cities = array();
        foreach($allCities as $key => $city)
        {
            $cid = $city['display_sequence'];
            $countries[$cid] = $city['country_name'];

            if (!isset($cities[$cid])){
                $cityList = array();
                $cities[$cid] = $cityList;
            }
            $cities[$cid][] = $city['city_name'];
        }
        $_SESSION['cities'] = $cities;
        $_SESSION['countries'] = $countries;
        //Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",cities=".json_encode($cities));
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",countries=".json_encode($countries));
    }

    public function getTagsByCityBusinessType(){
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",");
        $service_area = $_GET ['service_area'];
        $service_type = $_GET ['service_type'];
        $citiesTagDao = new CitiesTagDao();
        $tagsData = $citiesTagDao->getTagsByCityBusinessType($service_area, $service_type);
        $ret = json_encode(array('status' => 0, 'msg' => 'done', 'objLists' => $tagsData));
        echo $ret;
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",tags=".$ret);
        exit;
    }

    //=======================================================//
    //  Advertise                                            //
    //=======================================================//
    public function getAdvertiseList(){
        $adDao = new AdvertiseDao();
        $adsData = $adDao->getAllAdvertisesByStatus(0);
        $_SESSION['adsData'] = $adsData;
    }
    public function deleteAdvertiseOfService(){
        $adDao = new AdvertiseDao();
        $id = $_POST['id'];
        $adData = $adDao->getById($id);
        if (isset($adData['status']) && $adData['status']==0){
            $adData['status'] = 1;
            $adDao ->update($adData, $id);

            $service_id =  $_POST['service_id'] ;
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_id=".$service_id);
            $object = "yzphoto/advertise/".$adData['city_name']."/".$adData['service_type']."/".$adData['service_id'].".jpg";
            OSSHelper::deleteObject($object);
        }
        self::getAdvertiseList();
    }
    public function prepareAdvertise() {
        $_SESSION ['service_id'] = $_GET['service_id'] ;
        $_SESSION ['city'] = $_GET['city'] ;
        $_SESSION ['type'] = $_GET['type'] ;
        WeixinHelper::prepareWeixinPicsParameters("/weiphp/Addons/OverSea/View/admin/publish_advertise.php");
    }

    public function publishAdvertise() {
        // Get the infos
        $service_id = isset($_POST ['service_id']) ? $_POST ['service_id'] : '';
        $ad_area_type = isset($_POST ['ad_area_type']) ? $_POST ['ad_area_type'] : '';
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_id=".$service_id);
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",ad_area_type=".$ad_area_type);
        $serviceDao = new ServicesDao();
        $service = $serviceDao -> getByKv('service_id', $service_id);
        $city = $ad_area_type==2 ? '地球' : $service['service_area'];
        $service_type = $service['service_type'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_area=".$city);
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_type=".$service_type);
        if (!isset($service['id']))  {
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_id ".$service_id." is invalid");
            echo json_encode(array('status'=> -1, 'msg'=> '服务编号不存在:'.$service_id, 'objLists' => ''));
            exit;
        }

        //Check existed or not
        $adDao = new AdvertiseDao();
        $existedAd = $adDao ->getByKv('service_id', $service_id);
        if (!isset($existedAd['service_id'])) {
            $advertiseData = array();
            $advertiseData['service_id'] = $service_id;
            $advertiseData['city_name'] = $city;
            $advertiseData['service_type'] = $service_type;
            $adDao ->insert($advertiseData);
        }

        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_id=".$service_id);
        $object = "yzphoto/advertise/".$city."/".$service_type."/".$service_id.".jpg";
        // upload image if need to
        if (isset($_GET ['serverids'])){
            $serverids = $_GET ['serverids'];
            //echo $serverids;
            $serveridsArray = explode(',',$serverids);
            $i=1;
            foreach ($serveridsArray as $serverid){
                self::savePictureFromWeixin($serverid, $object);
                $i++;
            }
        }

        // list data
        $objArray = array();
        $objArray[] = $object;
        echo json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
        exit;
    }

    /*
    public function abslength($str){
        $len=strlen($str);
        $i=0; $j=0;
        while($i<$len)
        {
            if(preg_match("/^[".chr(0xa1)."-".chr(0xf9)."]+$/",$str[$i]))
            {
                $i+=2;
            }
            else
            {
                $i+=1;
            }
            $j++;
        }
        return $j;
    }
    */
}
