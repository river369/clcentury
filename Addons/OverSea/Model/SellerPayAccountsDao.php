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

class SellerPayAccountsDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("yz_seller_pay_accounts");
    }

    public function insertOrUpdateSellerPayAccount($sellerPayAccounts){
        $id = self::isExistedByUidAccount($sellerPayAccounts['user_id'],$sellerPayAccounts['account_type'],$sellerPayAccounts['account_id']);
        if ($id){
            parent::update($sellerPayAccounts, $id);
        } else {
            parent::insert($sellerPayAccounts);
        }
    }

    public function isExistedByUidAccount($user_id, $account_type, $account_id){
        try {
            $sql = 'SELECT id FROM '. $this->talbeName . ' WHERE user_id =:user_id AND account_type = :account_type AND account_id =:account_id LIMIT 1';
            $parameter = array(':user_id' => $user_id, ':account_type' => $account_type, ':account_id' => $account_id);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $user = MySqlHelper::fetchOne($sql, $parameter);
            return isset($user['id']) ? $user['id'] : 0;
        }catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
            throw $e;
        }
    }

    public function getPayAccountsByUserId($user_id){
        try {
            $sql = 'SELECT * FROM '. $this->talbeName . ' WHERE user_id =:user_id order by update_date desc';
            $parameter = array(':user_id' => $user_id);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $accounts = MySqlHelper::fetchAll($sql, $parameter);
            return $accounts;
        }catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
            throw $e;
        }
    }

    public function getPayAccountsByUserIdStatus($user_id, $status){
        try {
            $sql = 'SELECT * FROM '. $this->talbeName . ' WHERE user_id =:user_id and status = :status LIMIT 1';
            $parameter = array(':user_id' => $user_id, ":status" => $status);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $account = MySqlHelper::fetchOne($sql, $parameter);
            return $account;
        }catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
            throw $e;
        }
    }

    public function setPayAccountStatusByUserIdAccountId($user_id, $account_id, $status){
        try {
            $sql = 'UPDATE '. $this->talbeName . ' SET status = ' .$status. ' WHERE user_id =:user_id and account_id = :account_id';
            $parameter = array(':user_id' => $user_id, ":account_id" => $account_id);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $account = MySqlHelper::query($sql, $parameter);
            return $account;
        }catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
            throw $e;
        }
    }
}
?>