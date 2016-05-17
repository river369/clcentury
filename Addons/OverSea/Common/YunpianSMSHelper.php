<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/4/29
 * Time: 21:51
 */

namespace Addons\OverSea\Common;

header("Content-Type:text/html;charset=utf-8");

class YunpianSMSHelper
{
    private static $appid;
    private static $secret;

    public static function initData($appid, $secret)
    {
        self::$appid = $appid;
        self::$secret = $secret;
    }

    public static function sendSMS($text, $mobile) {

        $ch = curl_init();

        /* 设置验证方式 */

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded','charset=utf-8'));

        /* 设置返回结果为流 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* 设置超时时间*/
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        /* 设置通信方式 */
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // 发送短信
        $data=array('text'=>$text,'apikey'=>self::$appid,'mobile'=>$mobile);
        $json_data = self::send($ch,$data);
        $array = json_decode($json_data,true);
        //echo '<pre>';print_r($array);

        curl_close($ch);
        return $array;
    }

    static function send($ch,$data){
        curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        return curl_exec($ch);
    }


}

?>