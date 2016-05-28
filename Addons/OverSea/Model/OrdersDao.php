<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/2
 * Time: 11:55
 */
namespace Addons\OverSea\Model;
use Addons\OverSea\Common\MySqlHelper;

class OrdersDao
{
    public static function updateOrderCondition($orderid, $condition, $sellerid)
    {
        try {
            $sql = "update clc_orders set conditions = :condition where id =:id and sellerid=:sellerid";
            //echo $sql
            MySqlHelper::query($sql, array(':condition' => $condition, ':id' => $orderid, ':sellerid' => $sellerid ));
            return 0;
        } catch (\Exception $e){
            return -1;
            echo $e;
        }
    }

    public static function getOrderBySellerAndCondition($sellerid, $condition)
    {
        try {
            $sql = 'SELECT * FROM clc_orders WHERE sellerid= :sellerid and conditions= :condition';
            //echo $sql;
            $orders = MySqlHelper::fetchAll($sql, array(':sellerid' => $sellerid, ':condition' => $condition));
            return $orders;
        } catch (\Exception $e){
            echo $e;
            exit;
        }
    }
    
    public static function getOrderByCustomerAndCondition($customerid, $condition)
    {
        try {
            $sql = 'SELECT * FROM clc_orders WHERE customerid= :customerid and conditions= :condition';
            //echo $sql;
            $orders = MySqlHelper::fetchAll($sql, array(':customerid' => $customerid, ':condition' => $condition));
            return $orders;
        } catch (\Exception $e){
            echo $e;
            exit;
        }
    }
    
    public static function getOrderById($id)
    {
        try {
            $sql = 'SELECT * FROM clc_order_actions WHERE id= :id LIMIT 1';
            //echo $sql;
            $order = MySqlHelper::fetchOne($sql, array(':id' => $id));
            return $order;
        } catch (\Exception $e){
            echo $e;
            exit;
        }
    }
    
    public static function insertOrder($data)
    {
        try {
            //$data['create_date'] = time();
            $tmpData = array();
            foreach ($data as $k => $v) {
                //echo $k."-".$v;
                $tmpData[':' . $k] = $v;
            }
            $sql = 'INSERT INTO clc_orders (' . implode(',', array_keys($data)) . ') VALUES (' . implode(',', array_keys($tmpData)) . ')';
            echo $sql;
            MySqlHelper::query($sql, $tmpData);
        } catch (\Exception $e){
            echo $e;
            exit;
        }
        return MySqlHelper::getLastInsertId();
    }

    public static function updateOrder($data, $id)
    {
        try {
            $sql = "update clc_orders set ";
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