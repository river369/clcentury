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
    public function __construct()
    {
        parent::__construct("yz_users");
    }

    public function updateExternalId($external_id, $id)
    {
        try {
            $sql = "update yz_users set external_id = :external_id where id =:id";
            $parameter = array(':external_id' => $external_id, ':id' => $id);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            MySqlHelper::query($sql, $parameter);
            return 0;
        } catch (\Exception $e){
            return -1;
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
        }
    }

    public function getUserByPhone($phone_reigon, $phone_number)
    {
        $sql = 'SELECT * FROM yz_users WHERE phone_reigon = :phone_reigon and phone_number = :phone_number LIMIT 1';
        $parameter = array(':phone_reigon' => $phone_reigon, ':phone_number' => $phone_number);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
        $user = MySqlHelper::fetchOne($sql, $parameter);
        return $user;
    }
    
    public function getUserByExternalId($external_id)
    {
        try {
            $sql = 'SELECT * FROM yz_users WHERE external_id = :external_id LIMIT 1';
            $parameter = array(':external_id' => $external_id);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $user = MySqlHelper::fetchOne($sql, $parameter);
            return $user;
        }catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
            exit(1);
        }

    }
}
?>