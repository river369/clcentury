<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/6/6
 * Time: 08:12
 */


namespace Addons\OverSea\Common;

class HttpHelper {
    public static function saveServerQueryStringVales($text) {
        if (isset($text) && !is_null($text) && (strlen($text)>0)){
            $output = array();
            parse_str($text, $output);
            $_SESSION['QUERY_STRING_ARRAY'] = $output;
        }
    }

    public static function getVale($text) {
        $output = $_SESSION['QUERY_STRING_ARRAY'];
        if (isset($output [$text])){
            return $_GET [$text];
        } else {
           return null;
        }
    }
}