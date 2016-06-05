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
use Addons\OverSea\Model\PaymentsDao;


class OrdersBo
{
    public function __construct() {
    }

    // create order
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

        $userData=UsersDao::getUserById($orderData['customerid']);
        $customername = (isset($userData['name']) && !is_null($userData['name']))? $userData['name']: "匿名用户";
        $orderData['customername'] = $customername;

        $orderid = OrdersDao::insertOrder($orderData);
        $orderData['id'] = $orderid;
        $_SESSION['orderData']= $orderData;
        if ($orderid) {
            self::storeOrderActions($orderid, 0, 1);
            $_SESSION['createOrderStatus'] = '成功';
            header('Location:'."../Controller/wxpayv3/PrePayJs.php");
            exit;
        } else {
            $_SESSION['createOrderStatus'] = '失败';
        }

    }

    //For the case order are created but pay is delayed by user
    public function repayOrder() {
        $orderId = $_GET['orderid'];
        $orderDetail = OrdersDao::getOrderById($orderId);
        $_SESSION['orderData']= $orderDetail;
        $_SESSION['createOrderStatus'] = '成功';
        header('Location:'."../Controller/wxpayv3/PrePayJs.php");
        exit;
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

    // Get order details
    public function getOrderDetailsById(){
        $orderId = $_GET['orderid'];
        $orderDetail = OrdersDao::getOrderById($orderId);
        $orderActionDetails = OrderActionsDao::getOrderActionsByOrderId($orderId);
        $_SESSION['orderDetail'] = $orderDetail;
        $_SESSION['orderActionDetails'] = $orderActionDetails;
    }

    // Order Operations
    public function sellerRejectOrder() {
        $orderid  = $_POST ['rejectorderid'];
        $reason = $_POST ['rejectreason'];
        $condition = 1020;
        $sellerid = $_SESSION['signedUser'];
        OrdersDao::updateSellerOrderCondition($orderid, $condition, $sellerid);
        self::storeOrderActions($orderid, $condition, 2, $reason);
    }

    public function sellerAcceptOrder() {
        $orderid  = $_POST ['acceptorderid'];
        $condition = 20;
        $sellerid = $_SESSION['signedUser'];
        OrdersDao::updateSellerOrderCondition($orderid, $condition, $sellerid);
        self::storeOrderActions($orderid, $condition, 2);
    }

    public function sellerCancelOrder() {
        $orderid  = $_POST ['cancelorderid'];
        $reason = $_POST ['cancelreason'];
        $condition = 1040;
        $sellerid = $_SESSION['signedUser'];
        OrdersDao::updateSellerOrderCondition($orderid, $condition, $sellerid);
        self::storeOrderActions($orderid, $condition, 2, $reason);
    }

    public function sellerFinishOrder() {
        $orderid  = $_POST ['finishorderid'];
        $condition = 40;
        $sellerid = $_SESSION['signedUser'];
        OrdersDao::updateSellerOrderCondition($orderid, $condition, $sellerid);
        self::storeOrderActions($orderid, $condition, 2);
    }

    public function customerConfirmOrder() {
        $orderid  = $_POST ['confirmorderid'];
        $condition = 60;
        $customerid = $_SESSION['signedUser'];
        OrdersDao::updateCustomerOrderCondition($orderid, $condition, $customerid);
        self::storeOrderActions($orderid, $condition, 1);
    }

    public function customerCancelOrder() {
        $orderid  = $_POST ['cancelorderid'];
        $reason = $_POST ['cancelreason'];
        $condition = 1060;
        $customerid = $_SESSION['signedUser'];
        OrdersDao::updateCustomerOrderCondition($orderid, $condition, $customerid);
        self::storeOrderActions($orderid, $condition, 1, $reason);
    }

    public function storeOrderActions($orderid, $condition, $actioner, $reason = '') {
        $orderActionData = array();
        $orderActionData['orderid'] = $orderid;
        $orderActionData['action'] = $condition;
        date_default_timezone_set('PRC');
        $orderActionData['creation_date'] = date('y-m-d H:i:s',time());
        $orderActionData['actioner'] = $actioner;
        $orderActionData['comments'] = $reason;
        OrderActionsDao::insertOrderAction($orderActionData);
    }

    /**
     * When weixin call back with notify, the function is used
     * @param $paymentData
     */
    public static function paymentConfirmOrder($paymentData) {
        $orderid  = $paymentData ['order_id'];
        $condition = 10;
        OrdersDao::updateOrderCondition($orderid, $condition);
        self::storeOrderActions($orderid, $condition, 0);
        PaymentsDao::insertOrder($paymentData);
    }
    
}