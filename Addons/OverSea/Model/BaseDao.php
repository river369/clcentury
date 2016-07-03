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
    
    public function getTableName(){
        return $this->talbeName; 
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
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($tmpData));
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
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode(array(':id' => $id)));
            MySqlHelper::query($sql, array(':id' => $id));
            return 0;
        } catch (\Exception $e) {
            return -1;
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . $e);
        }
    }

    public function updateByKv($data, $key, $value)
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
            $sql = $sql . ' where '.$key.'=:' .$key;
            $parameter = array(':'.$key => $value);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            MySqlHelper::query($sql, $parameter);
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
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode(array(':id' => $id)));
            $user = MySqlHelper::fetchOne($sql, array(':id' => $id));
            return $user;
        }catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
            exit(1);
        }
    }

    public function getByKv($key, $value)
    {
        try {
            $sql = 'SELECT * FROM '. $this->talbeName. ' WHERE '.$key.'= :'.$key.' LIMIT 1';
            $parameter = array(':'.$key => $value);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $user = MySqlHelper::fetchOne($sql, $parameter);
            return $user;
        }catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
            exit(1);
        }
    }

    public function isExistByUid($key, $value){
        try {
            $sql = 'SELECT id FROM '. $this->talbeName . ' WHERE ' .$key .'=:' .$key.' LIMIT 1';
            $parameter = array(':'.$key => $value);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            $user = MySqlHelper::fetchOne($sql, $parameter);
            return isset($user['id']) ? $user['id'] : 0;
        }catch (\Exception $e){
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.$e);
            exit(1);
        }
    }


    //The following 2 functions is fit for the table with satus and check_reason, not so common, still need a new class above base.

    public function getByStatus($status)
    {
        $sql = 'SELECT * FROM '. $this->talbeName .' WHERE status = :status order by creation_date desc';
        $parameter = array(':status' => $status);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
        Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
        $services = MySqlHelper::fetchAll($sql, $parameter);
        return $services;
    }

    
    public function checkByKv($key, $value,  $check_reason, $status)
    {
        try {
            $sql = "update ". $this->talbeName. " set status = :status, check_reason = :check_reason where ".$key."=:".$key;
            //echo $sql
            $parameter =  array(':check_reason' => $check_reason, ':'.$key => $value, ':status' => $status);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            MySqlHelper::query($sql,$parameter);
            return 0;
        } catch (\Exception $e){
            return -1;
            echo $e;
        }
    }
    // To be deprecated??????check(..)???
    public function check($id,  $check_reason, $status)
    {
        try {
            $sql = "update ". $this->talbeName. " set status = :status, check_reason = :check_reason where id =:id ";
            //echo $sql
            $parameter =  array(':check_reason' => $check_reason, ':id' => $id, ':status' => $status);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",sql=".$sql);
            Logs::writeClcLog(__CLASS__ . "," . __FUNCTION__ . ",parameters=".json_encode($parameter));
            MySqlHelper::query($sql,$parameter);
            return 0;
        } catch (\Exception $e){
            return -1;
            echo $e;
        }
    }
}