<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Model\UserAccountsDao;
use Addons\OverSea\Common\WeixinHelper;
use Addons\OverSea\Common\EncryptHelper;
use Addons\OverSea\Common\HttpHelper;
use Addons\OverSea\Common\Logs;

require dirname(__FILE__).'/../init.php';

session_start();

//c - command, like signin, m - model, f - function in model, v - view,  d - description
$method_routes = array(
    'signin' => array('v'=>'../View/mobile/users/signin.php','d'=>''),//submit service
    'mine' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'getCurrentUserInfo', 
        'v'=>'../View/mobile/users/mine.php','d'=>'进入我的主页'),

    'changePassword' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'changePassword',
        'v'=>'../View/mobile/common/message.php','d'=>'修改密码'),
    'myinfo' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'getCurrentUserInfo', 
        'v'=>'../View/mobile/users/myinfo.php','d'=>'查看我的信息'),
    'updateMyinfo' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'createOrUpdateUserInfo', 
        'v'=>'../View/mobile/common/message.php','d'=>'完善我的信息'),
    'submitheadpic' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'handleHeads', 'd'=>'发个人头像'),
    'publishRealName' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'prepareRealName', 
        'v'=>'../View/mobile/users/realname.php','d'=>'发实名认证信息'),
    'publishRealNamePics' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'publishRealNamePics','d'=>'发实名认证图片'),
    'publishRealNameInfo' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'publishRealNameInfo', 
        'v'=>'../View/mobile/common/message.php', 'd'=>'发实名认证信息'),
    'getUsers' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'getUsers', 
        'v'=>'../View/admin/all_users.php', 'd'=>'admin查看用户信息'),
    'checkUser' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'checkUser', 
        'v'=>'../View/admin/all_users.php', 'd'=>'admin查看用户信息'),
    
    'getSellerPayInfo' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'getSellerPayInfo',
        'v'=>'../View/mobile/users/select_pay_account.php', 'd'=>'查看卖家收款账号'),
    'updateSellerPayInfo' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'updateSellerPayInfo',
        'v'=>'../View/mobile/common/message.php', 'd'=>'更新卖家收款账号'),
    

    'publishService' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getCurrentService', 
        'v'=>'../View/mobile/service/publishservice.php','d'=>'发易知服务信息'),
    'submitServiceMainPic' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'publishServiceMainPic', 'd'=>'发个人头像'),
    'publishServicePics' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'publishServicePics','d'=>'发易知图片'),
    'publishServiceInfo' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'publishServiceInfo', 
        'v'=>'../View/mobile/common/message.php', 'd'=>'发易知服务信息'),
    'myServices' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getMyServicesByStatus', 
        'v'=>'../View/mobile/service/my_services.php', 'd'=>'查看我发布的易知服务信息'),
    'deleteService' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'deleteService', 
        'v'=>'../View/mobile/service/my_services.php', 'd'=>'删除易知服务信息'),
    'getServices' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getServicesByStatus', 
        'v'=>'../View/admin/all_services.php', 'd'=>'admin查看易知服务信息'),
    'checkService' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'checkService', 
        'v'=>'../View/admin/all_services.php', 'd'=>'admin查看易知服务信息'),
    'pauseService' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'pauseService',
        'v'=>'../View/mobile/service/my_services.php', 'd'=>'暂停易知服务信息'),
    'recoverService' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'recoverService',
        'v'=>'../View/mobile/service/my_services.php', 'd'=>'恢复易知服务信息'),
    
    'getYPlusList' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getYPlusList',
        'v'=>'../View/mobile/service/service_yplus_list.php','d'=>'获取易知服务YPlus条目列表'),
    'editYPlusItem' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'editYPlusItem',
        'v'=>'../View/mobile/service/service_yplus_item.php','d'=>'获取易知服务YPlus条目'),
    'deleteYPlusItem' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'deleteYPlusItem',
        'v'=>'../View/mobile/service/service_yplus_list.php','d'=>'删除YPlus条目'),
    'publishServiceYPlusItem'  => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'publishServiceYPlusItem',
        'v'=>'../View/mobile/service/publishservice.php','d'=>'保存易知服务YPlus条目'),
    'publishServiceYPlusItemPics' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'publishServiceYPlusItemPics','d'=>'发YPlus条目图片'),
    
    'submitOrder' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'getServiceInfoById', 
        'v'=>'../View/mobile/orders/submitorder.php','d'=>'订购'),//用户订购确认
    'createOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'createOrder', 
        'v'=>'../View/mobile/common/message.php','d'=>'创建订单'),
    'repayOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'repayOrder',
        'v'=>'../View/mobile/orders/submitorderstatus.php','d'=>'支付订单'),
    'queryCustomerOrders' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'getOrdersByCustomerAndStatus',
        'v'=>'../View/mobile/orders/customerorderlist.php', 'd'=>'查看买家订单'),
    'querySellerOrders' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'getOrdersBySellerAndStatus', 
        'v'=>'../View/mobile/orders/sellerorderlist.php', 'd'=>'查看卖家订单'),
    'queryAdminOrders' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'getOrdersByStatusForAdmin',
        'v'=>'../View/admin/admin_orderlist.php', 'd'=>'查看Admin订单'),
    'getDealyOrdersByStatusForAdmin'  => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'getDealyOrdersByStatusForAdmin',
        'v'=>'../View/admin/admin_orderlist.php', 'd'=>'查看Admin订单'),
    
    'sellerRejectOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'sellerRejectOrder', 
        'v'=>'./AuthUserDispatcher.php?c=querySellerOrders&status=1020,1040,1060', 'd'=>'卖家拒绝订单'),
    'sellerAcceptOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'sellerAcceptOrder', 
        'v'=>'./AuthUserDispatcher.php?c=querySellerOrders&status=20', 'd'=>'卖家接受订单'),
    'sellerCancelOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'sellerCancelOrder', 
        'v'=>'./AuthUserDispatcher.php?c=querySellerOrders&status=1020,1040,1060', 'd'=>'卖家取消订单'),
    'sellerFinishOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'sellerFinishOrder', 
        'v'=>'./AuthUserDispatcher.php?c=querySellerOrders&status=40', 'd'=>'卖家完成订单'),
    'customerConfirmOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'customerConfirmOrder', 
        'v'=>'../View/mobile/orders/comment_order.php', 'd'=>'买家完成订单'), 
    'customerCommnetOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'customerCommentOrder',
            'v'=>'./AuthUserDispatcher.php?c=queryCustomerOrders&status=60,80,100', 'd'=>'买家评论订单'),
    'customerCancelOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'customerCancelOrder',
        'v'=>'./AuthUserDispatcher.php?c=queryCustomerOrders&status=1020,1040,1060', 'd'=>'买家取消订单'),
    'customerRejectOrder' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'customerRejectOrder',
        'v'=>'./AuthUserDispatcher.php?c=queryCustomerOrders&status=70', 'd'=>'买家提出争议'),
    'returnMoneyToCustomer' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'returnMoneyToCustomer',
        'v'=>'./AuthUserDispatcher.php?c=queryAdminOrders&status=1020,1040,1060', 'd'=>'向买家退款'),
    'payMoneyToSeller' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'payMoneyToSeller',
        'v'=>'./AuthUserDispatcher.php?c=queryAdminOrders&status=80,60', 'd'=>'向卖家付款'),

    'queryOrderDetails' => array('m'=>'Addons\OverSea\Model\OrdersBo', 'f'=>'getOrderDetailsById',
        'v'=>'../View/mobile/orders/orderdetails.php', 'd'=>'查看订单详情'),
    

    'searchMainPage' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getQueryHistory','v'=>'../View/mobile/query/search.php','d'=>'搜索主页'),
    'searchByKeyWord' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getServicesByKey','v'=>'../View/mobile/query/searchresults.php','d'=>'搜索结果列表'),
    'deleteKeyWordById' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'deleteKeyWordById','v'=>'../View/mobile/query/search.php','d'=>'搜索主页'),
    //'sellerPublishedServices' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getSellerPublishedServices',
      //  'v'=>'../View/mobile/query/seller_published_services.php','d'=>'查看卖家的主页'),

    'getAdvertiseList' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getAdvertiseList','v'=>'../View/admin/all_advertises.php','d'=>'广告列表'),
    'prepareAdvertise' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'prepareAdvertise','v'=>'../View/admin/publish_advertise.php','d'=>'广告图片'),
    'publishAdvertise' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'publishAdvertise','d'=>'发广告'),
    'deleteAdvertiseOfService' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'deleteAdvertiseOfService','v'=>'../View/admin/all_advertises.php','d'=>'广告列表'),
    
);

Logs::writeClcLog("AuthUserDipatcher.php, Starting");
HttpHelper::saveServerQueryStringVales($_SERVER['QUERY_STRING']);
$command = HttpHelper::getVale('c');

if (isset($_SESSION['signedUser'])) {
    Logs::writeClcLog("AuthUserDipatcher.php, Get user from session as ".$_SESSION['signedUser']);
    // first choice is session
    goToCommand($method_routes, $command);
} else {
    $cookieValue = isset($_COOKIE["signedUser"])? EncryptHelper::decrypt($_COOKIE["signedUser"]) : "";
    //$cookieValue = null; //to temp disable cookie for test weixin
    if (isset($cookieValue) && !empty($cookieValue) && !is_null($cookieValue)){
        Logs::writeClcLog("AuthUserDipatcher.php, Get user from cookie as ".$cookieValue);
        saveId($cookieValue);
        goToCommand($method_routes, $command);
    } /*else if (isset($_SESSION['weixinOpenid'])) {
        // check if weixin openid match the db saving values
        $userDao = new UserAccountsDao();
        $existedUser=$userDao->getUserByExternalId($_SESSION['weixinOpenid']);
        if (isset($existedUser['openid']) && $existedUser['openid'] == $_SESSION['weixinOpenid']){
            Logs::writeClcLog("AuthUserDipatcher.php, Get user from session openid as ".$existedUser['id']);
            saveId($existedUser['id']);
            goToCommand($method_routes, $command);
        } else {
            Logs::writeClcLog("AuthUserDipatcher.php, go to sign in page");
            needSignin($method_routes, $command);
        }

    } else if (!isset($_SESSION['weixinOpenidTried'])) {
        Logs::writeClcLog("AuthUserDipatcher.php,try to call weixin to verify");
        // Try to get weixin open id 1 times
        WeixinHelper::triggerWeixinGetToken();
    } */ else {
        Logs::writeClcLog("AuthUserDipatcher.php, go to sign in page");
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
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",method=".$method_routes[$command]['m'].",function=".$method_routes[$command]['f']);
        try {
            $class = $method_routes[$command]['m'];
            $fun = $method_routes[$command]['f'];
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