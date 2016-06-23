<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
require dirname(__FILE__).'/../init.php';
use Addons\OverSea\Common\HttpHelper;
session_start();

//c - command, like signin, m - model, f - function in model, v - view,  d - description
$method_routes = array(
    'setLocation' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'setLocation','v'=>'../View/mobile/query/discover.php','d'=>'服务信息列表'),
    'getServices' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getServices','v'=>'../View/mobile/query/discover.php','d'=>'服务信息列表'),
    'serviceDetails' => array('m'=>'Addons\OverSea\Model\ServicesBo', 'f'=>'getServiceById','v'=>'../View/mobile/service/servicedetails.php','d'=>'卖家详细信息'),
);

HttpHelper::saveServerQueryStringVales($_SERVER['QUERY_STRING']);
$command = HttpHelper::getVale('c');
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
    header('Location:'.$method_routes[$command]['v'].'?t='.rand(0,10000));
}

?>