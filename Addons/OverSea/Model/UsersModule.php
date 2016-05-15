<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/2
 * Time: 11:55
 */
namespace Addons\OverSea\Model;
use Addons\OverSea\Common\MySqlHelper;
require dirname(__FILE__).'/../init.php';

class UsersModule
{
    public static function insertUser($data)
    {
        try {
            //$data['create_date'] = time();
            $tmpData = array();
            foreach ($data as $k => $v) {
                //echo $k."-".$v;
                $tmpData[':' . $k] = $v;
            }
            $sql = 'INSERT INTO clc_users (' . implode(',', array_keys($data)) . ') VALUES (' . implode(',', array_keys($tmpData)) . ')';
            //echo $sql;
            MySqlHelper::query($sql, $tmpData);
        } catch (\Exception $e){
            echo $e;
        }
        return MySqlHelper::getLastInsertId();
    }

    public static function getUserByPhone($phonereigon, $phonenumber)
    {
        $sql = 'SELECT * FROM clc_users WHERE phonereigon = :phonereigon and phonenumber = :phonenumber LIMIT 1';
        $user = MySqlHelper::fetchOne($sql, array(':phonereigon' => $phonereigon, ':phonenumber' => $phonenumber));
        return $user;
    }

    public static function getUsersByServiceType($servicetype)
    {
        $sql = 'SELECT * FROM clc_users WHERE servicetype in (0, :servicetype) order by stars desc';
        $users = MySqlHelper::fetchAll($sql, array(':servicetype' => $servicetype));
        return $users;
    }
    
}
?>