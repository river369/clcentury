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

class PaymentsDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("yz_payments");
    }
}
?>