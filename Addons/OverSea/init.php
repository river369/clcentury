<?php
use Addons\OverSea\Common\MySqlHelper;
use Addons\OverSea\Common\WeixinHelper;
use Addons\OverSea\Common\YunpianSMSHelper;
use Addons\OverSea\Common\OSSHelper;
use Addons\OverSea\Common\EncryptHelper;
use Addons\OverSea\Common\Logs;

function initLoad()
{
    include_once dirname(__FILE__).'/Common/MySqlHelper.php';
    include_once dirname(__FILE__).'/Common/WeixinHelper.php';
    include_once dirname(__FILE__).'/Common/YunpianSMSHelper.php';
    include_once dirname(__FILE__).'/Common/OSSHelper.php';
    include_once dirname(__FILE__).'/Common/EncryptHelper.php';
    include_once dirname(__FILE__).'/Common/HttpHelper.php';
    include_once dirname(__FILE__).'/Common/Logs.php';
    include_once dirname(__FILE__). '/Model/UsersDao.php';
    include_once dirname(__FILE__). '/Model/OrdersDao.php';
    include_once dirname(__FILE__). '/Model/OrderActionsDao.php';
    include_once dirname(__FILE__). '/Model/PaymentsDao.php';
    include_once dirname(__FILE__). '/Model/UsersBo.php';
    include_once dirname(__FILE__). '/Model/OrdersBo.php';
    include_once dirname(__FILE__). '/Model/YZPicBo.php';
    return true;
}

spl_autoload_register("initLoad");

ini_set("error_reprorting", "E_ALL");
ini_set("display_errors", "Off");
ini_set("log_errors", "On");
ini_set("error_log", "/home/www/logs/clc.log");

/* 取得当前ecshop所在的根目录 */
define('ROOT_PATH', dirname(__DIR__));
// 定义项目基本配置信息
define('CONFIG_PATH', dirname(__DIR__));

/* 初始化设置 */
@ini_set('memory_limit', '1024M');
$config_file = CONFIG_PATH . DIRECTORY_SEPARATOR .'OverSea'. DIRECTORY_SEPARATOR . 'config.php';
if (file_exists($config_file) == false) {
    die('Server Config Not Found!');
}
//引入配置文件
require($config_file);

/* 初始化数据库类 */

$dbhost = explode(':', $db_host);
MySqlHelper::initData($dbhost[0], $dbhost[1], $db_user, $db_pass, $db_name);
unset($db_host, $db_user, $db_pass, $db_name);

/* 初始化weixin */
//echo $appid." ".$secret;

WeixinHelper::initData($appid, $secret);
unset($appid, $secret);

YunpianSMSHelper::initData($yunpianappid,$yunpianappid);
unset($yunpianappid, $yunpianappid);


OSSHelper::initData($accessKeyId, $accessKeySecret, $endpoint, $bucket);
unset($accessKeyId, $accessKeySecret, $endpoint, $bucket);