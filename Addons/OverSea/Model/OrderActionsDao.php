<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/2
 * Time: 11:55
 */
namespace Addons\OverSea\Model;
use Addons\OverSea\Common\MySqlHelper;

class OrderActionsDao
{
    public static function getOrderActionById($id)
    {
        $sql = 'SELECT * FROM clc_order_actions WHERE id= :id LIMIT 1';
        //echo $sql;
        $orderAction = MySqlHelper::fetchOne($sql, array(':id' => $id));
        return $orderAction;
    }

    public static function getOrderActionsByOrderId($orderid)
    {
        $sql = 'SELECT * FROM clc_order_actions WHERE orderid= :orderid';
        //echo $sql;
        $orderActions = MySqlHelper::fetchAll($sql, array(':orderid' => $orderid));
        return $orderActions;

    }
    
    public static function insertOrderAction($data)
    {

        try {
            //$data['create_date'] = time();
            $tmpData = array();
            foreach ($data as $k => $v) {
                //echo $k."-".$v;
                $tmpData[':' . $k] = $v;
            }
            $sql = 'INSERT INTO clc_order_actions (' . implode(',', array_keys($data)) . ') VALUES (' . implode(',', array_keys($tmpData)) . ')';
            //echo $sql;
            MySqlHelper::query($sql, $tmpData);
        } catch (\Exception $e){
            echo $e;
            exit;
        }
        return MySqlHelper::getLastInsertId();
    }

    public static function updateOrderAction($data, $id)
    {
        try {
            $sql = "update clc_order_actions set ";
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
    
}
?>