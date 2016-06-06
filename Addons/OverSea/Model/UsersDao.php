<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/2
 * Time: 11:55
 */
namespace Addons\OverSea\Model;
use Addons\OverSea\Common\MySqlHelper;
use Addons\OverSea\Common\Log;

class UsersDao
{
    public static function insertUser($data)
    {
        try {
            date_default_timezone_set('PRC');
            $data['creation_date'] = date('y-m-d H:i:s',time());
            $tmpData = array();
            foreach ($data as $k => $v) {
                //echo $k."-".$v;
                $tmpData[':' . $k] = $v;
            }
            $sql = 'INSERT INTO yz_users (' . implode(',', array_keys($data)) . ') VALUES (' . implode(',', array_keys($tmpData)) . ')';
            //echo $sql;
            MySqlHelper::query($sql, $tmpData);
        } catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
        }
        return MySqlHelper::getLastInsertId();
    }

    public static function updateUser($data, $id)
    {
        try {
            date_default_timezone_set('PRC');
            $data['creation_date'] = date('y-m-d H:i:s',time());
            $sql = "update yz_users set ";
            foreach ($data as $k => $v) {
                //echo $k."-".$v;
                if ($v != null && $v != ''){
                    $sql = $sql. $k."='".$v."',";
                }
            }
            $sql = substr($sql,0, strlen($sql) -1 );
            $sql= $sql.' where id =:id';
            //echo $sql . $id;
            MySqlHelper::query($sql, array(':id' => $id));
            return 0;
        } catch (\Exception $e){
            return -1;
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
        }
    }
    public static function updateExternalId($external_id, $id)
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

    public static function getUserByPhone($phone_reigon, $phone_number)
    {
        $sql = 'SELECT * FROM yz_users WHERE phone_reigon = :phone_reigon and phone_number = :phone_number LIMIT 1';
        $user = MySqlHelper::fetchOne($sql, array(':phone_reigon' => $phone_reigon, ':phone_number' => $phone_number));
        return $user;
    }

    public static function getUserById($id)
    {
        try {
            $sql = 'SELECT * FROM yz_users WHERE id= :id LIMIT 1';
            //echo $sql;
            $user = MySqlHelper::fetchOne($sql, array(':id' => $id));
            return $user;
        }catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
            exit(1);
        }

    }
    
    public static function getUserByExternalId($external_id)
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