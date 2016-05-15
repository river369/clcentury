<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UsersModule;
require dirname(__FILE__).'/../Model/UsersModule.php';

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
    if (UsersModule::insertUser($userData)>0) {
        $_SESSION['signupstatus'] = '成功';
    } else {
        $_SESSION['signupstatus'] = '失败';
    }
    $_SESSION['userData']= $userData;

    header('Location:../View/mobile/users/signupsuccess.php');
}
?>