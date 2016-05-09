<?php

namespace Addons\OverSea\Common;
use PDO;

/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/4/29
 * Time: 20:34
 */

class Mysql
{
    /**
     * @var \PDO PDO对象
     */
    private $dbLink = null;

    /**
     * 实例化数据库
     *
     * @param string $dbhost  MYSQL主机地址
     * @param string $dbport  MYSQL主机端口
     * @param string $dbuser  MYSQL用户
     * @param string $dbpass  MYSQL密码
     * @param string $dbname  MYSQL数据库
     * @param string $charset 数据库编码
     *
     * @return void
     */
    public function __construct($dbhost, $dbport, $dbuser, $dbpass, $dbname, $charset = 'utf8')
    {
        if ($this->dbLink instanceof PDO == false) {
            $dsn = "mysql:host={$dbhost};port={$dbport};dbname={$dbname}";
            //echo $dsn;
            /*
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset}",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );
            */
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset}",
                PDO::ATTR_CASE              =>  PDO::CASE_LOWER,
                PDO::ATTR_ERRMODE           =>  PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_ORACLE_NULLS      =>  PDO::NULL_NATURAL,
                PDO::ATTR_STRINGIFY_FETCHES =>  false,
            );
            try{
                $this->dbLink = new PDO($dsn, $dbuser, $dbpass, $options);
            }catch (\PDOException $e) {
               echo $e->getMessage();
            }
        }
    }

    /**
     * 获取单条数据
     *
     * @param string $sql              执行的sql语句
     * @param array  $input_parameters 预处理数据
     * @param int    $fetch_style      数据检索出来的数据格式
     *
     * @return array
     */
    public function fetchOne($sql, $input_parameters = array(), $fetch_style = PDO::FETCH_ASSOC)
    {
        $statement = $this->query($sql, $input_parameters);
        $data = $statement->fetch($fetch_style);
        $statement->closeCursor();
        return $data;
    }

    /**
     * 获取多条数据
     *
     * @param string $sql              执行的sql语句
     * @param array  $input_parameters 预处理数据
     * @param int    $fetch_style      数据检索出来的数据格式
     *
     * @return array
     */
    public function fetchAll($sql, $input_parameters = array(), $fetch_style = \PDO::FETCH_ASSOC)
    {
        $statement = $this->query($sql, $input_parameters);
        $data = $statement->fetchAll($fetch_style);
        $statement->closeCursor();

        return $data;
    }

    /**
     * 返回最后插入数据的主键
     *
     * @return int
     */
    public function getLastInsertId()
    {
        return $this->dbLink->lastInsertId();
    }

    /**
     * 执行SQL语句
     *
     * @param string $sql              执行的SQL语句
     * @param array  $input_parameters 数据参数
     *
     * @return \PDOStatement
     */
    public function query($sql, $input_parameters = array())
    {
        $statement = $this->dbLink->prepare($sql);
        $statement->execute($input_parameters);
        return $statement;
    }

    /**
     * 开启事务处理
     *
     * @return bool true/false
     */
    public function beginTransaction()
    {
        return $this->dbLink->beginTransaction();
    }

    /**
     * 事物回滚
     *
     * @return bool true/false
     */
    public function rollBack()
    {
        return $this->dbLink->rollBack();
    }

    /**
     * 开启事务后进行提交
     *
     * @return bool true/false
     */
    public function commit()
    {
        return $this->dbLink->commit();
    }

    /**
     * 返回PDO对象
     *
     * @return \PDO
     */
    public function getDbLink()
    {
        return $this->dbLink;
    }

    /**
     * 关闭连接
     *
     * @return void
     */
    public function close()
    {
        $this->dbLink = null;
    }

    /**
     * 析构函数
     *
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }
}
