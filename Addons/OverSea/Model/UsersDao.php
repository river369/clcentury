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

class UsersDao extends BaseDao
{
    /**
     * UsersDao constructor.
     */
    public function __construct()
    {
        parent::__construct("yz_users");
    }

    public function updateExternalId($external_id, $id)
    {
        try {
            $sql = "update yz_users set external_id = :external_id where id =:id";
            //echo $sql . $id .$openid;
            MySqlHelper::query($sql, array(':external_id' => $external_id, ':id' => $id));
            return 0;
        } catch (\Exception $e){
            return -1;
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
        }
    }

    public function getUserByPhone($phone_reigon, $phone_number)
    {
        $sql = 'SELECT * FROM yz_users WHERE phone_reigon = :phone_reigon and phone_number = :phone_number LIMIT 1';
        $user = MySqlHelper::fetchOne($sql, array(':phone_reigon' => $phone_reigon, ':phone_number' => $phone_number));
        return $user;
    }
    
    public function getUserByExternalId($external_id)
    {
        try {
            $sql = 'SELECT * FROM yz_users WHERE external_id = :external_id LIMIT 1';
            //echo $sql;
            $user = MySqlHelper::fetchOne($sql, array(':external_id' => $external_id));
            return $user;
        }catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
            exit(1);
        }

    }

    /**
     * Get users in special type and city
     * @param $servicetype
     * @param $city
     * @return mixed
     */
    public function getUsersByServiceTypeInArea($service_type, $service_area)
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
    public function getUsersByServiceType($service_type)
    {
        $sql = 'SELECT * FROM yz_users WHERE service_type in (99999, :service_type) order by stars desc';
        $users = MySqlHelper::fetchAll($sql, array(':service_type' => $service_type));
        return $users;
    }
    
}
?>