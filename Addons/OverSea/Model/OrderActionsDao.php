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

class OrderActionsDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("yz_order_actions");
    }

    public function getOrderActionsByOrderId($order_id)
    {
        $sql = 'SELECT * FROM yz_order_actions WHERE order_id= :order_id order by creation_date asc' ;
        $parameter =  array(':order_id' => $order_id);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
        $orderActions = MySqlHelper::fetchAll($sql, $parameter);
        return $orderActions;

    }
}
?>