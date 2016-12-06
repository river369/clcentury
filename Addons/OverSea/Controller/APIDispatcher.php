<?php
/**
 * Eknowhow数据对接接口入口文件,主要负责入口访问合法验证,返回请求对应的相应数据.
 * PHP version 5.5
 * @author jianguog
 */
use Addons\OverSea\Common\HttpHelper;
use Addons\OverSea\Common\Logs;

$startTime = microtime(true)*1000;
require dirname(__FILE__).'/../init.php';

define('API_SECRET_KEY', '71e5d83f6480523cb7b52e13445c2865');
//session_start();
header('Content-type: application/json; charset=utf-8');
$_POST = array_merge($_GET, $_POST);

// 来源.
$source = isset($_POST['source']) ? $_POST['source'] : 'eknow';
// 版本.
$version = isset($_POST['version']) ? $_POST['version'] : '1.0';
// 方法.
$method = isset($_POST['method']) ? $_POST['method'] : '';
// 令牌.
$token = isset($_POST['token']) ? trim($_POST['token']) : '';
// 用户app_uid.
$app_uid = isset($_POST['app_uid']) && $_POST['app_uid'] != '' ? intval($_POST['app_uid']) : '';
// 经过base64编码的数据文件.
$data = isset($_POST['data']) ? $_POST['data'] : '';
// 数字签名.
$sign = isset($_POST['sign']) ? $_POST['sign'] : '';

// 解码数据.
//$request_data = json_decode(base64_decode($data), true);
$request_data = array();
$request_data['test'] = "test123";

//c - command, like signin, m - model, f - function in model, d - description
$method_routes = array(
    'getServices' => array('m'=>'Addons\OverSea\Api\Services', 'f'=>'getServices','d'=>'服务信息列表')
);

Logs::writeAPILog("APIDispatcher start");
$command = 'getServices';

goToCommand($method_routes, $command, $request_data);

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
    }
}

?>