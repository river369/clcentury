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
    'mine' => array('v'=>'../View/mobile/users/mine.php','d'=>'我的订单'),
    'submityzpic' => array('m'=>'Addons\OverSea\Model\YZPicBo', 'f'=>'handlePics', 'v'=>'../View/mobile/users/yzpictures.php','d'=>'发易知图片'),
    'submityz' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'getCurrentUserInfo', 'v'=>'../View/mobile/users/submityz.php','d'=>'发易知信息'),
    'submitOrder' => array('m'=>'Addons\OverSea\Model\SellersBo', 'f'=>'getCurrentSellerInfo', 'v'=>'../View/mobile/orders/submitorder.php','d'=>'用户订购确认'),
    'createOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'createOrder', 'v'=>'../View/mobile/orders/submitorderstatus.php','d'=>'创建订单'),
    
    'queryCustomerOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'getOrderByCustomerAndCondition', 'v'=>'../View/mobile/orders/customerorderlist.php', 'd'=>'查看买家订单'),
    'querySellerOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'getOrderBySellerAndCondition', 'v'=>'../View/mobile/orders/sellerorderlist.php', 'd'=>'查看卖家订单'),
    
    'sellerRejectOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'sellerRejectOrder', 'v'=>'./AuthUserDispatcher.php?c=querySellerOrder&condition=102,104,106', 'd'=>'拒绝订单'),
    'sellerAcceptOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'sellerAcceptOrder', 'v'=>'./AuthUserDispatcher.php?c=querySellerOrder&condition=2', 'd'=>'接受订单'),
    'sellerCancelOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'sellerCancelOrder', 'v'=>'./AuthUserDispatcher.php?c=querySellerOrder&condition=102,104,106', 'd'=>'取消订单'),
    'sellerFinishOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'sellerFinishOrder', 'v'=>'./AuthUserDispatcher.php?c=querySellerOrder&condition=4', 'd'=>'卖家完成订单'),
    'customerConfirmOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'customerConfirmOrder', 'v'=>'./AuthUserDispatcher.php?c=queryCustomerOrder&condition=6', 'd'=>'买家完成订单'),
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

if (isset($_SESSION['signedUser'])) {
    // first choice is session
    goToCommand($method_routes, $command);
} else {
    if (isset($_SESSION['weixinOpenid'])) {
        // check if weixin openid match the db saving values
        $existedUser=UsersDao::getUserByOpenid($_SESSION['weixinOpenid']);
        if (isset($existedUser['openid']) && $existedUser['openid'] == $_SESSION['weixinOpenid']){
            $_SESSION['signedUser'] = $existedUser['id'];
            goToCommand($method_routes, $command);
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

function goToCommand($method_routes, $command) {
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
}
?>