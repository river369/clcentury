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
            case 80:
                $orderStatus = "买家完成评论";
                break;
            case 100:
                $orderStatus = "易知已经完成付款,订单结束";
                break;

        }
        return $orderStatus;
    }

}