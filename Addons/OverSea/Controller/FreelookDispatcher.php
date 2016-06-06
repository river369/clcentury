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
    'sellerdetails' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'getCurrentSellerInfoAndPictures','v'=>'../View/mobile/users/sellerdetails.php','d'=>'卖家详细信息'),
);

HttpHelper::saveServerQueryStringVales($_SERVER['QUERY_STRING']);
$command = HttpHelper::getVale('c');
/*
$command;
if (isset($_GET ['c'])){
    // get call back url from GET
    $command = $_GET ['c'];
}
*/

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
    header('Location:'.$method_routes[$command]['v']);
}

?>