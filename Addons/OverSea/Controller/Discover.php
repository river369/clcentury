<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UsersDao;
require dirname(__FILE__).'/../init.php';

session_start();
$servicearea = '';
if (isset($_SESSION ['servicearea'])){
    $city = $_SESSION ['servicearea'];
}
if (isset($_GET ['servicearea'])){
    $servicearea = $_GET ['servicearea'];
    $_SESSION ['servicearea'] = $servicearea;
}

$serviceTypeString = 'servicetype';
$serviceType = isset($_GET [$serviceTypeString])? $_GET [$serviceTypeString] : 1;
$usersData;
if (isset($servicearea) && !empty($servicearea) && !is_null($servicearea) && $servicearea != '地球'){
    $usersData=UsersDao::getUsersByServiceTypeInArea($serviceType, $servicearea);
} else {
    $usersData=UsersDao::getUsersByServiceType($serviceType);
}
$_SESSION['servicetype'] = $serviceType;
$_SESSION['usersData']= $usersData;
header('Location:../View/mobile/query/discover.php');
?>