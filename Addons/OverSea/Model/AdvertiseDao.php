<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/2
 * Time: 11:55
 */
namespace Addons\OverSea\Model;
use Addons\OverSea\Common\MySqlHelper;
use Addons\OverSea\Model\BaseDao;
use Addons\OverSea\Common\Logs;

class AdvertiseDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("yz_advertises");
    }

    public function getAdvertiseByCity($city_name, $status)
    {
        try {
            $sql = 'SELECT * FROM ' . parent::getTableName(). ' WHERE city_name= :city_name and status = :status ';
            $parameter = array(':city_name' => $city_name, ':status' => $status);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $ret = MySqlHelper::fetchAll($sql, $parameter);
            return $ret;
        } catch (\Exception $e){
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . $e);
            exit;
        }

    }

    /**
     * To be deprecated!!
     * 
     * @param $city_name
     * @param $service_type
     * @return mixed
     */
    public function getAdvertiseByCityBusinessType($city_name, $service_type)
    {
        try {
           $sql = 'SELECT * FROM ' . parent::getTableName(). ' WHERE city_name= :city_name and service_type= :service_type';
            $parameter = array(':city_name' => $city_name, ':service_type' => $service_type);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $ret = MySqlHelper::fetchAll($sql, $parameter);
            return $ret;
        } catch (\Exception $e){
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . $e);
            exit;
        }
        
    }
    public function getAllAdvertisesByStatus($status)
    {
        try {
            $sql = 'SELECT * FROM ' . parent::getTableName(). ' where status = :status order by city_name, service_type';
            $parameter = array(':status' => $status);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $ret = MySqlHelper::fetchAll($sql, $parameter);
            return $ret;
        } catch (\Exception $e){
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . $e);
            exit;
        }

    }
}
?>