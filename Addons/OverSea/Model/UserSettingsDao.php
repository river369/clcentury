<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/2
 * Time: 11:55
 */
namespace Addons\OverSea\Model;
use Addons\OverSea\Common\MySqlHelper;
use Addons\OverSea\Model\BaseDao;
use Addons\OverSea\Common\Logs;

class UserSettingsDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("yz_user_settings");
    }

    public function insertOrUpdateUserSetting($userSetting){
        $existedUserSetting = $this->getUserSettingByUserId($userSetting['user_id']);
        if (empty($existedUserSetting)){
            parent::insert($userSetting);
        } else {
            parent::update($userSetting, $existedUserSetting['id']);
        }
    }

    public function getUserSettingByUserId($user_id)
    {
        try {
            $sql = 'SELECT * FROM ' . parent::getTableName(). ' WHERE user_id= :user_id';
            $parameter = array(':user_id' => $user_id);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $userSetting = MySqlHelper::fetchOne($sql, $parameter);
            return $userSetting;
        } catch (\Exception $e){
            echo $e;
            exit;
        }
    }
}
?>