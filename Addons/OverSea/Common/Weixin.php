<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/4/29
 * Time: 21:51
 */

// Need to securate them
$appid = 'wx3266dc2dad415085';
$secret = '99b8ed61fe784a419d2960dc0c2d2cdb';

/**
 * Get weixin token, the token is not same as the the auth 2.0
 * @return int
 */
function getAccessToken() {
    global $appid;
    global $secret;
    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appid . '&secret=' . $secret ;
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
function getAuthToken($code) {
    global $appid;
    global $secret;
    $codeUrl ='';
    if (! empty ( $code )) {
        $codeUrl = '&code='.$code.'&grant_type=authorization_code';
    }
    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . $secret.$codeUrl ;
    $tempArr = json_decode ( file_get_contents ( $url ), true );
    return $tempArr;
}

/**
 * Get wei xin user info
 * @param $token
 * @param $openid
 * @return int|mixed
 */
function getWeixinUserInfo($token, $openid) {
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
function getWeixinUserInfoWithRefresh($token, $openid, $refreshtoken) {
    global $appid;
    // Try to get user first
    $user = getWeixinUserInfo($token, $openid);
    if ( @array_key_exists ( 'errmsg', $user ) ) {
        // Refresh here
        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=' . $appid  .'&grant_type=refresh_token&refresh_token='.$refreshtoken;
        //echo $url;
        $tempArr = json_decode ( file_get_contents ( $url ), true );
        //echo " [error]=".$tempArr['errmsg'];
        $token = $tempArr['access_token'];
        $openid = $tempArr['openid'];
        // Reget the info
        return getWeixinUserInfo($token, $openid);
    } else {
        return $user;
    }
}
