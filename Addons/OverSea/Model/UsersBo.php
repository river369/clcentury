<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/18
 * Time: 10:08
 */

namespace Addons\OverSea\Model;

use Addons\OverSea\Model\UsersDao;

class UsersBo
{
    public function __construct() {
    }

    /**
     * Get user by signedUser in session
     */
    public function getCurrentUserInfo() {
        $userid = $_SESSION['signedUser'];
        $existedUser = UsersDao::getUserById($userid);
        $_SESSION['signedUserInfo'] = $existedUser;
    }
    
    /**
     * Update User info
     */
    public function updateUserInfo() {
        $userData['name'] = isset($_POST ['name']) ? $_POST ['name'] : '';
        $userData['weixin'] = isset($_POST ['weixin']) ? $_POST ['weixin'] : '';
        $userData['gender'] = $_POST ['gender'];
        $userData['email'] = isset($_POST ['email']) ? $_POST ['email'] : '';
        $userData['description'] = isset($_POST ['description']) ? trim($_POST ['description']) : '';

        if (isset( $_POST ['mytags'])){
            $userData['tag'] = $_POST ['mytags'];
        }
        if (UsersDao::updateUser($userData,$_SESSION['signedUser'])==0) {
            $_SESSION['submityzstatus'] = '成功';
        } else {
            $_SESSION['submityzstatus'] = '失败';
        }
        $_SESSION['userData']= $userData;

    }


}