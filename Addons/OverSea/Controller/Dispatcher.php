<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UsersDao;
use Addons\OverSea\Common\WeixinHelper;

require dirname(__FILE__).'/../init.php';

session_start();

//c - command, like signin, m - model, f - function in model, v - view,  d - description
$method_routes = array(
    'signin' => array('v'=>'../View/mobile/users/signin.php','d'=>''),//submit yz
    'submityzpic' => array('v'=>'../View/mobile/users/UploadPicture.php','d'=>'发易知图片'),
    'submityz' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'getCurrentUserInfo', 'v'=>'../View/mobile/users/submityz.html','d'=>'发易知信息')//submit yz
);

$command;
if (isset($_GET ['c'])){
    // get call back url from GET
    $command = $_GET ['c'];
    $_SESSION['callbackurl']= $command;
} else if (isset($_SESSION['callbackurl'])){
    // get call back url from SESSION
    $command = $_SESSION['callbackurl'];
}
echo $_SESSION['signedUser'];
if (isset($_SESSION['signedUser'])) {
    // first choice is session
    if (isset($method_routes[$command]['m']) && isset($method_routes[$command]['f'])){
        try {
            $class = $method_routes[$command]['m'];
            $fun = $method_routes[$command]['f'];
            $class = new $class();
            call_user_func(array($class, $fun));
        } catch (Exception $e) {
            echo $e->getTrace();
        }
    }
    header('Location:'.$method_routes[$command]['v']);
} else {
    if (isset($_SESSION['weixinOpenid'])) {
        // check if weixin openid match the db saving values
        $existedUser=UsersDao::getUserByOpenid($_SESSION['weixinOpenid']);
        if (isset($existedUser['openid']) && $existedUser['openid'] == $_SESSION['weixinOpenid']){
            $_SESSION['signedUser'] = $existedUser['id'];
            header('Location:'.$method_routes[$command]['v']);
        } else {
            needSignin($method_routes, $command);
        }
        /**/
    } else if (!isset($_SESSION['weixinOpenidTried'])) {
        // Try to get weixin open id 1 times
        WeixinHelper::triggerWeixinGetToken();
    } else {
        needSignin($method_routes, $command);
    }

}

/**
 * redirect to sign in pages
 * @param $method_routes
 * @param $command
 */
function needSignin($method_routes, $command) {
    $_SESSION['$signInErrorMsg']= "请先登陆,然后可以".$method_routes[$command]['d'];
    header('Location:../View/mobile/users/signin.php');
}
?>