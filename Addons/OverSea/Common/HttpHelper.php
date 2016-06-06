<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/6/6
 * Time: 08:12
 */


namespace Addons\OverSea\Common;
use Addons\OverSea\Common\Logs;

class HttpHelper {
    public static function saveServerQueryStringVales($text) {
        if (isset($text) && !is_null($text) && (strlen($text)>0)){
            $output = array();
            parse_str($text, $output);
            $_SESSION['QUERY_STRING_ARRAY'] = $output;
            Logs::writeClcLog(__CLASS__.",".__FUNCTION__.",QUERY_STRING_ARRAY=".$text);
        }
    }

    public static function getVale($key) {
        $output = $_SESSION['QUERY_STRING_ARRAY'];
        if (isset($output [$key])){
            return $output [$key];
        } else {
           return null;
        }
    }
}