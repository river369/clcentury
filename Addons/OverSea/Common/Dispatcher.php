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

$method_routes = array(
    'signin' => array('l'=>'../View/mobile/users/signin.php','c'=>''),//submit yz
    'submityzpic' => array('l'=>'../View/mobile/users/UploadPicture.php','c'=>'发易知图片'),
    'submityz' => array('l'=>'../View/mobile/users/submityz.html','c'=>'发易知信息')//submit yz
);

$whereToGo;
if (isset($_GET ['f'])){
    $whereToGo = $_GET ['f'];
} else if (isset($_SESSION['callbackurl'])){
    $whereToGo = $_SESSION['callbackurl'];
}

if (!isset($_SESSION['signedUser'])){
    $_SESSION['$signInErrorMsg']= "请先登陆,然后可以".$method_routes[$whereToGo]['c'];
    $_SESSION['callbackurl']= $whereToGo;
    header('Location:../View/mobile/users/signin.php');
} else {
    header('Location:'.$method_routes[$whereToGo]['l']);
}
?>