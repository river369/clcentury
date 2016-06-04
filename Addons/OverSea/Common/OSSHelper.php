<?php
namespace Addons\OverSea\Common;
if (is_file(__DIR__ . '/../lib/aliyun-oss-php-sdk/autoload.php')) {
    require_once __DIR__ . '/../lib/aliyun-oss-php-sdk/autoload.php';
}

use OSS\OssClient;
use Addons\OverSea\Common\Logs;


class OSSHelper
{
    private static $accessKeyId;
    private static $accessKeySecret ;
    private static $endpoint ;
    private static $bucket ;
    private static $ossClient;

    public static function initData($accessKeyId, $accessKeySecret, $endpoint, $bucket ) {
        self::$accessKeyId = $accessKeyId;
        self::$accessKeySecret = $accessKeySecret;
        self::$endpoint = $endpoint;
        self::$bucket = $bucket;
    }
    /**
     * 根据Config配置，得到一个OssClient实例
     *
     * @return OssClient 一个OssClient实例
     */
    public static function getOssClient()
    {
        try {
            $ossClient = new OssClient(self::$accessKeyId, self::$accessKeySecret, self::$endpoint, false);
        } catch (OssException $e) {
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":"."creating OssClient instance: FAILED\n".$e->getMessage());
            return null;
        }
        return $ossClient;
    }

    /**
     * 把本地变量的内容到文件
     *
     * 简单上传,上传指定变量的内存值作为object的内容
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function putObject($object,$content, $option)
    {
        $ossClient = self::getOssClient();
        try {
            $ossClient->putObject(self::$bucket, $object, $content, $option);
        } catch (OssException $e) {
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":".$e->getMessage());
            return;
        }
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":OK");
    }
    
    /**
     * 上传指定的本地文件内容
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function uploadFile($object, $path, $option)
    {
        $ossClient = self::getOssClient();
        try {
            $ossClient->uploadFile(self::$bucket, $object, $path, $option);
        } catch (OssException $e) {
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":".$e->getMessage());
            return;
        }
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":OK");
    }
    
    /**
     * 删除object
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function deleteObject($object)
    {
        $ossClient = self::getOssClient();
        try {
            $ossClient->deleteObject(self::$bucket, $object);
        } catch (OssException $e) {
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":".$e->getMessage());
            return;
        }
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":OK");
    }

    /**
     * 列出Bucket内所有目录和文件, 注意如果符合条件的文件数目超过设置的max-keys， 用户需要使用返回的nextMarker作为入参，通过
     * 循环调用ListObjects得到所有的文件，具体操作见下面的 listAllObjects 示例
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function listObjects($prefix)
    {
        $ossClient = self::getOssClient();
        $delimiter = '/';
        $nextMarker = '';
        $maxkeys = 1000;
        $options = array(
            'delimiter' => $delimiter,
            'prefix' => $prefix,
            'max-keys' => $maxkeys,
            'marker' => $nextMarker,
        );
        try {
            $listObjectInfo = $ossClient->listObjects(self::$bucket, $options);
        } catch (OssException $e) {
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":".$e->getMessage());
            exit(1);
            //return;
        }
        Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":OK");
        $objectList = $listObjectInfo->getObjectList(); // 文件列表
        return $objectList;
        /*
        $prefixList = $listObjectInfo->getPrefixList(); // 目录列表
        if (!empty($objectList)) {
            print("objectList:\n");
            foreach ($objectList as $objectInfo) {
                print($objectInfo->getKey() . "\n");
            }
        }
        if (!empty($prefixList)) {
            print("prefixList: \n");
            foreach ($prefixList as $prefixInfo) {
                print($prefixInfo->getPrefix() . "\n");
            }
        }
        */

    }

}
