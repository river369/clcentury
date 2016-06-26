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
use Addons\OverSea\Model\QueryHistoryDao;
use Addons\OverSea\Model\ServicesDao;
use Addons\OverSea\Model\CommentsDao;
use Addons\OverSea\Model\CitiesDao;

require dirname(__FILE__).'/PicCrop.php';

class ServicesBo
{
    public function __construct() {
    }

    /**
     * Prepare for service info for service create or update
     */
    public function getCurrentService() {
        $sellerid = HttpHelper::getVale('sellerid');
        $userID = $_SESSION['signedUser'];
        if (is_null($sellerid) || strlen($sellerid) == 0 ){
            $sellerid = $userID;
        }
        $service_id = HttpHelper::getVale('service_id');
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userId=".$userID.",sellerId=".$sellerid.",service_id=".$service_id);

        $sellerData = self::getCurrentSellerInfo($sellerid);
        $sellerName = $sellerData['name'];
        $weixin = $sellerData['weixin'];

        if (!isset($sellerName) || strlen($sellerName) == 0 || !isset($weixin) || strlen($weixin) == 0){
            $_SESSION['status'] = 'f';
            $_SESSION['message'] = '用户信息不完善,请完善个人信息,确保微信号,昵称已填写完毕!';
            $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=myinfo&customerid=".$sellerid;
            header('Location:../View/mobile/common/message.php');
            exit;
        }
        self::getServiceInfo($service_id);
        WeixinHelper::prepareWeixinPicsParameters("/weiphp/Addons/OverSea/View/mobile/service/publishservice.php");
        self::getServicePictures($sellerid, $service_id);
    }


    /**
     * Prepare for service info for service read only when discover
     */
    public function getServiceById() {
        $service_id = HttpHelper::getVale('service_id');
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",service_id=".$service_id);
        $service = self::getServiceInfo($service_id);
        $seller_id = $service['seller_id'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",$seller_id=".$seller_id);
        self::getCurrentSellerInfo($seller_id);
        self::getServicePictures($seller_id, $service_id);
        self::getCommentForService($service_id);
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
        $userDao = new UsersDao();
        $sellerData = $userDao ->getById($sellerid);
        $_SESSION['sellerData']= $sellerData;
        return $sellerData;
    }

    /**
     * Get a user by seller id
     */
    private function getServiceInfo($service_id) {
        unset($_SESSION['serviceData']);
        if (!is_null($service_id) && strlen($service_id) >0 ){
            $serviceDao = new ServicesDao();
            $serviceData = $serviceDao ->getById($service_id);
            $_SESSION['serviceData']= $serviceData;
            return $serviceData;
        }
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
        unset($_SESSION['objArray']);

        // list data
        $object = "yzphoto/pics/".$sellerid."/".$service_id."/";
        //echo $object;
        $objectList = OSSHelper::listObjects($object);
        $objArray = array();
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                $objArray[] = $objectInfo->getKey();
            }
            $retObjArray =  json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",ret=".$retObjArray);
            $_SESSION['objArray'] = $objArray;
        }
    }

    /**
     * YZ 图片处理
     */
    public function publishServicePics() {
        $userID = $_SESSION['signedUser'];
        if (!isset($_SESSION['serviceData'])){
            self::createNewService();
        }
        $serviceId = $_SESSION['serviceData']['id'] ;
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userid=".$userID." serviceid=".$serviceId);
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
            }
        }
        //$retJson =  json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
        //Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",retJson=".$retJson);
        echo json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
        exit;
    }
    
    // 获yz取图片地址
    private function savePictureFromWeixin($media_id, $userID, $yzId, $i){
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $access_token = WeixinHelper::getAccessTokenWithLocalFile();
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
        $object = "yzphoto/pics/".$userID."/".$yzId."/".date('YmdHis')."_".$i.".jpg";
        $options = array();
        OSSHelper::putObject($object, file_get_contents($url), $options);
        return ;
    }

    /**
     * User update service info
     */
    public function publishServiceInfo(){
        if (!isset($_SESSION['serviceData'])){
            self::createNewService();
        }
        $serviceData = $_SESSION['serviceData'];
        $serviceData['status'] = 20;// change satus to waiting for approve
        $serviceData['service_area'] = isset($_POST ['service_area']) ? $_POST ['service_area'] : '';
        $serviceData['service_name'] = isset($_POST ['service_name']) ? $_POST ['service_name'] : '';
        $serviceData['description'] = isset($_POST ['description']) ? trim($_POST ['description']) : '';
        $serviceData['service_type'] = $_POST ['service_type'];
        $serviceData['service_price'] = isset($_POST ['service_price']) ? $_POST ['service_price'] : '';
        $serviceData['tag'] = isset($_POST ['mytags']) ? $_POST ['mytags'] : '';

        $serviceDao = new ServicesDao();
        $serviceid = $serviceDao ->update($serviceData, $serviceData['id']);

        if ($serviceid==0) {
            //$_SESSION['status'] = 's';
            //$_SESSION['message'] = '提交易知服务信息成功,谢谢!';
            //$_SESSION['goto'] = "../../../Controller/FreelookDispatcher.php?c=getServices";
            header('Location:../Controller/AuthUserDispatcher.php?c=myServices&sellerid='.$serviceData['seller_id'].'&status=20');
            exit;
        } else {
            $_SESSION['status'] = 's';
            $_SESSION['message'] = '提交易知服务信息失败!';
            $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=mine";
        }
        //$_SESSION['$serviceData']= $serviceData;
        //header('Location:../View/mobile/users/submityzsuccess.php');
    }

    /**
     * create new service
     */
    private function createNewService(){
        $sellerData = $_SESSION['sellerData'] ;
        $serviceData = array();
        $serviceData['seller_id'] = $sellerData['id'];
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
            $_SESSION['myServices'] = $myServices;
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
        $_SESSION['servicesData'] = $servicesData;
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
        $serviceDao -> check($serviceId,  $reason, $status);
        self::getMyServicesByStatus();
    }
    public function recoverService(){
        $serviceId = $_POST['recoverServiceId'];
        $status = 60;
        $serviceDao = new ServicesDao();
        $serviceDao -> check($serviceId,  "", $status);
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
        $serviceDao -> check($serviceId,  $reason, $status);
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
        if ($pageIndex >= 0){
            //$retJson =  json_encode(array('status'=> 0, 'msg'=> 'done', 'serviceLists' => $servicesData));
            //Logs::writeClcLog(__CLASS__.",".__FUNCTION__." retJson=".$retJson);
            echo json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $servicesData));
            exit;
        } else {
            $_SESSION['servicetype'] = $serviceType;
            $_SESSION['servicesData']= $servicesData;
        }
    }

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
                    echo json_encode(array('status' => 0, 'msg' => 'done', 'objLists' => $servicesData));
                    exit;
                } else {
                    // use less code now
                    $_SESSION['servicesData'] = $servicesData;
                }

            }
        }
    }

    public function getAllCities(){
        $citiesDao = new CitiesDao();
        $allCities = $citiesDao->getAllCities();
        $countries = array();
        $citites = array();
        foreach($allCities as $key => $city)
        {
            $cid = $city['display_sequence'];
            $countries[$cid] = $city['country_name'];
            
            $pinyin = $city['first_char_pinyin'];
            if (!isset($citites[$cid])){
                $cityList = array();
                $citites[$cid] = $cityList;
            }
            if (!isset($citites[$cid][$pinyin])){
                $firstCharPinyin = array();
                $citites[$cid][$pinyin] = $firstCharPinyin;
            }
            $citites[$cid][$pinyin][] = $city['city_name'];
        }
        $_SESSION['citites'] = $citites;
        $_SESSION['countries'] = $countries;
        //Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",citites=".json_encode($citites));
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",countries=".json_encode($countries));
    }

}
