<?php

/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/12/6
 * Time: 21:42
 */
namespace Addons\OverSea\Api;
use Addons\OverSea\Api\Base;
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Model\UserInfosDao;
use Addons\OverSea\Model\UserAccountsDao;
use Addons\OverSea\Common\EncryptHelper;


class UsersBo
{
    public function getCurrentSellerInfo($sellerid) {
        $userInfoDao = new UserInfosDao();
        $sellerData = $userInfoDao ->getByKv('user_id', $sellerid);
        return $sellerData;
    }

}