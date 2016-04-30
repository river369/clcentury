<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/4/29
 * Time: 20:34
 */
namespace Common;
require dirname(__FILE__).'/Mysql.php';

class MySqlHelper
{
    /**
     * @var string 数据库主机地址
     */
    private static $dbhost;

    /**
     * @var string 数据库端口
     */
    private static $dbport;

    /**
     * @var string 数据库用户
     */
    private static $dbuser;

    /**
     * @var string 数据库密码
     */
    private static $dbpass;

    /**
     * @var string 数据库
     */
    private static $dbname;

    /**
     * 实例化 Mysql
     *
     * @var Mysql
     */
    private static $link = null;

    /**
     * 获取数据类库对象
     *
     * @return Mysql
     */
    private static function getLink()
    {
        echo "[".self::$link."]";
        if (self::$link instanceof Mysql == false) {
            self::$link = new Mysql(self::$dbhost, self::$dbport, self::$dbuser, self::$dbpass, self::$dbname, DB_CHARSET);
            //echo "[".self::$link."]";
        }
        return self::$link;
    }

    /**
     * 初始化数据
     * 
     * @param string $dbhost 主机地址
     * @param string $dbport 端口
     * @param string $dbuser 用户
     * @param string $dbpass 密码
     * @param string $dbname 数据库
     * 
     * @return void
     */
    public static function initData($dbhost, $dbport, $dbuser, $dbpass, $dbname)
    {
        self::$dbhost = $dbhost;
        self::$dbport = $dbport;
        self::$dbuser = $dbuser;
        self::$dbpass = $dbpass;
        self::$dbname = $dbname;
    }

    /**
     * 静态魔术方法
     *
     * @param string $name 调用的方法名
     * @param string $args 方法的参数
     *
     * @return void
     */
    public static function __callStatic($name, $args)
    {
        //echo self::$link.$name.$args;
        $callback = array(
            self::getLink(),
            $name
        );
        return call_user_func_array($callback, $args);
    }
}
