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

    public static function updateUser($data, $id)
    {
        try {
            $sql = "update clc_users set ";
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
            echo $e;
        }
    }

    public static function getUserByPhone($phonereigon, $phonenumber)
    {
        $sql = 'SELECT * FROM clc_users WHERE phonereigon = :phonereigon and phonenumber = :phonenumber LIMIT 1';
        $user = MySqlHelper::fetchOne($sql, array(':phonereigon' => $phonereigon, ':phonenumber' => $phonenumber));
        return $user;
    }

    public static function getUserByOpenid($openid)
    {
        $sql = 'SELECT * FROM clc_users WHERE openid = :openid LIMIT 1';
        //echo $sql;
        $user = MySqlHelper::fetchOne($sql, array(':openid' => $openid));
        return $user;
    }

    public static function getUsersByServiceType($servicetype)
    {
        $sql = 'SELECT * FROM clc_users WHERE servicetype in (99999, :servicetype) order by stars desc';
        $users = MySqlHelper::fetchAll($sql, array(':servicetype' => $servicetype));
        return $users;
    }
    
}
?>