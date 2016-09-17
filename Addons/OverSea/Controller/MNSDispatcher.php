<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/1
 * Time: 17:05
 */
require dirname(__FILE__).'/../init.php';
use Addons\OverSea\Common\Logs;
session_start();
Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ . "1111");

//c - command, like signin, m - model, f - function in model, v - view,  d - description
$method_routes = array(
    // not use now
    'sendMessagesThroughWeixin' => array('m'=>'Addons\OverSea\Model\UsersBo', 'f'=>'sendMessagesThroughWeixin', 'd'=>'通过微信发送消息'),
);

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

function get_by_url($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    $output = curl_exec($ch);

    curl_close($ch);

    return $output;
}

function verify($data, $signature, $pubKey)
{
    $res = openssl_get_publickey($pubKey);
    $result = (bool) openssl_verify($data, base64_decode($signature), $res);
    openssl_free_key($res);

    return $result;
}

if (!function_exists('getallheaders'))
{
    function getallheaders()
    {

        $headers = array();
        foreach ($_SERVER as $name => $value)
        {
            //Logs::writeMessageLog("MNS messages:getallheaders name=".$name." value=".$value);
            if (substr($name, 0, 5) == 'HTTP_')
            {
                //$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                $headers[str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))))] = $value;
            }
        }
        return $headers;
    }
}

function run($method_routes){
    Logs::writeMessageLog("MNS Dispatcher:start");

    // 1. get the headers and check the signature
    // 1.1 build $canonicalizedMNSHeaders
    $tmpHeaders = array();
    $headers = getallheaders();
    Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ . "MNS messages:Headers=".json_encode($headers));
    foreach ($headers as $key => $value)
    {
        if (0 === strpos($key, 'x-mns-') )
        {
            $tmpHeaders[$key] = $value;
        }
    }
    ksort($tmpHeaders);
    //Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ . "MNS messages:tmpHeaders=".json_encode($tmpHeaders));
    $canonicalizedMNSHeaders = implode("\n", array_map(function ($v, $k) { return $k . ":" . $v; }, $tmpHeaders, array_keys($tmpHeaders)));
    //Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ . "MNS messages:verify:canonicalizedMNSHeaders=".$canonicalizedMNSHeaders);

    // 1.2 build canonicalizedResource
    $method = $_SERVER['REQUEST_METHOD'];
    $canonicalizedResource = $_SERVER['REQUEST_URI'];
    //Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ ."MNS messages:verify:canonicalizedResource=".$canonicalizedResource);

    // 1.3 build contentMd5
    $contentMd5 = '';
    if (array_key_exists('content-md5', $headers))
    {
        $contentMd5 = $headers['content-md5'];
    }
    //Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ ."MNS messages:verify:content-md5=".$contentMd5);

    // 1.4 build content-type
    $contentType = '';
    if (array_key_exists('content-type', $headers))
    {
        $contentType = $headers['content-type'];
    }
    //Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ ."MNS messages:verify:contentType=".$contentType);
    // 1.5 build date
    $date = $headers['date'];
    //Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ ."MNS messages:verify:date=".$date);

    // 1.6 build stringToSign (Aggregate 1.1-1.5)
    $stringToSign = strtoupper($method) . "\n" . $contentMd5 . "\n" . $contentType . "\n" . $date . "\n" . $canonicalizedMNSHeaders . "\n" . $canonicalizedResource;
    Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ ."MNS messages:verify:stringToSign=".$stringToSign);

    //$publicKeyURL = base64_decode($headers['X-Mns-Signing-Cert-Url']);
    $publicKeyURL = base64_decode($headers['x-mns-signing-cert-url']);
    $publicKey = get_by_url($publicKeyURL);
    $signature = $headers['authorization'];
    Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ ."MNS messages:verify:publicKeyURL=". $publicKeyURL . " publicKey=" . $publicKey ." signature=".$signature);
    $pass = verify($stringToSign, $signature, $publicKey);
    if (!$pass)
    {
        Logs::writeMessageLog("verify signature fail");
        http_response_code(400);
        return;
    }

    // 2. now parse the content
    $content = file_get_contents("php://input");
    Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ ."MNS messages:parse:content=".$content);

    if (!empty($contentMd5) && $contentMd5 != base64_encode(md5($content)))
    {
        Logs::writeMessageLog("md5 mismatch");
        http_response_code(401);
        return;
    }

    $msg = new SimpleXMLElement($content);
    Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ ."MNS TopicName : ".$msg->TopicName);
    Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ ."MNS SubscriptionName : ".$msg->SubscriptionName);
    Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ ."MNS MessageId : ".$msg->MessageId);
    Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ ."MNS MessageMD5 : ".$msg->MessageMD5);
    Logs::writeMessageLog(__CLASS__ . "," . __FUNCTION__ ."MNS Messages : ".$msg->Message);
    http_response_code(204);

    // 3 parse and run command
    //$command = $_GET['c'];
    //Logs::writeMessageLog("command=".$command);
    //goToCommand($method_routes, $command);

    //==== The following is just a sample, should be in BO classes
    /*
 $json = '{"a":1,"b":2,"c":3,"d":4,"e":5}';
var_dump(json_decode($json));
var_dump(json_decode($json, true));
 */
//$json= '{"out_trade_no":"1339077401_20160611101407_4","order_id":"4","start_date":"20160611101407","transaction_id":"4001002001201606117091057528","cash_fee":"1","total_fee":"1","fee_type":"CNY","openid":"om0h_wU7JP0aTlA4soWzzcL5gPeY","is_subscribe":"Y","result_code":"SUCCESS","return_code":"SUCCESS","trade_type":"JSAPI","end_date":"20160611101419"}';
//echo $json;
//$paymentData = json_decode($json,true);
//OrdersBo::paymentConfirmOrder($paymentData);
//OrdersBo::sendMessagesThroughWeixin('5779166370783206', 10);

}

run($method_routes);


?>