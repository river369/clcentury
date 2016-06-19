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

    /**
     * get service with sellerId and status
     * @param $seller_id
     * @param $status
     * @return mixed
     */
    public function getServiceByUserStatus($seller_id, $status)
    {
        $sql = 'SELECT * FROM yz_services WHERE seller_id =:seller_id and status = :status order by creation_date desc';
        $parameter = array(':seller_id' => $seller_id, ':status' => $status);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
        $services = MySqlHelper::fetchAll($sql, $parameter);
        return $services;
    }

    /**
     * get published service with sellerId
     * @param $seller_id
     * @return mixed
     */
    public function getSellerPublishedServices($seller_id)
    {
        $sql = 'SELECT * FROM yz_services WHERE seller_id =:seller_id and status = 60 order by creation_date desc';
        $parameter = array(':seller_id' => $seller_id);
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
    public function getServicesByServiceTypeInArea($service_type, $service_area)
    {
        $sql = 'SELECT * FROM yz_services WHERE service_type=:service_type and service_area = :service_area and status=60 order by stars desc';
        $parameter = array(':service_type' => $service_type, ':service_area' => $service_area);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
        $services = MySqlHelper::fetchAll($sql, $parameter );
        return $services;
    }

    public function getServicesByServiceTypeInAreaWithPage($service_type, $service_area, $pageIndexRange )
    {
        //$sql = 'SELECT * FROM yz_services WHERE service_type=:service_type and service_area = :service_area order by id desc, stars desc limit :pageIndex, 2';
        //$parameter = array(':service_type' => $service_type, ':service_area' => $service_area, ':pageIndex' => $pageIndex);
        $sql = 'SELECT * FROM yz_services WHERE service_type=:service_type and service_area = :service_area  and status=60 order by id desc, stars desc limit ' . $pageIndexRange;
        $parameter = array(':service_type' => $service_type, ':service_area' => $service_area);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
        $services = MySqlHelper::fetchAll($sql, $parameter );
        return $services;
    }

    public function getServicesByKeyWordInAreaWithPage($keyWord, $service_area, $pageIndexRange )
    {
        $sql = 'SELECT * FROM yz_services WHERE service_area = :service_area AND (tag like "%' . $keyWord . '%" or seller_name like "%' . $keyWord . '%" or description like "%' . $keyWord . '%") and status=60 order by id desc, stars desc limit ' . $pageIndexRange;
        $parameter = array(':service_area' => $service_area);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
        $services = MySqlHelper::fetchAll($sql, $parameter );
        return $services;
    }

    /**
     * 
     * Get users in special type.
     * @param $servicetype
     * @param $city
     * @return mixed
     */
    public function getServicesByServiceType($service_type)
    {
        $sql = 'SELECT * FROM yz_services WHERE service_type=:service_type and status=60 order by stars desc';
        $parameter = array(':service_type' => $service_type);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
        $services = MySqlHelper::fetchAll($sql, $parameter);
        return $services;
    }

    public function getServicesByServiceTypeWithPage($service_type, $pageIndexRange)
    {
        //$sql = 'SELECT * FROM yz_services WHERE service_type=:service_type order by id desc, stars desc limit :pageIndex, 2';
        //$parameter = array(':service_type' => $service_type, ':pageIndex' => $pageIndex);
        $sql = 'SELECT * FROM yz_services WHERE service_type=:service_type and status=60 order by id desc, stars desc limit ' . $pageIndexRange;
        $parameter = array(':service_type' => $service_type);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
        $services = MySqlHelper::fetchAll($sql, $parameter);
        return $services;
    }

    public function getServicesByKeyWordWithPage($keyWord, $pageIndexRange)
    {
        $sql = 'SELECT * FROM yz_services WHERE tag like "%' . $keyWord . '%" or seller_name like "%' . $keyWord . '%" or description like "%' . $keyWord . '%" and status=60 order by id desc, stars desc limit ' . $pageIndexRange;
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
        $services = MySqlHelper::fetchAll($sql);
        return $services;
    }



}
?>