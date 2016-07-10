<?php
//[ This is just for test, not use now ]

require dirname(__FILE__).'/../init.php';

use Addons\OverSea\Common\MNSHelper;
//http://www.clcentury.com/weiphp/Addons/OverSea/Controller/MNSPublishTopicTool.php?topic=clcOrderTopic&info=test1
$topic = $_GET['topic'];
$info = $_GET['info'];
MNSHelper::publishMessage($topic, $info);