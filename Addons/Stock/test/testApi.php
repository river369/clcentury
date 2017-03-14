<?php
/**
 * Created by PhpStorm.
 * User: jianguog
 * Date: 16/12/7
 * Time: 07:26
 */
function GetHttpContent($fsock=null) {
    $out = null;
    while($buff = @fgets($fsock, 2048)){
        $out .= $buff;
    }
    fclose($fsock);
    $pos = strpos($out, "\r\n\r\n");
    $head = substr($out, 0, $pos);    //http head
    $status = substr($head, 0, strpos($head, "\r\n"));    //http status line
    $body = substr($out, $pos + 4, strlen($out) - ($pos + 4));//page body
    if(preg_match("/^HTTP\/\d\.\d\s([\d]+)\s.*$/", $status, $matches)){
        if(intval($matches[1]) / 100 == 2){
            return $body;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
function DoGet($url){
    $url2 = parse_url($url);
    $url2["path"] = ($url2["path"] == "" ? "/" : $url2["path"]);
    $url2["port"] = ($url2["port"] == "" ? 80 : $url2["port"]);
    $host_ip = @gethostbyname($url2["host"]);
    $fsock_timeout = 2;  //2 second
    if(($fsock = fsockopen($host_ip, $url2['port'], $errno, $errstr, $fsock_timeout)) < 0){
        return false;
    }
    $request =  $url2["path"] .($url2["query"] ? "?".$url2["query"] : "");
    $in  = "GET " . $request . " HTTP/1.0\r\n";
    $in .= "Accept: */*\r\n";
    $in .= "User-Agent: Payb-Agent\r\n";
    $in .= "Host: " . $url2["host"] . "\r\n";
    $in .= "Connection: Close\r\n\r\n";
    if(!@fwrite($fsock, $in, strlen($in))){
        fclose($fsock);
        return false;
    }
    return GetHttpContent($fsock);
}

function DoPost2($url, $post_data=array())
{
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    //设置post数据
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    return $data;
}

function DoPost($url,$post_data=array()){
    $url2 = parse_url($url);
    $url2["path"] = ($url2["path"] == "" ? "/" : $url2["path"]);
    $url2["port"] = isset($url2["port"]) ? $url2["port"] : 80;
    //$url2["port"] = ($url2["port"] == "" ? 80 : $url2["port"]);
    $host_ip = @gethostbyname($url2["host"]);
    $fsock_timeout = 2; //2 second
    if(($fsock = fsockopen($host_ip, $url2['port'], $errno, $errstr, $fsock_timeout)) < 0){
        return false;
    }
    $url2["query"] = isset($url2["query"]) ? $url2["query"] : '';
    $request =  $url2["path"].($url2["query"] ? "?" . $url2["query"] : "");
    //$post_data2 = http_build_query($post_data);
    $post_data2 = $post_data;
    $in  = "POST " . $request . " HTTP/1.0\r\n";
    $in .= "Accept: */*\r\n";
    $in .= "Host: " . $url2["host"] . "\r\n";
    $in .= "User-Agent: Lowell-Agent\r\n";
    $in .= "Content-type: application/x-www-form-urlencoded\r\n";
    $in .= "Content-Length: " . strlen($post_data2) . "\r\n";
    $in .= "Connection: Close\r\n\r\n";
    $in .= $post_data2 . "\r\n\r\n";
    unset($post_data2);
    if(!@fwrite($fsock, $in, strlen($in))){
        fclose($fsock);
        return false;
    }
    return GetHttpContent($fsock);
}

function SIGN($source, $version, $method, $token, $app_uid, $data, $key)
{
    $sign = md5($source.$version.$method.$token.$app_uid.$data.$key);
    return $sign;
}

function Post($url, $source, $version, $method, $token, $app_uid, $data, $key)
{
    $sign = SIGN($source, $version, $method, $token, $app_uid, $data, $key);
    //$sign =  'd51d3c3c4d30edadf9e68b0c08580367';
    //echo $sign;
    $post_data = array(
        'source' => $source,
        'version' => $version,
        'method' => $method,
        'token' => $token,
        'app_uid' => $app_uid,
        'data' => $data,
        'sign' => $sign
        // 'date'	=>	'2015-08-19 18:35:33',
        // 'api_version'	=>	'6.0.2'

    );
    return DoPost2($url, $post_data);
}

function Test($method, $data)
{
    $url = 'http://www.clcentury.com/weiphp/Addons/OverSea/Controller/APIDispatcher.php';
    $source = 'eknowhow';
    $version = '1.0.0';
    $key = '71e5d83f6480523cb7b52e13445c2865';
    $app_uid = '2827587';
    $token = 'UDA1B7sml7QdVz9_VFeN6YH2I7Qr_uNpd07CwS_qzxQ';

    $data = base64_encode(json_encode($data));
    $ret = Post($url, $source, $version, $method, $token, $app_uid, $data, $key);
    print_r($ret);
    echo "\n";
}
Test('getServices', array('serviceArea'=>'地球', 'serviceType'=>'1', 'pageIndex'=>'0'));
?>
