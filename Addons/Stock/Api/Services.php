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
use Addons\OverSea\Common\WeixinHelper;
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Model\CropAvatar;
use Addons\OverSea\Common\HttpHelper;
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
    

}