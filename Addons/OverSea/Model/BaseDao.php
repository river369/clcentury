<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/6/9
 * Time: 13:27
 */
namespace Addons\OverSea\Model;
use Addons\OverSea\Common\MySqlHelper;
use Addons\OverSea\Common\Logs;

class BaseDao
{

    public $talbeName;
    /**
     * BaseDao constructor.
     */
    public function __construct($table)
    {
        $this->talbeName = $table;
    }

    public function insert($data)
    {
        try {
            date_default_timezone_set('PRC');
            $data['creation_date'] = date('y-m-d H:i:s', time());
            $tmpData = array();
            foreach ($data as $k => $v) {
                //echo $k."-".$v;
                $tmpData[':' . $k] = $v;
            }
            $sql = 'INSERT INTO '. $this->talbeName .' (' . implode(',', array_keys($data)) . ') VALUES (' . implode(',', array_keys($tmpData)) . ')';
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . "sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . "parameters=".json_encode($tmpData));
            MySqlHelper::query($sql, $tmpData);
        } catch (\Exception $e) {
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . $e);
        }
        return MySqlHelper::getLastInsertId();
    }

    public function update($data, $id)
    {
        try {
            date_default_timezone_set('PRC');
            $data['update_date'] = date('y-m-d H:i:s', time());
            $sql = "update ". $this->talbeName ." set ";
            foreach ($data as $k => $v) {
                //echo $k."-".$v;
                if ($v != null && $v != '') {
                    $sql = $sql . $k . "='" . $v . "',";
                }
            }
            $sql = substr($sql, 0, strlen($sql) - 1);
            $sql = $sql . ' where id =:id';
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . "sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . "parameters=".json_encode(array(':id' => $id)));
            MySqlHelper::query($sql, array(':id' => $id));
            return 0;
        } catch (\Exception $e) {
            return -1;
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . $e);
        }
    }

    public function getById($id)
    {
        try {
            $sql = 'SELECT * FROM '. $this->talbeName. ' WHERE id= :id LIMIT 1';
            //echo $sql;
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . "sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . "parameters=".json_encode(array(':id' => $id)));
            $user = MySqlHelper::fetchOne($sql, array(':id' => $id));
            return $user;
        }catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
            exit(1);
        }

    }
}