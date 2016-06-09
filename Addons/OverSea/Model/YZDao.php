<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/2
 * Time: 11:55
 */
namespace Addons\OverSea\Model;
use Addons\OverSea\Common\MySqlHelper;
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Model\BaseDao;

class YZDao extends BaseDao
{
    /**
     * YZDao constructor.
     */
    public function __construct()
    {
        parent::__construct("yz_services");
    }


   // To be change
    /**
     * Get users in special type and city
     * @param $servicetype
     * @param $city
     * @return mixed
     */
    public static function getUsersByServiceTypeInArea($service_type, $service_area)
    {
        $sql = 'SELECT * FROM yz_users WHERE service_type in (99999, :service_type) and service_area = :service_area order by stars desc';
        $users = MySqlHelper::fetchAll($sql, array(':service_type' => $service_type, ':service_area' => $service_area));
        return $users;
    }

    /**
     * 
     * Get users in special type.
     * @param $servicetype
     * @param $city
     * @return mixed
     */
    public static function getUsersByServiceType($service_type)
    {
        $sql = 'SELECT * FROM yz_users WHERE service_type in (99999, :service_type) order by stars desc';
        $users = MySqlHelper::fetchAll($sql, array(':service_type' => $service_type));
        return $users;
    }
    
}
?>