<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
use Addons\OverSea\Common\EncryptHelper;
require dirname(__FILE__).'/../init.php';
use Addons\OverSea\Model\OrdersBo;
/*
 $json = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
var_dump(json_decode($json));
var_dump(json_decode($json, true));
 */
$json= '{"out_trade_no":"1339077401_20160611101407_4","order_id":"4","start_date":"20160611101407","transaction_id":"4001002001201606117091057528","cash_fee":"1","total_fee":"1","fee_type":"CNY","openid":"om0h_wU7JP0aTlA4soWzzcL5gPeY","is_subscribe":"Y","result_code":"SUCCESS","return_code":"SUCCESS","trade_type":"JSAPI","end_date":"20160611101419"}';
echo $json;
$paymentData = json_decode($json,true);
//OrdersBo::paymentConfirmOrder($paymentData);
OrdersBo::sendMessagesThroughWeixin('5779166370783206', 10);

?>