<?php

/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/12/6
 * Time: 21:42
 */
namespace Addons\OverSea\Api;
use Addons\OverSea\Api\Base;
use Addons\OverSea\Common\OSSHelper;
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Model\UserInfosDao;
use Addons\OverSea\Api\ServicesBo;
use Addons\OverSea\Model\ServicesDao;
use Addons\OverSea\Model\CommentsDao;
use Addons\OverSea\Model\CitiesDao;

class Services extends Base
{
    /**
     * 实例化类库
     *
     * @param array $data 接收的参数数据
     *
     * @return void
     */
    public function __construct($data)
    {
        parent::__construct($data);
    }

    /**
     * Get service list
     */
    public function getServices()
    {
        $serviceArea = isset($this->data['serviceArea']) ? $this->data['serviceArea'] : '地球';
        $serviceType = isset($this->data['serviceType']) ? $this->data['serviceType'] : 0;
        $pageIndex = isset($this->data['pageIndex']) ? $this->data['pageIndex'] : 0;
        $pageIndexRange = $pageIndex * 5 .",". '5';
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",servicearea=".$serviceArea.",servicetype=".$serviceType.",pageIndex=".$pageIndex);

        $servicesData = null;
        $serviceDao = new ServicesDao();

        if (isset($servicearea) && !empty($servicearea) && !is_null($servicearea) && $servicearea != '地球'){
            $servicesData=$serviceDao->getServicesByServiceTypeInAreaWithPage($serviceType, $servicearea, $pageIndexRange);
        } else {
            $servicesData=$serviceDao->getServicesByServiceTypeWithPage($serviceType, $pageIndexRange);
        }
        $response_data = array();
        $response_data['services'] = $servicesData;
        $this->setCode("0");
        $this->setMessage("success");
        $this->setResponseData($response_data);
        $this->response();
    }

    /**
     * Get picture of serivces
     */
    public function getServicePictures() {
        $sellerId = isset($this->data['sellerId']) ? $this->data['sellerId'] : '';
        $serviceId = isset($this->data['serviceId']) ? $this->data['serviceId'] : '';
        if (!isset($serviceId) || is_null($serviceId) || strlen($serviceId) ==0 ||
            !isset($sellerId) || is_null($sellerId) || strlen($sellerId) ==0){
            Common::responseError(1011, "服务编号或卖家编号不能为空。");
        }
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",sellerId=".$sellerId.",serviceId=".$serviceId);

        $serviceBo = new ServicesBo();
        $objArray = $serviceBo->getServicePictures($sellerId, $serviceId);

        if (!empty($objArray)) {
            $response_data = array();
            $response_data['servicePictures'] = $objArray;
            $this->setCode("0");
            $this->setMessage("success");
            $this->setResponseData($response_data);
            $this->response();
        } else {
            Common::responseError(1010, "该服务未上传图片。");
        }
    }

    /**
     * Get service info
     */
    public function getServiceInfoById() {
        $serviceId = $this->data['serviceId'];
        if (!isset($serviceId) || is_null($serviceId) || strlen($serviceId) ==0 ){
            Common::responseError(1012, "服务编号不能为空。");
        }
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",serviceId=".$serviceId);
        $serviceDao = new ServicesDao();
        $serviceData = $serviceDao ->getByKv('service_id', $serviceId);
        if (!empty($serviceData)) {
            $response_data['serviceInfo'] = $serviceData;
            $this->setCode("0");
            $this->setMessage("success");
            $this->setResponseData($response_data);
            $this->response();
        } else {
            Common::responseError(1013, "该服务不存在。");
        }
    }

    public function getAggregatedServiceDetails()
    {
        $sellerId = isset($this->data['sellerId']) ? $this->data['sellerId'] : '';
        $serviceId = isset($this->data['serviceId']) ? $this->data['serviceId'] : '';
        if (!isset($serviceId) || is_null($serviceId) || strlen($serviceId) == 0 ||
            !isset($sellerId) || is_null($sellerId) || strlen($sellerId) == 0
        ) {
            Common::responseError(1011, "服务编号或卖家编号不能为空。");
        }
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sellerId=" . $sellerId . ",serviceId=" . $serviceId);

        // Get user info
        $userInfoDao = new UserInfosDao();
        $sellerData = $userInfoDao->getByKv('user_id', $sellerId);

        // Get service comments
        $commentsDao = new CommentsDao();
        $commentsData = $commentsDao->getCommentsByServiceId($serviceId);

        $response_data = array();
        $response_data['sellerInfo'] = $sellerData;
        if (!empty($commentsData)) {
            $response_data['comments'] = $commentsData;
        }

        if (!empty($sellerData)) {
            $this->setCode("0");
            $this->setMessage("success");
            $this->setResponseData($response_data);
            $this->response();
        } else {
            Common::responseError(1014, "用户信息有误。");
        }
    }

    public function createOrUpdatePublishingService(){
        $sellerId = isset($this->data['sellerId']) ? $this->data['sellerId'] : '';
        $serviceId = isset($this->data['serviceId']) ? $this->data['serviceId'] : '';
        if (!isset($sellerId) || is_null($sellerId) || strlen($sellerId) == 0) {
            Common::responseError(1015, "卖家编号不能为空。");
        }

        /*
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
        */

        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",sellerId=".$sellerId.",service_id=".$serviceId);
        $serviceBo = new ServicesBo();
        $service = null;
        if (is_null($serviceId) || strlen($serviceId) == 0 ){
            $service = $serviceBo -> getSellerNotPublishedService($sellerId);
            if (is_null($service)){
                $service = $serviceBo -> createNewService();
            }
        } else {
            $service = $serviceBo -> getServiceInfo($serviceId);
        }

        $serviceId = $service['service_id'];
        $objArray = $serviceBo -> getServicePictures($sellerId, $serviceId);

        $countries = array();
        $cities = array();
        $citiesDao = new CitiesDao();
        $allCities = $citiesDao->getAllCities();
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

        $response_data = array();
        $response_data['serviceInfo'] = $service;
        $response_data['servicePictures'] = $objArray;
        $response_data['countries'] = $countries;
        $response_data['cities'] = $cities;

        if (!empty($service)) {
            $this->setCode("0");
            $this->setMessage("success");
            $this->setResponseData($response_data);
            $this->response();
        } else {
            Common::responseError(1016, "服务信息创建或保存有误。");
        }
    }

  

}