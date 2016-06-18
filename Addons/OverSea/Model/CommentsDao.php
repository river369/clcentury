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

class CommentsDao extends BaseDao
{
    /**
     * OrdersDao constructor.
     */
    public function __construct()
    {
        parent::__construct("yz_comments");
    }

    public function getCommentsBySellerId($sellerId)
    {
        try {
            $sql = 'SELECT * FROM ' . parent::getTableName(). ' WHERE seller_id= :seller_id';
            $parameter = array(':seller_id' => $sellerId);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $comments = MySqlHelper::fetchAll($sql, $parameter);
            return $comments;
        } catch (\Exception $e){
            echo $e;
            exit;
        }
    }

    public function getCommentsByOrderId($orderId)
    {
        try {
            $sql = 'SELECT * FROM ' . parent::getTableName(). ' WHERE order_id= :order_id';
            $parameter = array(':order_id' => $orderId);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $comments = MySqlHelper::fetchAll($sql, $parameter);
            return $comments;
        } catch (\Exception $e){
            echo $e;
            exit;
        }
    }
}
?>