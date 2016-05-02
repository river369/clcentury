

//////
<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/4/29
 * Time: 20:34
 */
use Common\MySqlHelper;
use Common\WeixinHelper;

require dirname(__FILE__).'/../init.php';

session_start();

/*
$sql = 'SELECT * FROM wp_user WHERE nickname = :user_name LIMIT 1';
$user = MySqlHelper::fetchOne($sql, array(':user_name' => 'river'));

if (isset($user['nickname']) === false) {
   echo $user['nickname'];
} else {
    echo $user['nickname'];
}
*/
if(isset($_SESSION['weixinUserInfo'])){
    echo "data from session[".$_SESSION['weixinUserInfo']."]";

} else {
    $code = null;
    if (isset($_GET['code'])){
        $code = $_GET['code'];
    }else{
        echo "NO CODE";
    }

    $tokenArray = WeixinHelper::getAuthToken ($code);
    $token = $tokenArray['refresh_token'];
    $openid = $tokenArray['openid'];
    $refreshtoken = $tokenArray['refresh_token'];

    $user = WeixinHelper::getWeixinUserInfoWithRefresh($token, $openid, $refreshtoken);

    $_SESSION['weixinUserInfo']= $user['nickname'] . " -- ".$user['sex'] . " -- ".$user['language'] . " -- ".$user['city'] .
        " -- ".$user['province'] . " -- ".$user['country'] . " -- ".$user['headimgurl'] . " union id == ".$user['unionid']
        . " open id ==  ".$user['openid']. " -- ".$user['subscribe']. " -- ".$user['errmsg'];
    echo "data from service[".$_SESSION['weixinUserInfo']."]";

}






?>