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
unset($_SESSION['signedUser']);

/*
$servicetype=1; // Tourism
$usersData=UsersModule::getUsersByServiceType($servicetype);

foreach($usersData as $key => $userData)
{
    echo $key.":".$userData['name']."--------";
}
*/
$userData=UsersModule::getUserByPhone('13520143438');
if (isset($userData['phone'])){
    echo "got".$userData['phone'];
} else {
    echo "not got";
}

$userData=UsersModule::getUserByPhone('13520143481');
if (isset($userData['phone'])){
    echo "got".$userData['phone'];
} else {
    echo "not got";
}

?>