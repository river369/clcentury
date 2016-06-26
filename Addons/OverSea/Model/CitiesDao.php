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


class CitiesDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("yz_cities");
    }

    public function getAllCities()
    {
        try {
            $sql = 'SELECT co.country_name, co.display_sequence, ci.city_name, ci.first_char_pinyin FROM '
                . parent::getTableName(). ' ci inner join yz_countries co on ci.country_id = co.id '
                . ' order by co.display_sequence, ci.first_char_pinyin';
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            $cities = MySqlHelper::fetchAll($sql);
            return $cities;
        } catch (\Exception $e){
            echo $e;
            exit;
        }
    }
}
?>