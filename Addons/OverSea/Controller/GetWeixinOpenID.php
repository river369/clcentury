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
use Addons\OverSea\Common\MySqlHelper;
use Addons\OverSea\Model\UserAccountsDao;
use Addons\OverSea\Model\SellerPayAccountsDao;

session_start();
$_SESSION['weixinOpenidTried'] = 1;
$user_id = $_SESSION['signedUser'];
unset($_SESSION['signedUser']);
// Call weixin to get user info
$code = null;
if (isset($_GET['code'])){
    $code = $_GET['code'];
}else{
    echo "NO CODE";
}
Logs::writeClcLog( "GetWeixinOpenID, code=".$code);
$tokenArray = WeixinHelper::getAuthToken ($code);
$openid = $tokenArray['openid'];
Logs::writeClcLog( "GetWeixinOpenID, openId-1st=".$openid);
$token = $tokenArray['refresh_token'];
$refreshtoken = $tokenArray['refresh_token'];
$user = WeixinHelper::getWeixinUserInfoWithRefresh($token, $openid, $refreshtoken);
$openid = $user['openid'];
$nickname = $user['nickname'];
$_SESSION['weixinOpenid'] = $openid;
Logs::writeClcLog( "GetWeixinUserinfo, openId-2nd=".$openid);
Logs::writeClcLog( "GetWeixinUserinfo, nickname=".$nickname);

// Save user info to db
MySqlHelper::beginTransaction();
try {
    if (isset($_SESSION['weixinOpenid'])) {
        $userDao = new UserAccountsDao();
        $userDao->updateExternalUserId($_SESSION['weixinOpenid'], $user_id);
    }
    $sellerPayAccountsDao = new SellerPayAccountsDao();
    $sellerPayAccount = array();
    $sellerPayAccount['user_id'] = $user_id;
    $sellerPayAccount['nick_name'] = $nickname;
    $sellerPayAccount['account_type'] = 1;
    $sellerPayAccount['account_id'] = $openid;
    $sellerPayAccount['creation_date'] = date('y-m-d H:i:s', time());;
    $sellerPayAccount['update_date'] = date('y-m-d H:i:s', time());;
    $sellerPayAccountsDao->insertOrUpdateSellerPayAccount($sellerPayAccount);
    MySqlHelper::commit();
}  catch (\Exception $e){
    Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . $e);
    MySqlHelper::rollBack();
    $_SESSION['$signInErrorMsg']= " 系统错误,登录失败,请重试.";
    header('Location:../View/mobile/users/signin.php');
    exit;
}
$_SESSION['signedUser'] = $user_id;

if (isset($_SESSION['tempCode'])){
    $_SESSION['$signInErrorMsg'] = "请尽快修改密码";
    header('Location:../View/mobile/users/change_password.php');
} else {
    header('Location:./AuthUserDispatcher.php');
}

?>