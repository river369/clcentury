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
            $_SESSION['link_name'] = '去填写个人信息';
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
        $service_id = $_GET['service_id'];
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
        $userInfoDao = new UserInfosDao();
        $sellerData = $userInfoDao ->getByKv('user_id', $sellerid);
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
            $serviceData = $serviceDao ->getByKv('service_id', $service_id);
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
            self::createNewService();
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
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",Uploading to OSS");
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
            self::createNewService();
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
        $serviceData['service_brief'] = isset($_POST ['service_brief']) ? $_POST ['service_brief'] : '';
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

        if ($pageIndex >= 0){
            //$retJson =  json_encode(array('status'=> 0, 'msg'=> 'done', 'serviceLists' => $servicesData));
            //Logs::writeClcLog(__CLASS__.",".__FUNCTION__." retJson=".$retJson);
            echo json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => self::fixDataLength($servicesData)));
            exit;
        } else {
            $_SESSION['servicetype'] = $serviceType;
            $_SESSION['servicesData']= self::fixDataLength($servicesData);
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
                    echo json_encode(array('status' => 0, 'msg' => 'done', 'objLists' => self::fixDataLength($servicesData)));
                    exit;
                } else {
                    // use less code now
                    $_SESSION['servicesData'] = self::fixDataLength($servicesData);
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
