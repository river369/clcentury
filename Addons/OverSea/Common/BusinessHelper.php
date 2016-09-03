<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/30
 * Time: 22:57
 */
namespace Addons\OverSea\Common;

class BusinessHelper {
    // ===========  实名认证 ============= //
    public static function translateRealNameStatus($status) {
        $statusString = '尚未提交实名认证申请';
        switch ($status)
        {
            case 20:
                $statusString = "实名认证审核中";
                break;
            case 40:
                $statusString = "实名认证被拒绝";
                break;
            case 60:
                $statusString = "实名认证已通过";
                break;

        }
        return $statusString;
    }

    // ===========  服务审核 ============= //
    public static function translateServiceCheckStatus($status) {
        $statusString = '准备提交';
        switch ($status) {
            case 20:
                $statusString = "服务审核中";
                break;
            case 40:
                $statusString = "服务被拒绝";
                break;
            case 60:
                $statusString = "服务审核已通过";
                break;
            case 80:
                $statusString = "服务被卖家删除";
                break;
            case 100:
                $statusString = "服务被卖家暂停";
                break;
        }
        return $statusString;
    }

    // ===========  订单 ============= //

    public static function translateOrderStatus($status)
    {
        $orderStatus = '';
        switch ($status)
        {
            case 0:
                $orderStatus = "订单已创建";
                break;
            case 10:
                $orderStatus = "买家已付款,等待卖家接收";
                break;
            case 1020:
                $orderStatus = "卖家拒绝了该订单";
                break;
            case 20:
                $orderStatus = "卖家已接收";
                break;
            case 1040:
                $orderStatus = "卖家取消了该订单";
                break;
            case 40:
                $orderStatus = "卖家已将订单置为完成,等待买家确认";
                break;
            case 1060:
                $orderStatus = "买家取消了该订单";
                break;
            case 1080:
                $orderStatus = "易知海外已完成退款,订单结束";
                break;
            case 60:
                $orderStatus = "买家已将订单置为完成,等待易知海外付款";
                break;
            case 70:
                $orderStatus = "买家提出服务争议";
                break;
            case 80:
                $orderStatus = "买家完成评论";
                break;
            case 100:
                $orderStatus = "易知海外已完成付款,订单结束";
                break;

        }
        return $orderStatus;
    }
    public static function translateOrderNotifyMessages($status, $order)
    {
        $ret = '';
        switch ($status)
        {
            case 10:
                $ret = "您好,".$order['seller_name']."。买家'".$order['customer_name']."'购买了您的服务'".$order['service_name']."'。请您尽快接收服务,避免买家取消带来经济损失。多谢!" ;
                break;
            case 1060:
                $ret = "您好,".$order['seller_name']."。买家'".$order['customer_name']."'取消了您的服务'".$order['service_name']."'。" ;
                break;
            case 70:
                $ret = "您好,".$order['seller_name']."。买家'".$order['customer_name']."'对您的服务'".$order['service_name']."'提出异议。请尽快协助解决,避免经济损失。多谢!" ;
                break;
            case 60:
                $ret = "您好,".$order['seller_name']."。买家'".$order['customer_name']."'已经确认服务'".$order['service_name']."'履约完成。" ;
                break;
            case 100:
                $ret = "您好,".$order['seller_name']."。易知海外已经对服务".$order['service_name']."退款完成。请尽快查收!" ;
                break;
            case 1040:
                $ret = "您好,".$order['customer_name']."。卖家'".$order['seller_name']."'取消了您的订单。" ;
                break;
            case 1020:
                $ret = "您好,".$order['customer_name']."。卖家'".$order['seller_name']."'拒绝了您的订单。" ;
                break;
            case 20:
                $ret = "您好,".$order['customer_name']."。卖家'".$order['seller_name']."'接收了您的订单。" ;
                break;
            case 40:
                $ret = "您好,".$order['customer_name']."。卖家'".$order['seller_name']."'已将订单置为完成,等待买家确认。如果您在48小时内未处理, 易知海外将自动确认完成。多谢!" ;
                break;
            case 1080:
                $ret = "您好,".$order['customer_name']."。易知海外已经对服务".$order['service_name']."退款完成。请尽快查收!" ;
                break;
        }
        return $ret;
    }
    public static function translateOrderResponseUserType($status)
    {
        $ret = '';
        switch ($status)
        {
            case 10:
            case 1060:
            case 1080:
            case 70:
            case 60:
            case 100:
                $ret = "seller";
                break;
            case 1040:
            case 1020:
            case 20:
            case 40:
                $ret = "customer";
                break;
        }
        return $ret;
    }

    public static function translateSellerOrderTabDesc($ordersStatus)
    {
        $querystatusString = "买家已经付款,请您尽快接收以下订单,避免买家取消带来经济损失。";
        if ($ordersStatus == 20) {
            $querystatusString = "您已接收的订单。请等待卖家联系, 并确保订单按时执行。";
        } else if ($ordersStatus == 40 || $ordersStatus == 60 || $ordersStatus == 80 ) {
            $querystatusString = "您已完成的订单。等待买家确认, 易知海外支付。";
        } else if ($ordersStatus == 70) {
            $querystatusString = "买家认为有争议的订单,请及时处理。";
        } else if ($ordersStatus == 100 ) {
            $querystatusString = "易知海外已完成支付的订单。";
        } else if ($ordersStatus == 1020 || $ordersStatus == 1040 || $ordersStatus == 1060){
            $querystatusString = "已取消的订单。";
        }
        return $querystatusString;
    }

    public static function translateCustomerOrderTabDesc($ordersStatus)
    {
        $querystatusString = "订单已经创建,请确认付款完毕并等待卖家接收。";
        if ($ordersStatus == 20) {
            $querystatusString = "卖家已接收的订单。您可以查看订单获得卖家联系方式。";
        } else if ($ordersStatus == 40) {
            $querystatusString = "等待您确认完成的订单。如果您在48小时内未处理, 易知海外将自动确认完成。";
        } else if ($ordersStatus == 60 || $ordersStatus == 80 || $ordersStatus == 100) {
            $querystatusString = "已完成的订单。";
        } else if ($ordersStatus == 70) {
            $querystatusString = "您认为有争议的订单。";
        } else if ($ordersStatus == 1020 || $ordersStatus == 1040 || $ordersStatus == 1060){
            $querystatusString = "已取消的订单。";
        }
        return $querystatusString;
    }

    public static function isOrderException($condition)
    {
        if ($condition == 70 || $condition == 1020 || $condition == 1040 || $condition == 1060) {
           return 0;
        } 
        return 1;
    }
    public static function isCanceledOrder($condition)
    {
        if ($condition == 1020 || $condition == 1040 || $condition == 1060) {
            return 1;
        }
        return 0;
    }
    // ===========  评论 ============= //
    public static function translateOrderFeeling($feel)
    {
        $levelDesc = '';
        switch ($feel)
        {
            case 1:
                $levelDesc = "很不满意";
                break;
            case 2:
                $levelDesc = "不满意";
                break;
            case 3:
                $levelDesc = "正常";
                break;
            case 4:
                $levelDesc = "满意";
                break;
            case 5:
                $levelDesc = "非常满意";
                break;
        }
        return $levelDesc;
    }
}