<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UsersDao;
use Addons\OverSea\Common\OSSHelper;
require dirname(__FILE__).'/../init.php';

session_start();

$userid = $_GET ['userid'];
$userData = UsersDao::getUserById($userid);
$_SESSION['userData']= $userData;

// list data
$object = "yzphoto/pics/".$userid."/";
//echo $object;
$objectList = OSSHelper::listObjects($object);
$objArray = array();
if (!empty($objectList)) {
    foreach ($objectList as $objectInfo) {
        $objArray[] = $objectInfo->getKey();
    }

    $_SESSION['objArray'.$userid] = $objArray;
}

header('Location:../View/mobile/users/userdetails.php');

?>