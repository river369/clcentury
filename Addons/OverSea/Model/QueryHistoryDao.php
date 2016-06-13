<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/2
 * Time: 11:55
 */
namespace Addons\OverSea\Model;
use Addons\OverSea\Common\MySqlHelper;
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Model\BaseDao;

class QueryHistoryDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("yz_query_history");
    }


    public function getQueryHistoryByUserId($user_id)
    {
        try {
            $sql = 'SELECT * FROM ' . parent::getTableName(). '  WHERE user_id = :user_id order by id desc LIMIT 5';
            $parameter = array(':user_id' => $user_id);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $user = MySqlHelper::fetchAll($sql, $parameter);
            return $user;
        }catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
            exit(1);
        }

    }
}
?>