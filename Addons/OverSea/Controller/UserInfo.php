<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UsersModule;
require dirname(__FILE__).'/../Model/UsersModule.php';

//
//function setAttributes($userData, $postData, $key)
//{
//    if (isset($postData [$key])){
//        $userData[$key] = $postData [$key];
//    }
//}

//$userData[] = array();
//setAttributes($userData, $_POST, 'name');
//setAttributes($userData, $_POST, 'openid');
//setAttributes($userData, $_POST, 'gender');
//setAttributes($userData, $_POST, 'phone');
//setAttributes($userData, $_POST, 'description');

//$userData[] = array();
$userData['gender'] = $_POST ['gender'];
$userData['name'] = $_POST ['name'];
//setAttributes('name');
//setAttributes('openid');
//setAttributes('gender');
//setAttributes('phone');
//setAttributes('description');
//
//function setAttributes($key)
//{
//    if (isset($_POST[$key])){
//        $userData[$key] = $_POST [$key];
//        echo $_POST[$key]. "   ".$userData[$key];
//    }
//}
//echo  "   ".$userData['gender'];

//echo UsersModule::insertUser($userData);
echo UsersModule::getUser();
?>