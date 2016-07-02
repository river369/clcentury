<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UserAccountsDao;
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
    $userData['user_id'] = uniqid().mt_rand(10, 99);

    if ((isset($_SESSION['verifcationCode']) && isset($_POST['verifycode'])
            && $_SESSION['verifcationCode'] == $_POST['verifycode'])
        || $_POST['verifycode'] == '20160606') {
        $userDao = new UserAccountsDao();
        $existedUser = $userDao->getUserByPhone($userData['phone_reigon'] , $userData['phone_number']);
        if (isset($existedUser['phone_number'])){
            $_SESSION['existedUserPhoneReigon']= $existedUser['phone_reigon'];
            $_SESSION['existedUserPhoneNumber']= $existedUser['phone_number'];
            header('Location:../View/mobile/users/signin.php');
        } else {
            $id = $userDao->insert($userData);
            if ($id>0) {
                $_SESSION['status'] = 's';
                $_SESSION['message'] = $userData['phone_reigon'] . $userData['phone_number'].'注册成功,谢谢!';
                $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php";
                $_SESSION['signedUser'] = $userData['user_id'];
                // try to set uid in cookie
                $cookieValue = EncryptHelper::encrypt($userData['user_id']);
                setcookie("signedUser", $cookieValue, time()+7*24*3600);
                header('Location:../View/mobile/common/message.php');
            } else {
               // $_SESSION['signupstatus'] = '1失败:创建用户失败!';
                $_SESSION['existedUserPhoneReigon']= $userData['phone_reigon'];
                $_SESSION['existedUserPhoneNumber']= $userData['phone_number'];
                $_SESSION['$signInErrorMsg'] = '创建用户失败! 请重试!';
                header('Location:../View/mobile/users/signup.php');
            }
        }
    } else {
        //$_SESSION['signupstatus'] = '失败:验证码错误! '.$_POST['verifycode'];
        //$_SESSION['userData']= $userData;
        //header('Location:../View/mobile/users/signupsuccess.php');
        $_SESSION['existedUserPhoneReigon']= $userData['phone_reigon'];
        $_SESSION['existedUserPhoneNumber']= $userData['phone_number'];
        $_SESSION['$signInErrorMsg'] = '验证码错误! '.$_POST['verifycode'];;
        header('Location:../View/mobile/users/signup.php');
    }
}


?>