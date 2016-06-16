<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/18
 * Time: 10:08
 */

namespace Addons\OverSea\Model;

use Addons\OverSea\Model\UsersDao;
use Addons\OverSea\Common\OSSHelper;
use Addons\OverSea\Common\HttpHelper;
use Addons\OverSea\Common\WeixinHelper;
use Addons\OverSea\Common\Logs;

class UsersBo
{
    public function __construct() {
    }

    /**
     * Get user by signedUser in session
     */
    public function getCurrentUserInfo() {
        $userid = $_SESSION['signedUser'];
        $userDao = new UsersDao();
        $existedUser = $userDao ->getById($userid);
        $_SESSION['signedUserInfo'] = $existedUser;
    }
    
    /**
     * Update User info
     */
    public function updateUserInfo() {
        $userData['name'] = isset($_POST ['name']) ? trim($_POST ['name']) : '';
        $userData['weixin'] = isset($_POST ['weixin']) ? trim($_POST ['weixin']) : '';
        $userData['gender'] = $_POST ['gender'];
        $userData['email'] = isset($_POST ['email']) ? trim($_POST ['email']) : '';
        $userData['description'] = isset($_POST ['description']) ? trim($_POST ['description']) : '';

        if (isset( $_POST ['mytags'])){
            $userData['tag'] = $_POST ['mytags'];
        }
        $userDao = new UsersDao();
        $userid = $userDao ->update($userData,$_SESSION['signedUser']);
        if ($userid == 0) {
            $_SESSION['status'] = 's';
            $_SESSION['message'] = $userData['name'].'提交个人信息成功,谢谢!';
            $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=mine";
        } else {
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
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",Userid=".$userID);
        $crop = new CropAvatar( $userID,
            isset($_POST['avatar_src']) ? $_POST['avatar_src'] : null,
            isset($_POST['avatar_data']) ? $_POST['avatar_data'] : null,
            isset($_FILES['avatar_file']) ? $_FILES['avatar_file'] : null
        );
        if (is_null($crop -> getMsg())
            && !is_null($crop -> getResult()) && file_exists($crop -> getResult())) {
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",Uploading to OSS");
            self::savePictureFromFile($crop -> getResult(), $userID);
        }

        $response = array(
            'status'  => 200,
            'msg' => $crop -> getMsg(),
            'result' => $crop -> getResult()
        );
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",msg=".$crop -> getMsg());
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",result=".$crop -> getResult());
        echo json_encode($response);

        exit;

    }

    private function savePictureFromFile($path, $userID){
        $object = "yzphoto/heads/".$userID."/head.png";
        $options = array();
        OSSHelper::uploadFile($object, $path, $options);
        return ;
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

        $usersDao = new UsersDao();
        $userid = $usersDao ->update($realNameData, $userID);

        if ($userid==0) {
            $_SESSION['status'] = 's';
            $_SESSION['message'] = '实名认证信息发布成功,谢谢!';
            $_SESSION['goto'] = "../../../Controller/AuthUserDispatcher.php?c=mine";
        } else {
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
        $usersDao = new UsersDao();
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
        $usersDao = new UsersDao();
        $usersDao -> check($userId,  $reason, $status);
        self::getUsers();
    }


}