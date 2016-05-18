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
$serviceTypeString = 'servicetype';
$serviceType = isset($_GET [$serviceTypeString])? $_GET [$serviceTypeString] : 1;
$usersData=UsersDao::getUsersByServiceType($serviceType);
$_SESSION['servicetype'] = $serviceType;
$_SESSION['usersData']= $usersData;
header('Location:../View/mobile/query/discover.php');
?>