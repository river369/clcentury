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

class OrdersDao extends BaseDao
{
    /**
     * OrdersDao constructor.
     */
    public function __construct()
    {
        parent::__construct("yz_orders");
    }

    public function updateSellerOrderStatus($order_id, $status, $seller_id)
    {
        try {
            $sql = "update " . parent::getTableName(). " set status = :status where order_id =:order_id and seller_id=:seller_id";
            $parameter = array(':status' => $status, ':order_id' => $order_id, ':seller_id' => $seller_id );
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            MySqlHelper::query($sql, $parameter);
            return 0;
        } catch (\Exception $e){
            return -1;
            echo $e;
        }
    }

    public function updateCustomerOrderStatus($order_id, $status, $customer_id)
    {
        try {
            $sql = "update " . parent::getTableName(). " set status = :status where order_id =:order_id and customer_id=:customer_id";
            $parameter = array(':status' => $status, ':order_id' => $order_id, ':customer_id' => $customer_id );
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            MySqlHelper::query($sql, $parameter);
            return 0;
        } catch (\Exception $e){
            return -1;
            echo $e;
        }
    }

    public function updateOrderStatus($order_id, $status)
    {
        try {
            $sql = "update " . parent::getTableName(). " set status = :status where order_id =:order_id";
            $parameter = array(':status' => $status, ':order_id' => $order_id);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            MySqlHelper::query($sql, $parameter);
            return 0;
        } catch (\Exception $e){
            return -1;
            echo $e;
        }
    }

    public function getOrdersBySellerAndStatus($seller_id, $status)
    {
        try {
            //$sql = 'SELECT * FROM ' . parent::getTableName(). ' WHERE seller_id= :seller_id and status in ( :status )';
            $sql = 'SELECT * FROM ' . parent::getTableName(). ' WHERE seller_id= :seller_id and status in (' . $status . ') order by creation_date desc';
            //$parameter = array(':seller_id' => $seller_id, ':status' => $status);
            $parameter = array(':seller_id' => $seller_id);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $orders = MySqlHelper::fetchAll($sql, $parameter);
            return $orders;
        } catch (\Exception $e){
            echo $e;
            exit;
        }
    }
    
    public function getOrdersByCustomerAndStatus($customer_id, $status)
    {
        try {
            $sql = 'SELECT * FROM ' . parent::getTableName(). ' WHERE customer_id= :customer_id and status in (' . $status . ') order by creation_date desc';
            //$sql = "SELECT * FROM " . parent::getTableName(). " WHERE customer_id= :customer_id and status in ( :status )";
            $parameter = array(':customer_id' => $customer_id);
            //$parameter = array(':customer_id' => $customer_id, ':status' => $status);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $orders = MySqlHelper::fetchAll($sql, $parameter);
            return $orders;
        } catch (\Exception $e){
            echo $e;
            exit;
        }
    }
    
}
?>