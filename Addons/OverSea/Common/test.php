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
foreach($usersData as $key => $userData)
{
    echo $key.":".$userData[$key]."--------";
}

?>