<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UsersDao;
use Addons\OverSea\Common\EncryptHelper;
require dirname(__FILE__).'/../init.php';

//$day2=48*3600;
//// each client should remember their session id for EXACTLY 2 days
//session_set_cookie_params($day2);
//ini_set("session.cookie_lifetime",$day2);
//// server should keep session data for AT LEAST 2 days
//ini_set('session.gc_maxlifetime', $day2);

session_start();
$userData;
if (isset($_POST ['phonereigon'])){
    $userData['phonereigon'] = $_POST ['phonereigon'];
}
if (isset($_POST ['phonenumber'])){
    $userData['phonenumber'] = $_POST ['phonenumber'];
}
if (isset($_POST ['password'] )){
    $userData['password'] = $_POST ['password'];
}
if (isset($_SESSION['weixinOpenid'])) {
    $userData['openid'] = $_SESSION['weixinOpenid'];
}
if (isset($_SESSION['verifcationCode']) && isset($_POST['verifycode']) && $_SESSION['verifcationCode'] == isset($_POST['verifycode'])) {
    $existedUser=UsersDao::getUserByPhone($userData['phonereigon'] , $userData['phonenumber']);
    if (isset($existedUser['phonenumber'])){
        $_SESSION['existedUserPhoneReigon']= $existedUser['phonereigon'];
        $_SESSION['existedUserPhoneNumber']= $existedUser['phonenumber'];
        header('Location:../View/mobile/users/signin.php');
    } else {
        $id = UsersDao::insertUser($userData);
        if ($id>0) {
            $_SESSION['signupstatus'] = '成功';
            $_SESSION['signedUser'] = $id;
            // try to set uid in cookie
            $cookieValue = EncryptHelper::encrypt($existedUser['id']);
            setcookie("signedUser", $cookieValue, time()+7*24*3600);

        } else {
            $_SESSION['signupstatus'] = '失败';
        }
        $_SESSION['userData']= $userData;
        header('Location:../View/mobile/users/signupsuccess.php');
    }
} else {
    $_SESSION['signupstatus'] = '失败';
    $_SESSION['userData']= $userData;
    header('Location:../View/mobile/users/signupsuccess.php');
}


?>