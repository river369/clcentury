<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/18
 * Time: 10:08
 */

namespace Addons\OverSea\Model;

use Addons\OverSea\Model\OrdersDao;


class OrdersBo
{
    public function __construct() {
    }

    public function createOrder() {
        $orderData = array();
        $orderData['sellerid'] = isset($_POST ['sellerid']) ? $_POST ['sellerid'] : "";
        $orderData['customerid'] = isset($_POST ['customerid']) ? $_POST ['customerid'] : "";
        $orderData['servicearea']  = isset($_POST ['servicearea']) ? $_POST ['servicearea'] : "";
        $orderData['servicetype']  = isset($_POST ['servicetype']) ? $_POST ['servicetype'] : "";
        $orderData['serviceprice']  = isset($_POST ['serviceprice']) ?$_POST ['serviceprice'] : "";
        $orderData['servicepriceunit']  = isset($_POST ['servicepriceunit']) ? $_POST ['servicepriceunit'] : "";
        $orderData['servicehours']  = isset($_POST ['servicehours']) ? $_POST ['servicehours'] : "";
        $orderData['servicetotalfee']  = isset($_POST ['servicetotalfee']) ? $_POST ['servicetotalfee'] : "";
        $orderData['requestmessage']  = isset($_POST ['requestmessage']) ? $_POST ['requestmessage'] : "";
        
        if (OrdersDao::insertOrder($orderData)) {
            $_SESSION['createOrderStatus'] = '成功';
        } else {
            $_SESSION['createOrderStatus'] = '失败';
        }
        $_SESSION['orderData']= $orderData;
    }


}