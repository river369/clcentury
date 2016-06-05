<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/6/4
 * Time: 11:51
 */

namespace Addons\OverSea\Common;

class Logs
{
    /**
     * 程序跟踪日志
     * @param $content
     */
    public static function writeClcLog($content)
    {
        try {
            $directory = LOG_DIR;
            $date = date('Y-m-d');
            $filename = sprintf("%s/clc_" . $date . ".log", $directory);
            $fp = fopen($filename, 'a+');
            $filecontent = json_encode(date('y-m-d H:i:s',time())."," .$content);
            fwrite($fp, $filecontent . "\r\n");
            fclose($fp);
        } catch (Exception $e) {
            echo $e;

        }
    }
    public static function writePayLog($content)
    {
        try {
            $directory = LOG_DIR;
            $date = date('Y-m-d');
            $filename = sprintf("%s/pay_" . $date . ".log", $directory);
            $fp = fopen($filename, 'a+');
            $filecontent = json_encode(date('y-m-d H:i:s',time())."," .$content);
            fwrite($fp, $filecontent . "\r\n");
            fclose($fp);
        } catch (Exception $e) {
            echo $e;

        }
    }
}