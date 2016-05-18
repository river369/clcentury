<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UsersModule;
use Addons\OverSea\Common\WeixinHelper;

require dirname(__FILE__) . '/../Model/UsersModule.php';

session_start();

$method_routes = array(
    'signin' => array('l'=>'../View/mobile/users/signin.php','c'=>''),//submit yz
    'submityzpic' => array('l'=>'../View/mobile/users/UploadPicture.php','c'=>'发易知图片'),
    'submityz' => array('l'=>'../View/mobile/users/submityz.html','c'=>'发易知信息')//submit yz
);

$whereToGo;
if (isset($_GET ['f'])){
    // get call back url from GET
    $whereToGo = $_GET ['f'];
    $_SESSION['callbackurl']= $whereToGo;
} else if (isset($_SESSION['callbackurl'])){
    // get call back url from SESSION
    $whereToGo = $_SESSION['callbackurl'];
}

if (isset($_SESSION['signedUser'])) {
    // first choice is session
    header('Location:'.$method_routes[$whereToGo]['l']);
} else {
    if (isset($_SESSION['weixinOpenid'])) {
        // check if weixin openid match the db saving values
        $existedUser=UsersModule::getUserByOpenid($_SESSION['weixinOpenid']);
        if (isset($existedUser['openid']) && $existedUser['openid'] == $_SESSION['weixinOpenid']){
            $_SESSION['signedUser'] = $existedUser['id'];
            header('Location:'.$method_routes[$whereToGo]['l']);
        } else {
            needSignin($method_routes, $whereToGo);
        }
        /**/
    } else if (!isset($_SESSION['weixinOpenidTried'])) {
        // Try to get weixin open id 1 times
        WeixinHelper::triggerWeixinGetToken();
    } else {
        needSignin($method_routes, $whereToGo);
    }

}

/**
 * redirect to sign in pages
 * @param $method_routes
 * @param $whereToGo
 */
function needSignin($method_routes, $whereToGo) {
    $_SESSION['$signInErrorMsg']= "请先登陆,然后可以".$method_routes[$whereToGo]['c'];
    header('Location:../View/mobile/users/signin.php');
}
?>