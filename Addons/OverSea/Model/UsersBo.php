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
        $userid = $userDao ->update($userData,$_SESSION['signedUser'])==0;
        if ($userid) {
            $_SESSION['submityzstatus'] = '成功';
        } else {
            $_SESSION['submityzstatus'] = '失败';
        }
        $_SESSION['userData']= $userData;
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
    function savePictureFromFile($path, $userID){
        $object = "yzphoto/heads/".$userID."/head.png";
        $options = array();
        OSSHelper::uploadFile($object, $path, $options);
        return ;
    }


}