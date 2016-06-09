<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UsersDao;
require dirname(__FILE__) . '/../init.php';

session_start();
$userData;

if (isset($_POST ['name'])){
    $userData['name'] = $_POST ['name'];
}
if (isset($_POST ['weixin'] )){
    $userData['weixin'] = $_POST ['weixin'];
}
if (isset($_POST ['servicearea'] )){
    $userData['servicearea'] = $_POST ['servicearea'];
}
if (isset($_POST ['description'] )){
    $userData['description'] = ltrim($_POST ['description']);
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
    $userData['servicetype'] = 99999;
}
if (isset($_POST ['serviceprice'] )){
    $userData['serviceprice'] = $_POST ['serviceprice'];
}

if (isset( $_POST ['mytags'])){
    $userData['tag'] = $_POST ['mytags'];
}
if (UsersDao::updateUser($userData,$_SESSION['signedUser'])==0) {
    $_SESSION['submityzstatus'] = '成功';
} else {
    $_SESSION['submityzstatus'] = '失败';
}
$_SESSION['userData']= $userData;

header('Location:../View/mobile/users/submityzsuccess.php');

?>