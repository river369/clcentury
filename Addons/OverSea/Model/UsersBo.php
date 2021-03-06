<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/18
 * Time: 10:08
 */

namespace Addons\OverSea\Model;

use Addons\OverSea\Model\UserInfosDao;
use Addons\OverSea\Model\UserAccountsDao;
use Addons\OverSea\Model\UserSettingsDao;
use Addons\OverSea\Model\SellerPayAccountsDao;
use Addons\OverSea\Model\SuggestionsDao;
use Addons\OverSea\Common\OSSHelper;
use Addons\OverSea\Common\HttpHelper;
use Addons\OverSea\Common\WeixinHelper;
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Common\EncryptHelper;
use Addons\OverSea\Common\YunpianSMSHelper;
use Addons\OverSea\Common\MySqlHelper;

class UsersBo
{
    public function __construct() {
    }

    /**
     * set user setting if user used to sign in
     */
    public function index(){
        if (!isset($_SESSION['userSetting'])){
            $user_id = self::getUserIdFromSession();
            if (!empty($user_id) && !is_null($user_id)) {
                self::setUserSettingInSessionById($user_id);
            }
        }
    }

    /**
     * Set user runnning location
     */
    public function setLocation(){
        $servicearea = '';
        if (isset($_SESSION ['servicearea'])){
            $servicearea = $_SESSION ['servicearea'];
        }
        if (isset($_GET ['servicearea'])){
            $servicearea = $_GET ['servicearea'];
            $_SESSION ['servicearea'] = $servicearea;
        }
        $user_id = self::getUserIdFromSession();
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",servicearea=".$servicearea." user_id=".$user_id);

        if (!empty($user_id) && !is_null($user_id)){
            self::setUserSettingInSessionById($user_id);
            $userSettingsDao = new UserSettingsDao();
            $userSetting = array();
            $userSetting['user_id'] = $user_id;
            $userSetting['selected_service_area'] = $servicearea;
            $userSettingsDao->insertOrUpdateUserSetting($userSetting);
        }
    }
   
    // set user setting if user existed and user settting existed
    private function getUserIdFromSession() {
        if (isset($_SESSION['signedUser'])) {
            return $_SESSION['signedUser'];
            //self::setUserSettingInSessionById($_SESSION['signedUser']);
        } else {
            $cookieValue = isset($_COOKIE["signedUser"])? EncryptHelper::decrypt($_COOKIE["signedUser"]) : "";
            if (isset($cookieValue) && !empty($cookieValue) && !is_null($cookieValue)){
                return $cookieValue;
                //self::setUserSettingInSessionById($cookieValue);
            }
            return null;
        }
    }

    private function setUserSettingInSessionById($id) {
        $userSettingsDao = new UserSettingsDao();
        $userSetting=$userSettingsDao->getUserSettingByUserId($id);
        if (isset($userSetting) && !empty($userSetting)){
            $_SESSION['userSetting'] = $userSetting;
            //setcookie("userSetting", seralize($userSetting), time()+7*24*3600);
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",Saved User Setting for ".$id);
        }
        
        $cookieValue = EncryptHelper::encrypt($id);
        $_SESSION['signedUser'] = $id;
        setcookie("signedUser", $cookieValue, time()+7*24*3600);
    }

    /**
     * Get user by signedUser in session
     */
    public function getCurrentUserInfo() {
        $userid = $_SESSION['signedUser'];
        $userInfosDao = new UserInfosDao();
        $existedUser = $userInfosDao ->getByKv('user_id', $userid);
        $_SESSION['signedUserInfo'] = $existedUser;
    }

    /**
     * Update User info
     */
    public function createOrUpdateUserInfo() {
        $userData['name'] = isset($_POST ['name']) ? trim($_POST ['name']) : '';
        $userData['signature'] = isset($_POST ['signature']) ? trim($_POST ['signature']) : '';
        $userData['weixin'] = isset($_POST ['weixin']) ? trim($_POST ['weixin']) : '';
        $userData['gender'] = $_POST ['gender'];
        $userData['email'] = isset($_POST ['email']) ? trim($_POST ['email']) : '';
        $userData['description'] = isset($_POST ['description']) ? trim($_POST ['description']) : '';
        $userData['tag'] = (isset($_POST ['mytags']) && $_POST ['mytags']!='') ? trim($_POST ['mytags']) : ' ';

        $userDao = new UserInfosDao();
        $userid = $_SESSION['signedUser'];
        try {
            $exist = $userDao->isExistByUid('user_id', $userid);
            if ($exist) {
                $userDao->updateByKv($userData, 'user_id', $userid);
            } else {
                $userData['user_id'] = $userid;
                $userDao->insert($userData);
            }
            // need to check account, go to next link
            if (isset($_SESSION['nextGotoLink'])) {
                $sellerid = $_SESSION['nextGotoLink'];
                unset($_SESSION['nextGotoLink']);

                header('Location:../Controller/AuthUserDispatcher.php?c=getSellerPayInfo&userid=' . $sellerid);
            } else {
                header('Location:../Controller/AuthUserDispatcher.php?c=mine');
            }
            exit;
        }  catch (\Exception $e){
            $_SESSION['status'] = 'f';
            $_SESSION['message'] = $userData['name'].'提交个人信息失败!';
            $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=mine";
        }
    }

    /**
     * 头像处理
     */
    public function handleHeads() {
        $userID = $_SESSION['signedUser'];
        $object = "yzphoto/heads/".$userID."/head.png";
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",Userid=".$userID);
        $crop = new CropAvatar( $userID,
            isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
            isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
            isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null
        );
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",msg=".$crop -> getMsg());
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",result=".$crop -> getResult());
        if (is_null($crop -> getMsg())
            && !is_null($crop -> getResult()) && file_exists($crop -> getResult())) {
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",Uploading to OSS " . $object);
            OSSHelper::uploadFile($object, $crop -> getResult(), array());
        }

        $response = array(
            'status'  => 200,
            'msg' => $crop -> getMsg(),
            'result' => $object
        );
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",response=".json_encode($response));
        echo json_encode($response);

        exit;

    }

    /**
     * prepare some info for realname certification
     */
    public function prepareRealName(){
        $userId = HttpHelper::getVale('userid');
        $signedUserID = $_SESSION['signedUser'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",signedUserId=".$signedUserID.",userid=".$userId);

        self::getCurrentUserInfo() ;
        WeixinHelper::prepareWeixinPicsParameters("/weiphp/Addons/OverSea/View/mobile/users/realname.php");
        self::getRealNamePictures($userId);
    }

    /*
    * get real name pictures
    */
    private function getRealNamePictures($userId) {
        unset($_SESSION['objArray']);

        // list data
        $object = "yzphoto/realname/".$userId."/";
        //echo $object;
        $objectList = OSSHelper::listObjects($object);
        $objArray = array();
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                $objArray[] = $objectInfo->getKey();
            }
            $retObjArray =  json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",ret=".$retObjArray);
            $_SESSION['objArray'] = $objArray;
        }
    }

    /**
     * YZ 图片处理
     */
    public function publishRealNamePics() {
        $userID = $_SESSION['signedUser'];

        Logs::writeClcLog(__CLASS__.",".__FUNCTION__." userid=".$userID);
        // upload image if need to
        if (isset($_GET ['serverids'])){
            $serverids = $_GET ['serverids'];
            //echo $serverids;
            $serveridsArray = explode(',',$serverids);
            $i=1;
            foreach ($serveridsArray as $serverid){
                self::savePictureFromWeixin($serverid, $userID, $i);
                $i++;
            }
        }

        // delete image if need
        if (isset($_GET ['objtodelete'])){
            $obj = $_GET ['objtodelete'];
            //echo $obj;
            OSSHelper::deleteObject($obj);
            //exit(1);
        }

        // list data
        $object = "yzphoto/realname/".$userID."/";
        //echo $object;
        $objectList = OSSHelper::listObjects($object);
        $objArray = array();
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                $objArray[] = $objectInfo->getKey();
            }
        }
        //$retJson =  json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
        //Logs::writeClcLog(__CLASS__.",".__FUNCTION__." retJson=".$retJson);
        echo json_encode(array('status'=> 0, 'msg'=> 'done', 'objLists' => $objArray));
        exit;
    }

    // 获yz取图片地址
    private function savePictureFromWeixin($media_id, $userID, $i){
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $access_token = WeixinHelper::getAccessTokenWithLocalFile();
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
        $object = "yzphoto/realname/".$userID."/".date('YmdHis')."_".$i.".jpg";
        $options = array();
        OSSHelper::putObject($object, file_get_contents($url), $options);
        return ;
    }

    /**
     * User update realname info
     */
    public function publishRealNameInfo(){

        $userID = $_SESSION['signedUser'];
        $realNameData = array();
        $realNameData['status'] = 20;// change satus to waiting for approve
        $realNameData['real_name'] = isset($_POST ['real_name']) ? $_POST ['real_name'] : '';
        $realNameData['certificate_type'] = $_POST ['certificate_type'];
        $realNameData['certificate_no'] = isset($_POST ['certificate_no']) ? $_POST ['certificate_no'] : '';

        try{
            $usersDao = new UserInfosDao();
            $userid = $usersDao ->updateByKv($realNameData, 'user_id', $userID);
            header('Location:../Controller/AuthUserDispatcher.php?c=mine');
            exit;
        } catch (\Exception $e){
            $_SESSION['status'] = 'f';
            $_SESSION['message'] = '实名认证信息发布失败!';
            $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=mine";
        }

        //header('Location:../View/mobile/users/submityzsuccess.php');
    }

    /**
     * Get the pending review realname users (admin now)
     */
    public function getUsers() {
        $status = HttpHelper::getVale('status');
        $usersDao = new UserInfosDao();
        $allServices = $usersDao->getByStatus($status);
        $_SESSION['allUsers'] = $allServices;
    }

    /**
     * Admin reject or approve realname user  (admin now)
     */
    public function checkUser(){
        $userId = $_POST['userId'];
        $reason = $_POST['checkreason'];
        $action = $_POST['checkaction'];
        $status = 60;
        if ($action == 1){
            $status = 40;
        }
        $usersDao = new UserInfosDao();
        $usersDao -> checkByKv('user_id', $userId,  $reason, $status);
        self::getUsers();
    }

    // =========SMS User Get registration code, Get temppassowrd ======= //
    public function sendRegistrationPassword(){
        $mobile = '';
        if (isset($_POST['phone_reigon']) && isset($_POST['phone_number'])){
            $mobile = $_POST['phone_reigon'].$_POST['phone_number'];
            $_SESSION['verifcationCode'] = rand(1000,9999);
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",sending ".$_SESSION['verifcationCode']." to ".$mobile);
            $text="【易知海外】您的验证码是".$_SESSION['verifcationCode'];
            if ($_POST['phone_reigon'] != '+86') {
                $text="【eknowhow】Your verification code is ".$_SESSION['verifcationCode'];
            }
            $result = YunpianSMSHelper::sendSMS($text, $mobile);
            $resultJson = json_encode(array('status'=> $result['code'], 'msg'=> $result['msg'].':'.$result['detail']));
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.", sent response ".$resultJson);
            echo $resultJson;
        } else {
            echo json_encode(array('status'=> -1));
        }
        exit;
    }
    
    public function sendTempPasswordToPhone(){
        $userData = array();
        $userData['user_type'] = 1;

        if ($userData['user_type'] == 1) { // register by phone user
            if (isset($_POST ['phone_reigon'])){
                $userData['phone_reigon'] = $_POST ['phone_reigon'];
            }
            if (isset($_POST ['phone_number'])){
                $userData['phone_number'] = $_POST ['phone_number'];
            }
            if (isset($_POST ['password'] )){
                $userData['password'] = $_POST ['password'];
            }

            // verifycode to be implement
            $userDao = new UserAccountsDao();
            $existedUser = $userDao->getUserByPhone($userData['phone_reigon'] , $userData['phone_number']);
            if (!isset($existedUser['phone_number'])){
                $_SESSION['$signInErrorMsg']= $userData['phone_reigon'] . $userData['phone_number']. " 号码尚未注册.";
                header('Location:../View/mobile/users/forget_password.php');

            } else {
                $mobile = $_POST['phone_reigon'].$_POST['phone_number'];
                $_SESSION['tempCode'] = rand(100000,999999);
                Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",sending ".$_SESSION['tempCode']." to ".$mobile);
                $text="【易知海外】您的临时登陆码是".$_SESSION['tempCode'];
                if ($_POST['phone_reigon'] != '+86') {
                    $text="【eknowhow】Your temporary sign in code is ".$_SESSION['tempCode'];
                }
                $result = YunpianSMSHelper::sendSMS($text, $mobile);
                $resultJson = json_encode(array('status'=> $result['code'], 'msg'=> $result['msg'].':'.$result['detail']));
                Logs::writeClcLog(__CLASS__.",".__FUNCTION__.", sent response ".$resultJson);
                if ($result['code'] == 0) {
                    $_SESSION['existedUserPhoneReigon']= $userData['phone_reigon'];
                    $_SESSION['existedUserPhoneNumber']= $userData['phone_number'];
                    $_SESSION['$signInErrorMsg']= "临时登陆密码号已发送到手机,有效期三分钟。 请用临时登陆密码登陆系统, 并尽快用该号码执行修改密码操作.";
                    header('Location:../View/mobile/users/signin.php');
                } else {
                    $_SESSION['$signInErrorMsg']= $result['msg'].':'.$result['detail'];
                    header('Location:../View/mobile/users/forget_password.php');
                }
            }
            exit;
        }
    }

    public function changePassword(){
        $origPassword= $_POST ['orig'];
        $userData['password'] = isset($_POST ['new1']) ? trim($_POST ['new1']) : '';

        $userid = $_SESSION['signedUser'];
        $userAccountsDao = new UserAccountsDao();
        $existedUser = $userAccountsDao ->getByKv('user_id', $userid);
        if (isset($_SESSION['tempCode']) && $origPassword != $_SESSION['tempCode']) {
            $_SESSION['existedUserPhoneReigon']= $userData['phone_reigon'];
            $_SESSION['existedUserPhoneNumber']= $userData['phone_number'];
            $_SESSION['$signInErrorMsg']= $userData['phone_reigon'] . $userData['phone_number']. " 临时登陆密码错误.";
            header('Location:'."../View/mobile/users/change_password.php");
            exit;
        } else if ((!isset($_SESSION['tempCode']) && ($origPassword != $existedUser['password'])) ){
            $_SESSION['existedUserPhoneReigon']= $userData['phone_reigon'];
            $_SESSION['existedUserPhoneNumber']= $userData['phone_number'];
            $_SESSION['$signInErrorMsg'] = '原密码不正确,无法修改密码,请重试!';
            header('Location:'."../View/mobile/users/change_password.php");
            exit;
        } else {
            $ret = $userAccountsDao ->update($userData, $existedUser['id']);
            if ($ret == 0) {
                $_SESSION['status'] = 's';
                $_SESSION['message'] = "密码修改成功!";
                $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=mine";
                unset($_SESSION['tempCode'], $_SESSION['existedUser'], $_SESSION['existedUserPhoneReigon'], $_SESSION['existedUserPhoneNumber'], $_SESSION['$signInErrorMsg'] );
            } else {
                $_SESSION['status'] = 'f';
                $_SESSION['message'] = '密码修改失败!';
                $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=mine";
            }
        }
    }

    public function getSellerPayInfo(){
        $userId = HttpHelper::getVale('userid');
        $signedUserID = $_SESSION['signedUser'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",signedUserId=".$signedUserID.",userid=".$userId);

        $sellerPayAccountsDao = new SellerPayAccountsDao();
        $sellerPayAccounts = $sellerPayAccountsDao->getPayAccountsByUserId($userId);
        $_SESSION['sellerPayAccounts'] = $sellerPayAccounts;
    }

    public function updateSellerPayInfo() {
        $seller_pay_account_id = $_POST['seller_pay_account'];
        $userId = $_SESSION['signedUser'];
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userId=".$userId.",seller_pay_account_id=".$seller_pay_account_id);

        MySqlHelper::beginTransaction();
        $sellerPayAccountsDao = new SellerPayAccountsDao();
        try{
            $activeAccount = $sellerPayAccountsDao -> getPayAccountsByUserIdStatus($userId, 1);
            if (isset($activeAccount['id'])){
                if ($activeAccount['account_id'] != $seller_pay_account_id){
                    $activeAccount['status'] = 0;
                    $sellerPayAccountsDao -> setPayAccountStatusByUserIdAccountId($userId, $activeAccount['account_id'], 0);
                    $sellerPayAccountsDao -> setPayAccountStatusByUserIdAccountId($userId, $seller_pay_account_id, 1);
                }
            } else {
                $sellerPayAccountsDao -> setPayAccountStatusByUserIdAccountId($userId, $seller_pay_account_id, 1);
            }
            MySqlHelper::commit();
            header('Location:../Controller/AuthUserDispatcher.php?c=mine');
            exit;
        }  catch (\Exception $e){
            MySqlHelper::rollBack();
            $_SESSION['status'] = 'f';
            $_SESSION['message'] = '更新卖家收款账号失败!';
            $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=mine";
        }
    }
    public function submitUserSuggestion(){
        $userid = $_SESSION['signedUser'];
        $suggestion = array();
        $suggestion['user_id'] = $userid;
        $suggestion['suggestion'] = $_POST['suggestion'];
        $suggestionsDao = new SuggestionsDao();
        $suggestionsDao -> insert($suggestion);
        $_SESSION['status'] = 's';
        $_SESSION['message'] = '反馈意见提交完毕。感谢您的支持!';
        $_SESSION['goto'] = "../../../View/mobile/query/discover.php";
    }
}