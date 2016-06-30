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

class CitiesTagDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("yz_city_tags");
    }

    public function getTagsByCityBusinessType($city_name, $service_type)
    {
        try {
           $sql = 'SELECT tag FROM ' . parent::getTableName(). ' WHERE city_name= :city_name and service_type= :service_type';
            $parameter = array(':city_name' => $city_name, ':service_type' => $service_type);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $ret = MySqlHelper::fetchAll($sql, $parameter);
            return $ret;
        } catch (\Exception $e){
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . $e);
            exit;
        }
        
    }
}
?>