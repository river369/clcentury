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
use Addons\OverSea\Model\CommentsDao;

use Addons\OverSea\Common\Logs;

class OrdersBo
{
    public function __construct() {
    }

    // create order
    public function createOrder() {
        $orderData = array();

        $orderData['service_id'] = isset($_POST ['service_id']) ? $_POST ['service_id'] : "";
        $orderData['service_name'] = isset($_POST ['service_name']) ? $_POST ['service_name'] : "";
        $orderData['seller_id'] = isset($_POST ['seller_id']) ? $_POST ['seller_id'] : "";
        $orderData['seller_name'] = isset($_POST ['seller_name']) ? $_POST ['seller_name'] : "";
        $orderData['customer_id'] = isset($_POST ['customer_id']) ? $_POST ['customer_id'] : "";
        $orderData['status'] = 0;
        $orderData['service_area']  = isset($_POST ['service_area']) ? $_POST ['service_area'] : "";
        $orderData['service_type']  = isset($_POST ['service_type']) ? $_POST ['service_type'] : "";
        $orderData['service_price']  = isset($_POST ['service_price']) ?$_POST ['service_price'] : "";
        $orderData['service_price_unit']  = isset($_POST ['service_price_unit']) ? $_POST ['service_price_unit'] : "";
        $orderData['service_hours']  = isset($_POST ['service_hours']) ? $_POST ['service_hours'] : "";
        $orderData['service_total_fee']  = isset($_POST ['service_total_fee']) ? $_POST ['service_total_fee'] : "";
        $orderData['request_message']  = isset($_POST ['request_message']) ? $_POST ['request_message'] : "";

        $userDao = new UsersDao();
        $userData=$userDao->getById($orderData['customer_id']);
        $customerName = (isset($userData['name']) && !is_null($userData['name']))? $userData['name']: "匿名用户";
        $orderData['customer_name'] = $customerName;

        $ordersDao = new OrdersDao();
        $orderid = $ordersDao->insert($orderData);
        $orderData['id'] = $orderid;
        $_SESSION['orderData']= $orderData;
        if ($orderid) {
            self::storeOrderActions($orderid, 0, 1);
            $_SESSION['createOrderStatus'] = '成功';
            header('Location:'."../Controller/wxpayv3/PrePayJs.php");
            exit;
        } else {
            $_SESSION['status'] = 'f';
            $_SESSION['message'] = '创建订单失败!';
            $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=mine";
        }

    }

    //For the case order are created but pay is delayed by user
    public function repayOrder() {
        $orderId = $_GET['order_id'];
        $ordersDao = new OrdersDao();
        $orderDetail = $ordersDao->getById($orderId);
        $_SESSION['orderData']= $orderDetail;
        $_SESSION['createOrderStatus'] = '成功';
        header('Location:'."../Controller/wxpayv3/PrePayJs.php");
        exit;
    }


    // Get Order Lists
    public function getOrdersByCustomerAndStatus() {
        $customerid  = isset($_GET ['customer_id']) ? $_GET ['customer_id'] : $_SESSION['signedUser'];
        $status= $_GET ['status'];
        $ordersDao = new OrdersDao();
        $orders = $ordersDao->getOrdersByCustomerAndStatus($customerid, $status);
        $_SESSION['customerOrders'] = $orders;
        $_SESSION['customerId'] = $customerid;
        $_SESSION['customerOrdersStatus'] = $status;
    }

    public function getOrdersBySellerAndStatus() {
        $sellerid  = $customerid  = isset($_GET ['seller_id']) ? $_GET ['seller_id'] : $_SESSION['signedUser'];
        $status = $_GET ['status'];
        $ordersDao = new OrdersDao();
        $orders = $ordersDao->getOrdersBySellerAndStatus($sellerid, $status);
        $_SESSION['sellerOrders'] = $orders;
        $_SESSION['sellerid'] = $sellerid;
        $_SESSION['sellerOrdersStatus'] = $status;
    }

    // Get order details
    public function getOrderDetailsById(){
        $orderId = $_GET['order_id'];
        $orderDetail = self::getOrderById($orderId);
        self::getOrderActionsById($orderId);
        self::getCommentForOrder($orderId);

        $orderStatus = $orderDetail['status'];
        unset($_SESSION['sellerData']);
        if ($orderStatus >= 20 and $orderStatus < 1000){
            self::getSellerById($orderDetail['seller_id']);
        }
    }

    public function getOrderById($orderId){
        $ordersDao = new OrdersDao();
        $orderDetail = $ordersDao->getById($orderId);
        $_SESSION['orderDetail'] = $orderDetail;
        return $orderDetail;
    }

    public function getOrderActionsById($orderId){
        $orderActionsDao = new OrderActionsDao ();
        $orderActionDetails = $orderActionsDao->getOrderActionsByOrderId($orderId);
        $_SESSION['orderActionDetails'] = $orderActionDetails;
    }
    
    public function getCommentForOrder($orderId) {
        $commentsDao = new CommentsDao();
        $commentsData = $commentsDao ->getCommentsByOrderId($orderId);
        $_SESSION['commentsData']= $commentsData;
    }

    public function getSellerById($sellId) {
        $userDao = new UsersDao();
        $userData=$userDao->getById($sellId);
        $_SESSION['sellerData']= $userData;
    }


    // Order Operations
    public function sellerRejectOrder() {
        $orderid  = $_POST ['rejectorderid'];
        $reason = $_POST ['rejectreason'];
        $status = 1020;
        $sellerid = $_SESSION['signedUser'];
        $ordersDao = new OrdersDao();
        $ordersDao->updateSellerOrderStatus($orderid, $status, $sellerid);
        self::storeOrderActions($orderid, $status, 2, $reason);
    }

    public function sellerAcceptOrder() {
        $orderid  = $_POST ['acceptorderid'];
        $status = 20;
        $sellerid = $_SESSION['signedUser'];
        $ordersDao = new OrdersDao();
        $ordersDao->updateSellerOrderStatus($orderid, $status, $sellerid);
        self::storeOrderActions($orderid, $status, 2);
    }

    public function sellerCancelOrder() {
        $orderid  = $_POST ['cancelorderid'];
        $reason = $_POST ['cancelreason'];
        $status = 1040;
        $sellerid = $_SESSION['signedUser'];
        $ordersDao = new OrdersDao();
        $ordersDao->updateSellerOrderStatus($orderid, $status, $sellerid);
        self::storeOrderActions($orderid, $status, 2, $reason);
    }

    public function sellerFinishOrder() {
        $orderid  = $_POST ['finishorderid'];
        $reason = $_POST ['finishreason'];
        $status = 40;
        $sellerid = $_SESSION['signedUser'];
        $ordersDao = new OrdersDao();
        $ordersDao->updateSellerOrderStatus($orderid, $status, $sellerid);
        self::storeOrderActions($orderid, $status, 2, $reason);
    }

    public function customerConfirmOrder() {
        $orderid  = $_POST ['confirmorderid'];
        $status = 60;
        $customerid = $_SESSION['signedUser'];
        $ordersDao = new OrdersDao();
        $ordersDao->updateCustomerOrderStatus($orderid, $status, $customerid);
        self::storeOrderActions($orderid, $status, 1);
        self::getOrderById($orderid);
    }

    public function customerCommentOrder() {
        $comments = array();
        $comments['service_id'] = $_POST ['service_id'];
        $comments['order_id'] = $_POST ['order_id'];
        $comments['seller_id'] = $_POST ['seller_id'];
        $comments['customer_id'] = $_POST ['customer_id'];
        $comments['seller_name'] = $_POST ['seller_name'];
        $comments['customer_name'] = $_POST ['customer_name'];
        $comments['stars'] = $_POST ['star'];
        $comments['comments'] = isset($_POST ['comments']) ? trim($_POST ['comments']) : "";

        $customer_id = $_SESSION['signedUser'];
        if($customer_id == $comments['customer_id']){
            $commentsDao = new CommentsDao();
            $commentsDao->insert($comments);
            $status = 80;
            $order_id = $comments['order_id'];
            $ordersDao = new OrdersDao();
            $ordersDao->updateCustomerOrderStatus($order_id, $status, $customer_id);
            self::storeOrderActions($order_id, $status, 1);
        }
    }
    
    public function customerCancelOrder() {
        $orderid  = $_POST ['cancelorderid'];
        $reason = $_POST ['cancelreason'];
        $status = 1060;
        $customerid = $_SESSION['signedUser'];
        $ordersDao = new OrdersDao();
        $ordersDao->updateCustomerOrderStatus($orderid, $status, $customerid);
        self::storeOrderActions($orderid, $status, 1, $reason);
    }

    public function customerRejectOrder() {
        $orderid  = $_POST ['rejectorderid'];
        $reason = $_POST ['rejectreason'];
        $status = 70;
        $customerid = $_SESSION['signedUser'];
        $ordersDao = new OrdersDao();
        $ordersDao->updateCustomerOrderStatus($orderid, $status, $customerid);
        self::storeOrderActions($orderid, $status, 1, $reason);
    }


    public function storeOrderActions($orderid, $status, $actioner, $reason = '') {
        $orderActionData = array();
        $orderActionData['order_id'] = $orderid;
        $orderActionData['action'] = $status;
        date_default_timezone_set('PRC');
        $orderActionData['creation_date'] = date('y-m-d H:i:s',time());
        $orderActionData['actioner'] = $actioner;
        $orderActionData['comments'] = $reason;
        $orderActionsDao = new OrderActionsDao ();
        $orderActionsDao->insert($orderActionData);
    }

    /**
     * When weixin call back with notify, the function is used
     * @param $paymentData
     */
    public static function paymentConfirmOrder($paymentData) {
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",paymentData=".json_encode($paymentData));
        $orderid  = $paymentData ['order_id'];
        $status = 10;
        $ordersDao = new OrdersDao();
        $ordersDao->updateOrderStatus($orderid, $status);
        self::storeOrderActions($orderid, $status, 0);
        $paymentDao = new PaymentsDao();
        $paymentDao->insert($paymentData);
    }
    
}