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
        //$data['create_date'] = time();
        $tmpData = array();
        foreach ($data as $k => $v) {
            //echo $k."-".$v;
            $tmpData[':' . $k] = $v;
        }
        $sql = 'INSERT INTO clc_users (' . implode(',', array_keys($data)) . ') VALUES (' . implode(',', array_keys($tmpData)) . ')';
        //echo $sql;
        MySqlHelper::query($sql, $tmpData);
        return MySqlHelper::getLastInsertId();
    }

    public static function getUser()
    {
        $sql = 'SELECT * FROM clc_users WHERE servicetype = :servicetype LIMIT 1';
        $user = MySqlHelper::fetchOne($sql, array(':servicetype' => 1));

        if (isset($user['name']) === false) {
            echo $user['name'];
        } else {
            echo $user['name'];
        }
        
    }

}
?>