<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/2
 * Time: 11:55
 */
namespace Addons\OverSea\Model;
use Addons\OverSea\Common\MySqlHelper;

class PaymentsDao
{
    public static function insertOrder($data)
    {
        try {
            $tmpData = array();
            foreach ($data as $k => $v) {
                //echo $k."-".$v;
                $tmpData[':' . $k] = $v;
            }
            $sql = 'INSERT INTO clc_payments (' . implode(',', array_keys($data)) . ') VALUES (' . implode(',', array_keys($tmpData)) . ')';
            //echo $sql;
            MySqlHelper::query($sql, $tmpData);
        } catch (\Exception $e){
            echo $e;
            exit;
        }
        return MySqlHelper::getLastInsertId();
    }
    
}
?>