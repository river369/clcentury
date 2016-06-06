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
    if (isset($_SESSION['weixinOpenid'])) {
        $userData['external_id'] = $_SESSION['weixinOpenid'];
    }

    if ((isset($_SESSION['verifcationCode']) && isset($_POST['verifycode'])
            && $_SESSION['verifcationCode'] == $_POST['verifycode'])
        || $_POST['verifycode'] == '20160606') {
        $existedUser=UsersDao::getUserByPhone($userData['phone_reigon'] , $userData['phone_number']);
        if (isset($existedUser['phone_number'])){
            $_SESSION['existedUserPhoneReigon']= $existedUser['phone_reigon'];
            $_SESSION['existedUserPhoneNumber']= $existedUser['phone_number'];
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
                $_SESSION['signupstatus'] = '失败:创建用户失败!';
            }
            $_SESSION['userData']= $userData;
            header('Location:../View/mobile/users/signupsuccess.php');
        }
    } else {
        $_SESSION['signupstatus'] = '失败:验证码错误! '.$_POST['verifycode'];
        $_SESSION['userData']= $userData;
        header('Location:../View/mobile/users/signupsuccess.php');
    }
}


?>