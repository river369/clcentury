<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/18
 * Time: 10:08
 */

namespace Addons\OverSea\Model;

use Addons\OverSea\Model\OrdersDao;
use Addons\OverSea\Model\OrderActionsDao;


class OrdersBo
{
    public function __construct() {
    }

    public function createOrder() {
        $orderData = array();
        
        $orderData['sellerid'] = isset($_POST ['sellerid']) ? $_POST ['sellerid'] : "";
        $orderData['sellername'] = isset($_POST ['sellername']) ? $_POST ['sellername'] : "";
        $orderData['conditions'] = 0;
        $orderData['customerid'] = isset($_POST ['customerid']) ? $_POST ['customerid'] : "";
        $orderData['servicearea']  = isset($_POST ['servicearea']) ? $_POST ['servicearea'] : "";
        $orderData['servicetype']  = isset($_POST ['servicetype']) ? $_POST ['servicetype'] : "";
        $orderData['serviceprice']  = isset($_POST ['serviceprice']) ?$_POST ['serviceprice'] : "";
        $orderData['servicepriceunit']  = isset($_POST ['servicepriceunit']) ? $_POST ['servicepriceunit'] : "";
        $orderData['servicehours']  = isset($_POST ['servicehours']) ? $_POST ['servicehours'] : "";
        $orderData['servicetotalfee']  = isset($_POST ['servicetotalfee']) ? $_POST ['servicetotalfee'] : "";
        $orderData['requestmessage']  = isset($_POST ['requestmessage']) ? $_POST ['requestmessage'] : "";


        $orderid = OrdersDao::insertOrder($orderData);
        if ($orderid) {
            $orderActionData = array();
            $orderActionData['orderid'] = $orderid;
            $orderActionData['action'] = 0;
            $orderActionData['creation_date'] = date('y-m-d h:i:s',time());
            $orderActionData['actioner'] = 1;
            OrderActionsDao::insertOrderAction($orderActionData);
            $_SESSION['createOrderStatus'] = '成功';
        } else {
            $_SESSION['createOrderStatus'] = '失败';
        }
        $_SESSION['orderData']= $orderData;
    }

    // Get Order Lists
    public function getOrderByCustomerAndCondition() {
        $customerid  = isset($_GET ['customerid']) ? $_GET ['customerid'] : $_SESSION['signedUser'];
        $condition = $_GET ['condition'];
        $orders = OrdersDao::getOrderByCustomerAndCondition($customerid, $condition);
        $_SESSION['customerOrders'] = $orders;
        $_SESSION['customerId'] = $customerid;
        $_SESSION['customerOrdersCondition'] = $condition;
    }

    public function getOrderBySellerAndCondition() {
        $sellerid  = $customerid  = isset($_GET ['sellerid']) ? $_GET ['sellerid'] : $_SESSION['signedUser'];
        $condition = $_GET ['condition'];
        $orders = OrdersDao::getOrderBySellerAndCondition($sellerid, $condition);
        $_SESSION['sellerOrders'] = $orders;
        $_SESSION['sellerid'] = $sellerid;
        $_SESSION['SellerOrdersCondition'] = $condition;
    }

    // Order Operations
    public function rejectOrder() {
        $orderid  = $_POST ['rejectorderid'];
        $reason = $_POST ['rejectreason'];
        $condition = 102;
        $sellerid = $_SESSION['signedUser'];
        OrdersDao::updateOrderCondition($orderid, $condition, $sellerid);
        self::storeOrderActions($orderid, $condition, 2, $reason);
    }

    public function acceptOrder() {
        $orderid  = $_POST ['acceptorderid'];
        $condition = 2;
        $sellerid = $_SESSION['signedUser'];
        OrdersDao::updateOrderCondition($orderid, $condition, $sellerid);
        self::storeOrderActions($orderid, $condition, 2);
    }

    public function cancelOrder() {
        $orderid  = $_POST ['cancelorderid'];
        $reason = $_POST ['cancelreason'];
        $condition = 104;
        $sellerid = $_SESSION['signedUser'];
        OrdersDao::updateOrderCondition($orderid, $condition, $sellerid);
        self::storeOrderActions($orderid, $condition, 2, $reason);
    }

    public function finishOrder() {
        $orderid  = $_POST ['finishorderid'];
        $condition = 4;
        $sellerid = $_SESSION['signedUser'];
        OrdersDao::updateOrderCondition($orderid, $condition, $sellerid);
        self::storeOrderActions($orderid, $condition, 2);
    }

    public function storeOrderActions($orderid, $condition, $actioner, $reason = '') {
        $orderActionData = array();
        $orderActionData['orderid'] = $orderid;
        $orderActionData['action'] = $condition;
        $orderActionData['creation_date'] = date('y-m-d h:i:s',time());
        $orderActionData['actioner'] = 2;
        OrderActionsDao::insertOrderAction($orderActionData);
    }
}