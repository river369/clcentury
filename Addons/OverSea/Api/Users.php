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


class Users extends Base
{
    /**
     * 实例化类库
     *
     * @param array $data 接收的参数数据
     *
     * @return void
     */
    public function __construct($data)
    {
        parent::__construct($data);
    }

    /**
     * Get service info
     */
    public function signIn() {
        $userType = $this->data['userType'];
        $phoneRegion = $this->data['phoneRegion'];
        $phoneNumber = $this->data['phoneNumber'];
        $password = $this->data['password'];
        if (Common::isStringEmpty($userType) ){
            Common::responseError(3001, "用户类型不能为空。");
        }
        if (Common::isStringEmpty($phoneRegion) ){
            Common::responseError(3002, "用户手机区号不能为空。");
        }
        if (Common::isStringEmpty($phoneNumber) ){
            Common::responseError(3003, "用户手机号码不能为空。");
        }
        if (Common::isStringEmpty($userType) ){
            Common::responseError(3004, "用户密码不能为空。");
        }

        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",userType=".$userType.",phoneRegion=".$phoneRegion.",phoneNumber=".$phoneNumber);

        $userData = array();
        if ($userType == 1) { // register by phone user
            // verifycode to be implement
            $userDao = new UserAccountsDao();
            $existedUser = $userDao->getUserByPhone( $phoneRegion , $phoneNumber);
            if (!isset($existedUser['phone_number'])){
                Common::responseError(3010, $phoneRegion . $phoneNumber. " 号码尚未注册.");
            } else if ($password != $existedUser['password']){
                Common::responseError(3011, $phoneRegion . $phoneNumber. " 密码错误.");
            } else {
                $token = EncryptHelper::encrypt($existedUser['user_id']);
                $response_data = array();
                $response_data['token'] = $token;
                $this->setCode("0");
                $this->setMessage("success");
                $this->setResponseData($response_data);
                $this->response();
            }
        }
    }

}