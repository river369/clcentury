<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UsersModule;
require dirname(__FILE__).'/../Model/UsersModule.php';

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
// verifycode to be implement

$existedUser=UsersModule::getUserByPhone($userData['phonereigon'] , $userData['phonenumber']);
if (isset($existedUser['phonenumber'])){
    $_SESSION['existedUserPhoneReigon']= $existedUser['phonereigon'];
    $_SESSION['existedUserPhoneNumber']= $existedUser['phonenumber'];
    header('Location:../View/mobile/users/signin.php');
} else {
    $id = UsersModule::insertUser($userData);
    if ($id>0) {
        $_SESSION['signupstatus'] = '成功';
        $_SESSION['signedUser'] = $id;
    } else {
        $_SESSION['signupstatus'] = '失败';
    }
    $_SESSION['userData']= $userData;
    header('Location:../View/mobile/users/signupsuccess.php');
}
?>