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

class ServicesDao extends BaseDao
{
    /**
     * ServicesDao constructor.
     */
    public function __construct()
    {
        parent::__construct("yz_services");
    }
    
    public function getServiceByUserStatus($seller_id, $status)
    {
        $sql = 'SELECT * FROM yz_services WHERE seller_id =:seller_id and status = :status order by creation_date desc';
        $parameter = array(':seller_id' => $seller_id, ':status' => $status);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
        $services = MySqlHelper::fetchAll($sql, $parameter);
        return $services;
    }

    public static function deleteService($id,  $delete_reason, $seller_id)
    {
        try {
            $sql = "update yz_services set status = 80, delete_reason = :delete_reason where id =:id and seller_id=:seller_id";
            //echo $sql
            $parameter =  array(':delete_reason' => $delete_reason, ':id' => $id, ':seller_id' => $seller_id);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            MySqlHelper::query($sql,$parameter);
            return 0;
        } catch (\Exception $e){
            return -1;
            echo $e;
        }
    }

  
   // To be change
    /**
     * Get users in special type and city
     * @param $servicetype
     * @param $city
     * @return mixed
     */
    public function getUsersByServiceTypeInArea($service_type, $service_area)
    {
        $sql = 'SELECT * FROM yz_services WHERE service_type in (99999, :service_type) and service_area = :service_area order by stars desc';
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
    public function getUsersByServiceType($service_type)
    {
        $sql = 'SELECT * FROM yz_services WHERE service_type in (99999, :service_type) order by stars desc';
        $users = MySqlHelper::fetchAll($sql, array(':service_type' => $service_type));
        return $users;
    }
    
}
?>