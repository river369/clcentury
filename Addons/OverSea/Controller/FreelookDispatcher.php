<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
$startTime = microtime(true)*1000;
require dirname(__FILE__).'/../init.php';
use Addons\OverSea\Common\HttpHelper;
use Addons\OverSea\Common\Logs;
session_start();

//c - command, like signin, m - model, f - function in model, v - view,  d - description
$method_routes = array(
    'index' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'index', 'v'=>'../View/mobile/query/discover.php','d'=>'服务信息列表'),
    'getCities' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getAllCities','v'=>'../View/mobile/query/setlocation.php','d'=>'服务信息列表'),
    'setLocation' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'setLocation','v'=>'../View/mobile/query/discover.php','d'=>'服务信息列表'),
    'getServices' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getServices','v'=>'../View/mobile/query/discover.php','d'=>'服务信息列表'),
    'serviceDetails' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getServiceById','v'=>'../View/mobile/service/servicedetails.php','d'=>'卖家详细信息'),
    'getTagsByCityBusinessType' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getTagsByCityBusinessType', 'd'=>'根据城市获得tag'),

    'sendRegistrationPassword' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'sendRegistrationPassword', 'd'=>'发送验证码'),
    'sendTempPasswordToPhone' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'sendTempPasswordToPhone',
        'v'=>'../View/mobile/common/message.php','d'=>'获取临时密码'),

    
);

Logs::writeClcLog("FreelookDispatcher start");
$command = $_GET['c'];
//HttpHelper::saveServerQueryStringVales($_SERVER['QUERY_STRING']);
//$command = HttpHelper::getVale('c');
Logs::writeClcLog("command=".$command);
$periodTime = microtime(true)*1000 - $startTime;
Logs::writeClcLog(date('y-m-d h:i:s',time()).",rtt,FreelookDispatcher,prepare,".$periodTime);

goToCommand($method_routes, $command);

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
    if (isset($method_routes[$command]['v'])) {
        header('Location:' . $method_routes[$command]['v'] . '?t=' . rand(0, 10000));
    }
}

?>