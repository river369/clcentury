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

if (isset($_POST ['name'] )){
    $userData['name'] = $_POST ['name'];
}
if (isset($_POST ['weixin'] )){
    $userData['weixin'] = $_POST ['weixin'];
}
if (isset($_POST ['servicearea'] )){
    $userData['servicearea'] = $_POST ['servicearea'];
}
if (isset($_POST ['description'] )){
    $userData['description'] = $_POST ['description'];
}
$i=0;
if (isset($_POST ['service-1']) &&  $_POST ['service-1'] == 'on'){
    $userData['servicetype'] = 1;
    $i++;
}
if (isset($_POST ['service-2']) &&  $_POST ['service-2'] == 'on'){
    $userData['servicetype'] = 2;
    $i++;
}
if ($i == 2 ){
    $userData['servicetype'] = 0;
}

if (UsersModule::insertUser($userData)>0) {
    $_SESSION['submityzstatus'] = '成功';
    $_SESSION['userdata']= $userData;
} else {
    $_SESSION['submityzstatus'] = '失败';
}

header('Location:../View/mobile/users/submityzsuccess.php');
//echo UsersModule::getUser();



?>