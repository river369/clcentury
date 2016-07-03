<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/30
 * Time: 22:57
 */
namespace Addons\OverSea\Common;

class BusinessHelper {
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

    public static function translateOrderStatus($staus)
    {
        $orderStatus = '';
        switch ($staus)
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
            case 60:
                $orderStatus = "买家已将订单置为完成,等待易知付款";
                break;
            case 70:
                $orderStatus = "买家提出服务争议";
                break;
            case 80:
                $orderStatus = "买家完成评论";
                break;
            case 100:
                $orderStatus = "易知已经完成付款,订单结束";
                break;

        }
        return $orderStatus;
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
            $querystatusString = "易知海外已经完成支付的订单。";
        } else if ($ordersStatus == 1020 || $ordersStatus == 1040 || $ordersStatus == 1060){
            $querystatusString = "已取消的订单。";
        }
        return $querystatusString;
    }

    public static function translateCustomerOrderTabDesc($ordersStatus)
    {
        $querystatusString = "订单已经创建,请确认付款完毕并等待卖家接收。";
        if ($ordersStatus == 20) {
            $querystatusString = "卖家已接收的订单。你可以查看订单获得卖家联系方式。";
        } else if ($ordersStatus == 40) {
            $querystatusString = "等待您确认完成的订单。如果你在48小时内未处理, 易知海外将自动确认完成。";
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
    public static function translateOrderFeeling($feel)
    {
        $levelDesc = '';
        switch ($feel)
        {
            case 1:
                $levelDesc = "非常不满意";
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