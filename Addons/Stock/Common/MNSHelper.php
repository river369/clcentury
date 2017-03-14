<?php
namespace Addons\OverSea\Common;
if (is_file(__DIR__ . '/../lib/aliyun-mns-php-sdk/mns-autoloader.php')) {
    require_once __DIR__ . '/../lib/aliyun-mns-php-sdk/mns-autoloader.php';
}

use AliyunMNS\Client;
use AliyunMNS\Topic;
use AliyunMNS\Requests\PublishMessageRequest;
use AliyunMNS\Exception\MnsException;

use Addons\OverSea\Common\Logs;


class MNSHelper
{
    private static $accessId;
    private static $accessKey;
    private static $endPoint;
    //private static $client;
    //http://1686661312870509.mns.cn-beijing-internal.aliyuncs.com/topics/yzOrderTopic/subscriptions/test2
    //http://1686661312870509.mns.cn-beijing-internal.aliyuncs.com/
    
    public static function initData($accessId, $accessKey, $endPoint)
    {
        self::$accessId = $accessId;
        self::$accessKey = $accessKey;
        self::$endPoint = $endPoint;
    }

    /**
     * 根据Config配置，得到一个MnsClient实例, 和一个topic
     *
     * @return Topic
     */
    public static function getMnsTopic($topicName){
        try {
            $client = new Client(self::$endPoint, self::$accessId, self::$accessKey);
            $topic = $client->getTopicRef($topicName);
            return $topic;
        } catch (MnsException $e) {
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":"."creating Mns Topic: FAILED\n".$e->getMessage());
            return null;
        }
    }

    public static function publishMessage($topicName, $info)
    {
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":"."PublishMessage, topicName=".$topicName.",info=".$info);
        // as the messageBody will be automatically encoded
        // the MD5 is calculated for the encoded body
        //$bodyMD5 = md5(base64_encode($info));
        $request = new PublishMessageRequest($info);
        try
        {
            $topic = self::getMnsTopic($topicName);
            $res = $topic->publishMessage($request);
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":Message Published");
            return;
        } catch (MnsException $e) {
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":"."PublishMessage FAILED\n".$e->getMessage());
            echo "PublishMessage Failed: " . $e;
            return;
        }
    }

}
