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

    public static function writeMessageLog($content)
    {
        try {
            $directory = LOG_DIR;
            $date = date('Y-m-d');
            $filename = sprintf("%s/message_" . $date . ".log", $directory);
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
    public static function writeReturnLog($content)
    {
        try {
            $directory = LOG_DIR;
            $date = date('Y-m-d');
            $filename = sprintf("%s/return_" . $date . ".log", $directory);
            $fp = fopen($filename, 'a+');
            $filecontent = json_encode(date('y-m-d H:i:s',time())."," .$content);
            fwrite($fp, $filecontent . "\r\n");
            fclose($fp);
        } catch (Exception $e) {
            echo $e;

        }
    }
    public static function writeSellerPayLog($content)
    {
        try {
            $directory = LOG_DIR;
            $date = date('Y-m-d');
            $filename = sprintf("%s/pay_seller_" . $date . ".log", $directory);
            $fp = fopen($filename, 'a+');
            $filecontent = json_encode(date('y-m-d H:i:s',time())."," .$content);
            fwrite($fp, $filecontent . "\r\n");
            fclose($fp);
        } catch (Exception $e) {
            echo $e;

        }
    }
    public static function writeAPILog($content)
    {
        try {
            $directory = LOG_DIR;
            $date = date('Y-m-d');
            $filename = sprintf("%s/api_" . $date . ".log", $directory);
            $fp = fopen($filename, 'a+');
            $filecontent = json_encode(date('y-m-d H:i:s',time())."," .$content);
            fwrite($fp, $filecontent . "\r\n");
            fclose($fp);
        } catch (Exception $e) {
            echo $e;

        }
    }
}