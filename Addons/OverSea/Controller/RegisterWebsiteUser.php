<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/4/29
 * Time: 20:34
 */
use Common\MySqlHelper;
require dirname(__FILE__).'/../Common/Weixin.php';
require dirname(__FILE__).'/../init.php';

$sql = 'SELECT * FROM wp_user WHERE nickname = :user_name LIMIT 1';
$user = MySqlHelper::fetchOne($sql, array(':user_name' => 'river'));

if (isset($user['nickname']) === false) {
   echo $user['nickname'];
} else {
    echo $user['nickname'];
}

$code = null;
if (isset($_GET['code'])){
    $code = $_GET['code'];
}else{
    echo "NO CODE";
}
$tokenArray = getAuthToken ($code);
$token = $tokenArray['refresh_token'];
$openid = $tokenArray['openid'];
$refreshtoken = $tokenArray['refresh_token'];

$user = getWeixinUserInfoWithRefresh($token, $openid, $refreshtoken);
echo $user['nickname'] . " -- ".$user['sex'] . " -- ".$user['language'] . " -- ".$user['city'] .
    " -- ".$user['province'] . " -- ".$user['country'] . " -- ".$user['headimgurl'] . " -- ".$user['unionid']
    . " -- ".$user['openid']. " -- ".$user['subscribe']. " -- ".$user['errmsg'];

?>