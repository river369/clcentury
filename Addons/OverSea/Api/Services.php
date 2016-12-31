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
use Addons\OverSea\Model\UserAccountsDao;
use Addons\OverSea\Model\QueryHistoryDao;
use Addons\OverSea\Model\ServicesDao;
use Addons\OverSea\Model\ServiceYPlusDao;
use Addons\OverSea\Model\CommentsDao;
use Addons\OverSea\Model\CitiesDao;
use Addons\OverSea\Model\CitiesTagDao;
use Addons\OverSea\Model\AdvertiseDao;

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

        // list data
        $object = "yzphoto/pics/".$sellerId."/".$serviceId."/";
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

}