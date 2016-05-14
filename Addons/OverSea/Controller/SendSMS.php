<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/14
 * Time: 17:55
 */
require dirname(__FILE__).'/../init.php';
use Addons\OverSea\Common\YunpianSMSHelper;

header("Content-Type:text/html;charset=utf-8");
$text="【云片网】您的验证码是1234";
//$mobile = '+8613520143483';
$mobile = '+14257362292';
YunpianSMSHelper::sendSMS($text, $mobile);

?>