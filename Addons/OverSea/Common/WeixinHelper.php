<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/4/29
 * Time: 21:51
 */

namespace Addons\OverSea\Common;

class WeixinHelper
{
    private static $appid ;
    private static $secret ;

    public static function initData($appid, $secret) {
        self::$appid = $appid;
        self::$secret = $secret;
    }


    /**
     * @return mixed
     */
    public static function getAppid()
    {
        return self::$appid;
    }

    /**
     * Get weixin token, the token is not same as the the auth 2.0
     * @return int
     */
    public static function getAccessToken() {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . self::$appid . '&secret=' . self::$secret ;
        $tempArr = json_decode ( file_get_contents ( $url ), true );
        if (@array_key_exists ( 'access_token', $tempArr )) {
            return $tempArr ['access_token'];
        } else {
            return 0;
        }
    }

    public static function getAccessTokenWithLocalFile() {
        $data = json_decode(file_get_contents("access_token.json"));
        if ($data->expire_time < time()) {
            $TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::$appid."&secret=".self::$secret;
            $json = file_get_contents($TOKEN_URL);
            $result = json_decode($json, true);
            $access_token = $result['access_token'];
            if ($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $fp = fopen("access_token.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
                return $access_token;
            } else {
                return 0;
            }
        }else{
            return $data->access_token;
        }
    }

    /**
     * Get the auth 2.0 token, the token is not same as the weixin token
     * @param $code
     * @return mixed
     */
    public static function getAuthToken($code) {
        $codeUrl ='';
        if (! empty ( $code )) {
            $codeUrl = '&code='.$code.'&grant_type=authorization_code';
        }
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . self::$appid . '&secret=' . self::$secret.$codeUrl ;
        //echo $url;
        $tempArr = json_decode ( file_get_contents ( $url ), true );
        return $tempArr;
    }

    /**
     * Get wei xin user info
     * @param $token
     * @param $openid
     * @return int|mixed
     */
    public static function getWeixinUserInfo($token, $openid) {
        if (empty ( $token ) || empty ( $openid )) {
            return 0;
        }
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';
        //echo $url;
        $tempArr = json_decode ( file_get_contents ( $url ), true );
        return $tempArr;
    }

    /**
     * Sometime token expired, need to refresh before call user info
     * @param $token
     * @param $openid
     * @param $refreshtoken
     * @return int|mixed
     */
    public static function getWeixinUserInfoWithRefresh($token, $openid, $refreshtoken) {
        // Try to get user first
        $user = self::getWeixinUserInfo($token, $openid);
        if ( @array_key_exists ( 'errmsg', $user ) ) {
            // Refresh here
            $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=' . self::$appid  .'&grant_type=refresh_token&refresh_token='.$refreshtoken;
            //echo $url;
            $tempArr = json_decode ( file_get_contents ( $url ), true );
            //echo " [error]=".$tempArr['errmsg'];
            $token = $tempArr['access_token'];
            $openid = $tempArr['openid'];
            // Reget the info
            return self::getWeixinUserInfo($token, $openid);
        } else {
            return $user;
        }
    }
    
    public static function triggerWeixinGetToken($token, $openid, $refreshtoken) {
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='. self::$appid  .'&redirect_uri=http://www.clcentury.com/weiphp/Addons/OverSea/Controller/GetWeixinOpenID.php?response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
            //echo $url;
            header('Location:'.$url);
    }


    //======The following part is for upload pictures======
    /*
     * something like : nonceStr: '3YbIykt3eW4wXnyY',
     */
    public static function make_nonceStr()
    {
        $codeSet = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0; $i<16; $i++) {
            $codes[$i] = $codeSet[mt_rand(0, strlen($codeSet)-1)];
        }
        $nonceStr = implode($codes);
        return $nonceStr;
    }

    /*
     * something like : 
     */
    public static function make_ticket()
    {
        $access_token = self::getAccessTokenWithLocalFile();

        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode(file_get_contents("jsapi_ticket.json"));
        if ($data->expire_time < time()) {
            $ticket_URL="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi";
            $json = file_get_contents($ticket_URL);
            $result = json_decode($json,true);
            $ticket = $result['ticket'];
            if ($ticket) {
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $fp = fopen("jsapi_ticket.json", "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        }else{
            $ticket = $data->jsapi_ticket;
        }
        return $ticket;
    }
    
    
    /*
     * something like : signature: '299238dc9ab411dc7ea8da4f967ddc8654b3bbfe',
     */
    public static function make_signature($nonceStr, $timestamp, $jsapi_ticket, $url)
    {
        $tmpArr = array(
            'noncestr' => $nonceStr,
            'timestamp' => $timestamp,
            'jsapi_ticket' => $jsapi_ticket,
            'url' => $url
        );
        ksort($tmpArr, SORT_STRING);
        $string1 = http_build_query( $tmpArr );
        $string1 = urldecode( $string1 );
        $signature = sha1( $string1 );
        return $signature;
    }

}


