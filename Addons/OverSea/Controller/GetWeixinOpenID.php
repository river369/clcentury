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
use Addons\OverSea\Model\UserAccountsDao;

session_start();
$_SESSION['weixinOpenidTried'] = 1;

$code = null;
if (isset($_GET['code'])){
    $code = $_GET['code'];
}else{
    echo "NO CODE";
}
Logs::writeClcLog( "GetWeixinOpenID, code=".$code);
$tokenArray = WeixinHelper::getAuthToken ($code);
$openid = $tokenArray['openid'];
Logs::writeClcLog( "GetWeixinOpenID, openId1=".$openid);
//$token = $tokenArray['refresh_token'];
//$refreshtoken = $tokenArray['refresh_token'];
//$user = WeixinHelper::getWeixinUserInfoWithRefresh($token, $openid, $refreshtoken);
//$_SESSION['weixinOpenid'] = $user['openid'];
$_SESSION['weixinOpenid'] = $openid;
if (isset($_SESSION['weixinOpenid'])) {
    $userDao = new UserAccountsDao();
    $userDao->updateExternalUserId($_SESSION['weixinOpenid'], $_SESSION['signedUser']);
}
header('Location:./AuthUserDispatcher.php');
?>