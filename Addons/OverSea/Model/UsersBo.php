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

class UsersBo
{
    public function __construct() {
    }
    
    public function getCurrentUserInfo() {
        $userid = $_SESSION['signedUser'];
        $existedUser = UsersDao::getUserById($userid);
        $_SESSION['signedUserInfo'] = $existedUser;
    }
    
    public function getCurrentSellerInfo() {
        unset($_SESSION['sellerData']);

        $sellerid = HttpHelper::getVale('sellerid');

        $sellerData = UsersDao::getUserById($sellerid);
        $_SESSION['sellerData']= $sellerData;
    }

    public function getCurrentSellerInfoAndPictures() {
        unset($_SESSION['sellerObjArray']);

        $sellerid = HttpHelper::getVale('sellerid');
        self::getCurrentSellerInfo();
        
        // list data
        $object = "yzphoto/pics/".$sellerid."/";
        //echo $object;
        $objectList = OSSHelper::listObjects($object);
        $objArray = array();
        if (!empty($objectList)) {
            foreach ($objectList as $objectInfo) {
                $objArray[] = $objectInfo->getKey();
            }

            $_SESSION['sellerObjArray'] = $objArray;
        }
    }


}