<?php

/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/12/6
 * Time: 21:42
 */
namespace Addons\OverSea\Api;
use Addons\OverSea\Common\OSSHelper;
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Model\UserInfosDao;
use Addons\OverSea\Model\ServicesDao;

class ServicesBo
{

    /**
     * Get picture of serivces
     */
    public function getServicePictures($sellerId, $serviceId) {
        $object = "yzphoto/pics/".$sellerId."/".$serviceId."/";
        $objectList = OSSHelper::listObjects($object);
        if (!empty($objectList)) {
            $objArray = array();
            foreach ($objectList as $objectInfo) {
                if (strstr($objectInfo->getKey(), "main")){
                } else {
                    $objArray[] = $objectInfo->getKey();
                }
            }
            return $objArray;
        } else {
            return null;
        }
    }

    /**
     * Get the service that current seller still not published
     * @param $sellerid
     * @return null
     */
    public function getSellerNotPublishedService($sellerid) {
        $serviceDao = new ServicesDao();
        $myServices = $serviceDao->getServiceByUserStatus($sellerid, 0);
        if (isset($myServices) && count($myServices) >0){
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",Got unPublishedService");
            $serviceData = $myServices[0];
            return $serviceData;
        }
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",Not get unPublishedService");
        return null;
    }


    /**
     * Create a empty service for a seller
     * @param $sellerid
     * @return array
     * @throws \Exception
     */
    public function createNewService($sellerid){
        $usersBo = new UsersBo();
        $sellerData = $usersBo -> getCurrentSellerInfo($sellerid);
        $serviceData = array();
        $serviceData['service_id'] = uniqid().mt_rand(100, 999);
        $serviceData['seller_id'] = $sellerData['user_id'];
        $serviceData['seller_name'] = $sellerData['name'];
        $serviceData['status'] = 0;
        $serviceDao = new ServicesDao();
        $serviceid = $serviceDao ->insert($serviceData);
        $serviceData['id'] = $serviceid;
        return $serviceData;
    }
 
    /**
     * Get a service by service id
     */
    public function getServiceInfo($service_id) {
        if (!is_null($service_id) && strlen($service_id) >0 ){
            $serviceDao = new ServicesDao();
            $serviceData = $serviceDao ->getByKv('service_id', $service_id);
            return $serviceData;
        }
    }


}