<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/5/30
 * Time: 22:57
 */
namespace Addons\OverSea\Common;

class EncryptHelper {
    public static function encrypt($text) {
        return base64_encode(time()."_".$text."_".mt_rand(1, 99999999));
    }

    public static function decrypt($text) {
        $keys = base64_decode($text);
        $arr = explode('_',$keys);
        return $arr[1];
    }
}