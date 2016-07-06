<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/6/6
 * Time: 08:12
 */


namespace Addons\OverSea\Common;
use Addons\OverSea\Common\Logs;

class HttpHelper {
    public static function saveServerQueryStringVales($text) {
        if (isset($text) && !is_null($text) && (strlen($text)>0)){
            $output = array();
            parse_str($text, $output);
            $_SESSION['QUERY_STRING_ARRAY'] = $output;
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",QUERY_STRING_ARRAY=".$text);
        }
    }

    public static function getVale($key) {
        $output = $_SESSION['QUERY_STRING_ARRAY'];
        if (isset($output [$key])){
            return $output [$key];
        } else {
           return null;
        }
    }

    /**
     * 发送post请求
     * @param string $url
     * @param string $param
     * @return bool|mixed
     */
    public static function request_post($url = '', $param = '')
    {
        if (empty($url) || empty($param)) {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch); //运行curl
        curl_close($ch);
        return $data;
    }
}