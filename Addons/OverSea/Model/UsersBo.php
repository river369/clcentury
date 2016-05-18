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

    public function getCurrentUserInfo() {
        $userid = $_SESSION['signedUser'];
        $existedUser = UsersDao::getUserById($userid);
        $_SESSION['signedUserInfo'] = $existedUser;
    }

}