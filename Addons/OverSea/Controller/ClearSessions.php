<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UsersDao;
require dirname(__FILE__) . '/../Model/UsersDao.php';

session_start();
/*
$servicetype=1; // Tourism
$usersData=UsersDao::getUsersByServiceType($servicetype);

foreach($usersData as $key => $userData)
{
    echo $key.":".$userData['name']."--------";
}
*/

echo '  [session_weixinOpenid='.$_SESSION['weixinOpenid'];
echo '  ] [session_weixinOpenidTried='.$_SESSION['weixinOpenidTried'];
echo '  ] [id='.$_SESSION['signedUser'];

//$userData=UsersDao::getUserByPhone('+86','13520143438');
$userData=UsersDao::getUserById($_SESSION['signedUser']);
if (isset($userData['phonenumber'])){
    echo "] [got phone by id=".$userData['phonenumber'];
} else {
    echo "] [not got by id";
}

$userData=UsersDao::getUserByOpenid($_SESSION['weixinOpenid']);
if (isset($userData['phonenumber'])){
    echo "] [got phone by weixinOpenid=".$userData['phonenumber'];
} else {
    echo "] [not got by weixinOpenid]";
}

UsersDao::updateOpenid(-1, $_SESSION['signedUser']);
unset($_SESSION['signedUser']);
unset($_SESSION['weixinOpenid']);
unset($_SESSION['weixinOpenidTried']);



?>