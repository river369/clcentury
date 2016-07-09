<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UserInfosDao;
use Addons\OverSea\Model\UserAccountsDao;

require dirname(__FILE__).'/../init.php';

session_start();

echo '  [session_weixinOpenid='.$_SESSION['weixinOpenid'];
echo '  ] [session_weixinOpenidTried='.$_SESSION['weixinOpenidTried'];
echo '  ] [id='.$_SESSION['signedUser'];
$userInfosDao = new UserInfosDao();
$userData=$userInfosDao->getByKv('user_id', $_SESSION['signedUser']);
if (isset($userData['phone_number'])){
    echo "] [got phone by id=".$userData['phone_number'];
} else {
    echo "] [not got by id";
}

$userAccountDao = new UserAccountsDao();
$userData=$userAccountDao->getUserByExternalId($_SESSION['weixinOpenid']);
if (isset($userData['phone_number'])){
    echo "] [got phone by weixinOpenid=".$userData['phone_number'];
} else {
    echo "] [not got by weixinOpenid]";
}
echo "[".$_SESSION['signedUser']."]";
//$userAccountDao->updateExternalUserId(-1, $_SESSION['signedUser']);
setcookie("signedUser", "", time()-1000);

unset($_SESSION['signedUser'],$_SESSION['userSetting']);
unset($_SESSION['weixinOpenid']);
unset($_SESSION['weixinOpenidTried']);
unset($_SESSION['$timestamp'], $_SESSION['$nonceStr'], $_SESSION['$signature']);
unset($_SESSION['serviceData'], $_SESSION['sellerData'], $_SESSION['commentsData'], $_SESSION['myServices']);
unset($_SESSION['objArray']);

//$_SESSION['status'] = 's';
//$_SESSION['message'] = "成功退出!";
//$_SESSION['goto'] = "../../../Controller/FreelookDispatcher.php?c=index";
//header('Location:'."../View/mobile/common/message.php");
header('Location:'."./FreelookDispatcher.php?c=index");
exit;
?>