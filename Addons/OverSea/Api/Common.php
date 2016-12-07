<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/12/7
 * Time: 21:53
 */
namespace Addons\OverSea\Api;

class Common
{
    public static function response($result, $compress = null)
    {
        $data = "";
        if (is_array($result)) {
            if (isset($result['data'])) {
                $results = array('code' => $result['code'], 'msg' => $result['msg'], 'data' => $result['data']);
            } else {
                $results = array('code' => $result['code'], 'msg' => $result['msg']);
            }
            $data = self::jsonEncode($results);
        } elseif (is_string($result)) {
            $data= $result;
        }
        if ($compress) {
            header("Content-Encoding: gzip");
            $data = gzencode($data, 9, FORCE_GZIP);
        }
        echo $data;
        exit(0);
    }

    public static function responseError($code, $msg) {
        Common::response('{"response":{"code":"'.$code.'","msg":"'.$msg.'"}}');
    }
    
    public static function jsonEncode($str)
    {
        return json_encode($str, JSON_UNESCAPED_UNICODE);
    }
    
    
}