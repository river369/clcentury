<?php
//[ This is just for test, not use now ]

require dirname(__FILE__).'/../init.php';

use Addons\OverSea\Common\MNSHelper;
use Addons\OverSea\Common\Logs;

//http://www.clcentury.com/weiphp/Addons/OverSea/Controller/MNSPublishTopicTool.php?topic=yzOrderTopic&info=test-yzOrderTopic
$topic = $_GET['topic'];
$info = $_GET['info'];
Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":starting mns send...");
MNSHelper::publishMessage($topic, $info);
Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":finished mns send...");
echo "message is sent!";