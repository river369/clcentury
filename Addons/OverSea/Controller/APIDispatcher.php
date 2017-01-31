<?php
/**
 * Eknowhow数据对接接口入口文件,主要负责入口访问合法验证,返回请求对应的相应数据.
 * PHP version 5.5
 * @author jianguog
 */
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Api\Common;

$startTime = microtime(true)*1000;
require dirname(__FILE__).'/../init.php';
Logs::writeClcLog("APIDispatcher start");
define('API_SECRET_KEY', '71e5d83f6480523cb7b52e13445c2865');
//session_start();
header('Content-type: application/json; charset=utf-8');
$POST_RAW = json_decode(file_get_contents("php://input"), true);
$Input = isset($POST_RAW) ? $POST_RAW : array_merge($_GET, $_POST);
Logs::writeClcLog("InputJSON,".json_encode($Input));
validateInputs($Input);

// 来源.
$source = isset($Input['source']) ? $Input['source'] : 'eknow';
// 版本.
$version = isset($Input['version']) ? $Input['version'] : '1.0';
// 方法.
$method = $Input['method'];
// 令牌.
$token = isset($Input['token']) ? trim($Input['token']) : '';
// 用户app_uid.
$app_uid = isset($Input['app_uid']) && $Input['app_uid'] != '' ? intval($Input['app_uid']) : '';
// 经过base64编码的数据文件.
$data = $Input['data'];
// 数字签名.
$sign = isset($Input['sign']) ? $Input['sign'] : '';

// 解码数据.
$request_data = json_decode(base64_decode($data), true);
Logs::writeClcLog("APIDispatcher,InputDataJSON,".json_encode($request_data));

//c - command, like signin, m - model, f - function in model, d - description
$method_routes = array(
    'signIn' => array('m'=>'Addons\OverSea\Api\Users', 'f'=>'signIn','d'=>'SignIn'),
    'getServices' => array('m'=>'Addons\OverSea\Api\Services', 'f'=>'getServices','d'=>'服务信息列表'),
    'getServicePictures' => array('m'=>'Addons\OverSea\Api\Services', 'f'=>'getServicePictures','d'=>'服务信息图片'),
    'getServiceInfoById' => array('m'=>'Addons\OverSea\Api\Services', 'f'=>'getServiceInfoById','d'=>'服务信息'),
    'getAggregatedServiceDetails' => array('m'=>'Addons\OverSea\Api\Services', 'f'=>'getAggregatedServiceDetails','d'=>'其它服务信息'),
    'createOrUpdatePublishingService' => array('m'=>'Addons\OverSea\Api\Services', 'f'=>'createOrUpdatePublishingService','d'=>'create or update 服务信息'),
);

goToCommand($method_routes, $method, $request_data);

function goToCommand($method_routes, $command, $request_data) {
    if (isset($method_routes[$command]['m']) && isset($method_routes[$command]['f'])){
        try {
            $class = $method_routes[$command]['m'];
            $fun = $method_routes[$command]['f'];
            $class = new $class($request_data);
            call_user_func(array($class, $fun));
        } catch (Exception $e) {
            echo $e->getTrace();
        }
    } else {
        Common::responseError(1003, "method参数不合法。");
    }
}

function validateInputs($InputData) {
    if (!isset($InputData['method'])){
        Common::responseError(1001, "客户端未传递参数。");
    }
    if (!isset($InputData['method'])){
        Common::responseError(1002, "参数method必选。");
    }
    if (!isset($InputData['data'])){
        Common::responseError(1002, "参数data必选。");
    }
}
?>