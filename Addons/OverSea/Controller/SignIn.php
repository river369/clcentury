<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UserAccountsDao;
use Addons\OverSea\Common\EncryptHelper;
use Addons\OverSea\Common\WeixinHelper;
use Addons\OverSea\Common\Logs;
require dirname(__FILE__).'/../init.php';

session_start();
$userData;
$userData['user_type'] = 1;

if ($userData['user_type'] == 1) { // register by phone user
    if (isset($_POST ['phone_reigon'])){
        $userData['phone_reigon'] = $_POST ['phone_reigon'];
    }
    if (isset($_POST ['phone_number'])){
        $userData['phone_number'] = $_POST ['phone_number'];
    }
    if (isset($_POST ['password'] )){
        $userData['password'] = $_POST ['password'];
    }

    // verifycode to be implement
    $userDao = new UserAccountsDao();
    $existedUser = $userDao->getUserByPhone($userData['phone_reigon'] , $userData['phone_number']);
    if (!isset($existedUser['phone_number'])){
        //echo $userData['phone_reigon'] . $userData['phone_number']. " 号码尚未注册.";
        $_SESSION['$signInErrorMsg']= $userData['phone_reigon'] . $userData['phone_number']. " 号码尚未注册.";
        header('Location:../View/mobile/users/signin.php');
    } else if ($_POST ['password'] != $existedUser['password']){
        $_SESSION['$signInErrorMsg']= $userData['phone_reigon'] . $userData['phone_number']. " 密码错误.";
        header('Location:../View/mobile/users/signin.php');
    } else {
        $_SESSION['signedUser'] = $existedUser['user_id'];
        // try to set uid in cookie
        $cookieValue = EncryptHelper::encrypt($existedUser['user_id']);
        setcookie("signedUser", $cookieValue, time()+7*24*3600);
        
        Logs::writeClcLog("Signin.php,try to call weixin to verify");
        WeixinHelper::triggerWeixinGetToken();
    }
}

?>