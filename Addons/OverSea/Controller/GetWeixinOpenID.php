<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/4/29
 * Time: 20:34
 */
require dirname(__FILE__).'/../init.php';
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Common\WeixinHelper;

session_start();
$_SESSION['weixinOpenidTried'] = 1;

$code = null;
if (isset($_GET['code'])){
    $code = $_GET['code'];
}else{
    echo "NO CODE";
}

$tokenArray = WeixinHelper::getAuthToken ($code);
$token = $tokenArray['refresh_token'];
$openid = $tokenArray['openid'];
$refreshtoken = $tokenArray['refresh_token'];
Logs::writeClcLog( "GetWeixinOpenID, code=".$code);
$user = WeixinHelper::getWeixinUserInfoWithRefresh($token, $openid, $refreshtoken);
$_SESSION['weixinOpenid'] = $user['openid'];
//echo $user['openid'];
header('Location:./AuthUserDispatcher.php');

?>