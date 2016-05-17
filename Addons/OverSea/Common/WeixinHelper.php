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
        $user = WeixinHelper::getWeixinUserInfo($token, $openid);
        if ( @array_key_exists ( 'errmsg', $user ) ) {
            // Refresh here
            $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=' . self::$appid  .'&grant_type=refresh_token&refresh_token='.$refreshtoken;
            //echo $url;
            $tempArr = json_decode ( file_get_contents ( $url ), true );
            //echo " [error]=".$tempArr['errmsg'];
            $token = $tempArr['access_token'];
            $openid = $tempArr['openid'];
            // Reget the info
            return WeixinHelper::getWeixinUserInfo($token, $openid);
        } else {
            return $user;
        }
    }
    
    public static function triggerWeixinGetToken($token, $openid, $refreshtoken) {
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='. self::$appid  .'&redirect_uri=http://www.clcentury.com/weiphp/Addons/OverSea/Controller/GetWeixinOpenID.php?response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect';
            //echo $url;
            header('Location:'.$url);
    }



}


