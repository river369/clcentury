<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UsersDao;
use Addons\OverSea\Common\WeixinHelper;
use Addons\OverSea\Common\EncryptHelper;
use Addons\OverSea\Common\HttpHelper;
use Addons\OverSea\Common\Logs;

require dirname(__FILE__).'/../init.php';

session_start();

//c - command, like signin, m - model, f - function in model, v - view,  d - description
$method_routes = array(
    'signin' => array('v'=>'../View/mobile/users/signin.php','d'=>''),//submit yz
    'mine' => array('v'=>'../View/mobile/users/mine.php','d'=>'我的订单'),
    'submityzpic' => array('m'=>'Addons\OverSea\Model\YZPicBo', 'f'=>'handlePics', 'v'=>'../View/mobile/users/yzpictures.php','d'=>'发易知图片'),
    'submityz' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'getCurrentUserInfo', 'v'=>'../View/mobile/users/submityz.php','d'=>'发易知信息'),
    'submitheadpic' => array('m'=>'Addons\OverSea\Model\YZPicBo', 'f'=>'handleHeads', 'd'=>'发个人头像'),

    'submitOrder' => array('m'=>'Addons\OverSea\Model\SellersBo', 'f'=>'getCurrentSellerInfo', 
        'v'=>'../View/mobile/orders/submitorder.php','d'=>'用户订购确认'),
    'createOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'createOrder', 
        'v'=>'../View/mobile/orders/submitorderstatus.php','d'=>'创建订单'),
    'repayOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'repayOrder',
        'v'=>'../View/mobile/orders/submitorderstatus.php','d'=>'支付订单'),

    'queryCustomerOrders' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'getOrderByCustomerAndCondition',
        'v'=>'../View/mobile/orders/customerorderlist.php', 'd'=>'查看买家订单'),
    'querySellerOrders' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'getOrderBySellerAndCondition', 
        'v'=>'../View/mobile/orders/sellerorderlist.php', 'd'=>'查看卖家订单'),
    
    'sellerRejectOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'sellerRejectOrder', 
        'v'=>'./AuthUserDispatcher.php?c=querySellerOrders&condition=1020,1040,1060', 'd'=>'卖家拒绝订单'),
    'sellerAcceptOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'sellerAcceptOrder', 
        'v'=>'./AuthUserDispatcher.php?c=querySellerOrders&condition=20', 'd'=>'卖家接受订单'),
    'sellerCancelOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'sellerCancelOrder', 
        'v'=>'./AuthUserDispatcher.php?c=querySellerOrders&condition=1020,1040,1060', 'd'=>'卖家取消订单'),
    'sellerFinishOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'sellerFinishOrder', 
        'v'=>'./AuthUserDispatcher.php?c=querySellerOrders&condition=40', 'd'=>'卖家完成订单'),
    'customerConfirmOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'customerConfirmOrder', 
        'v'=>'./AuthUserDispatcher.php?c=queryCustomerOrders&condition=60', 'd'=>'买家完成订单'),
    'customerCancelOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'customerCancelOrder',
        'v'=>'./AuthUserDispatcher.php?c=queryCustomerOrders&condition=1020,1040,1060', 'd'=>'买家取消订单'),

    'queryOrderDetails' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'getOrderDetailsById',
        'v'=>'../View/mobile/orders/orderdetails.php', 'd'=>'查看订单详情'),

);

HttpHelper::saveServerQueryStringVales($_SERVER['QUERY_STRING']);
$command = HttpHelper::getVale('c');
/*
$command;
if (isset($_GET ['c'])){
    // get call back url from GET
    $command = $_GET ['c'];
    $_SESSION['callbackurl']= $command;
} else if (isset($_SESSION['callbackurl'])){
    // get call back url from SESSION
    $command = $_SESSION['callbackurl'];
}*/


if (isset($_SESSION['signedUser'])) {
    // first choice is session
    goToCommand($method_routes, $command);
} else {
    $cookieValue = isset($_COOKIE["signedUser"])? EncryptHelper::decrypt($_COOKIE["signedUser"]) : "";
    $cookieValue = null;
    if (isset($cookieValue) && !empty($cookieValue) && !is_null($cookieValue)){
        saveId($cookieValue);
        goToCommand($method_routes, $command);
    } else if (isset($_SESSION['weixinOpenid'])) {
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":"."Got weixin openid=");
        // check if weixin openid match the db saving values
        $existedUser=UsersDao::getUserByOpenid($_SESSION['weixinOpenid']);
        if (isset($existedUser['openid']) && $existedUser['openid'] == $_SESSION['weixinOpenid']){
            saveId($existedUser['id']);
            goToCommand($method_routes, $command);
        } else {
            needSignin($method_routes, $command);
        }
        /**/
    } else if (!isset($_SESSION['weixinOpenidTried'])) {
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":"."try to call weixin to verify");
        // Try to get weixin open id 1 times
        WeixinHelper::triggerWeixinGetToken();
    } else {
        needSignin($method_routes, $command);
    }
}
/**
 * try to set uid in cookie and session
 * @param $id
 */
function saveId($id) {
    $cookieValue = EncryptHelper::encrypt($id);
    $_SESSION['signedUser'] = $id;
    setcookie("signedUser", $cookieValue, time()+7*24*3600);
}
/**
 * redirect to sign in pages
 * @param $method_routes
 * @param $command
 */
function needSignin($method_routes, $command) {
    $msg = "请先登陆,然后可以" . $method_routes[$command]['d'];
    if (isset($method_routes[$command]['v'])) {
        $_SESSION['$signInErrorMsg'] = $msg;
        header('Location:../View/mobile/users/signin.php');
    } else {
        $response = array(
            'status'  => 200,
            'msg' => $msg,
            'result' => ""
        );
        echo json_encode($response);
        exit;
    }
}

function goToCommand($method_routes, $command) {

    if (isset($method_routes[$command]['m']) && isset($method_routes[$command]['f'])){
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":"."method=".$method_routes[$command]['m']."function=".$method_routes[$command]['f']);
        try {
            $class = $method_routes[$command]['m'];
            $fun = $method_routes[$command]['f'];
            //echo $class.$fun;
            //exit(1);
            $class = new $class();
            call_user_func(array($class, $fun));
        } catch (Exception $e) {
            echo $e->getTrace();
        }
    }
    if (isset($method_routes[$command]['v'])){
        header('Location:'.$method_routes[$command]['v']);
    }
}
?>