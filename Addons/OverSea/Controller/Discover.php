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
$servicetype=1; // Tourism
$usersData=UsersModule::getUsersByServiceType($servicetype);
echo count($usersData);
$_SESSION['usersData']= $usersData;
header('Location:../View/mobile/query/discover.php');
?>