<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/14
 * Time: 17:55
 */
require dirname(__FILE__).'/../init.php';
use Addons\OverSea\Common\YunpianSMSHelper;
session_start();
//header("Content-Type:text/html;charset=utf-8");
//$text="【云片网】您的验证码是1234";
//$mobile = '+8613520143483111';
////$mobile = '+14257362292';
//$result = YunpianSMSHelper::sendSMS($text, $mobile);
//echo json_encode(array('status'=> $result['code'], 'msg'=> $result['msg']));

$mobile;
if (isset($_POST['phonereigon']) && isset($_POST['phonenumber'])){
    $mobile = $_POST['phonereigon'].$_POST['phonenumber'];
    $_SESSION['verifcationCode'] = rand(1000,9999);
    $text="【易知海外】您的验证码是".$_SESSION['verifcationCode'];
    if ($_POST['phonereigon'] != '+86') {
        $text="【eknowhow】Your verification code is ".$_SESSION['verifcationCode'];
    }
    $result = YunpianSMSHelper::sendSMS($text, $mobile);
    echo json_encode(array('status'=> $result['code'], 'msg'=> $result['msg'].':'.$result['detail']));
} else {
    echo json_encode(array('status'=> -1));
}

exit;

?>