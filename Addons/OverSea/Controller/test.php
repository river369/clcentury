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
use Addons\OverSea\Common\Logs;
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
            //Logs::writeClcLog("MNS messages:getallheaders name=".$name." value=".$value);
            if (substr($name, 0, 5) == 'HTTP_')
            {
                //$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                $headers[str_replace(' ', '-', strtolower(str_replace('_', ' ', substr($name, 5))))] = $value;
            }
        }
        return $headers;
    }
}
Logs::writeClcLog("MNS messages:start");

// 1. get the headers and check the signature
$tmpHeaders = array();
$headers = getallheaders();
Logs::writeClcLog("MNS messages:Headers=".json_encode($headers));
foreach ($headers as $key => $value)
{
    if (0 === strpos($key, 'x-mns-') )
    {
        $tmpHeaders[$key] = $value;
    }
}
ksort($tmpHeaders);
Logs::writeClcLog("MNS messages:tmpHeaders=".json_encode($tmpHeaders));
$canonicalizedMNSHeaders = implode("\n", array_map(function ($v, $k) { return $k . ":" . $v; }, $tmpHeaders, array_keys($tmpHeaders)));

$method = $_SERVER['REQUEST_METHOD'];
$canonicalizedResource = $_SERVER['REQUEST_URI'];
Logs::writeClcLog("MNS messages:verify:canonicalizedResource=".$canonicalizedResource);

$contentMd5 = '';
if (array_key_exists('content-md5', $headers))
{
    $contentMd5 = $headers['content-md5'];
}

$contentType = '';
if (array_key_exists('content-type', $headers))
{
    $contentType = $headers['content-type'];
}
$date = $headers['date'];

$stringToSign = strtoupper($method) . "\n" . $contentMd5 . "\n" . $contentType . "\n" . $date . "\n" . $canonicalizedMNSHeaders . "\n" . $canonicalizedResource;
Logs::writeClcLog("MNS messages:verify:stringToSign=".$stringToSign);

//$publicKeyURL = base64_decode($headers['X-Mns-Signing-Cert-Url']);
$publicKeyURL = base64_decode($headers['x-mns-signing-cert-url']);
$publicKey = get_by_url($publicKeyURL);
$signature = $headers['authorization'];
Logs::writeClcLog("MNS messages:verify:publicKey=".$publicKey . " publicKeyURL=" . $publicKeyURL ." signature=".$signature);
$pass = verify($stringToSign, $signature, $publicKey);
if (!$pass)
{
    Logs::writeClcLog("verify signature fail");
    http_response_code(400);
    return;
}

// 2. now parse the content
$content = file_get_contents("php://input");
Logs::writeClcLog("MNS messages:parse:content=".$content);

if (!empty($contentMd5) && $contentMd5 != base64_encode(md5($content)))
{
    Logs::writeClcLog("md5 mismatch");
    http_response_code(401);
    return;
}

$msg = new SimpleXMLElement($content);
echo "\n______________________________________________________\n";
echo "TopicName: " . $msg->TopicName . "\n";
echo "SubscriptionName: " . $msg->SubscriptionName . "\n";
echo "MessageId: " . $msg->MessageId . "\n";
echo "MessageMD5: " . $msg->MessageMD5 . "\n";
echo "Message: " . $msg->Message . "\n";
echo "______________________________________________________\n";
Logs::writeClcLog("MNS messages : ".$msg->Message);
http_response_code(204);



?>