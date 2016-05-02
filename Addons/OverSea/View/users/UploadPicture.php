<?php
$appId = 'wx3266dc2dad415085';
$appsecret = '99b8ed61fe784a419d2960dc0c2d2cdb';
$timestamp = time();
$jsapi_ticket = make_ticket($appId,$appsecret);
$nonceStr = make_nonceStr();
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$signature = make_signature($nonceStr,$timestamp,$jsapi_ticket,$url);

if (isset($_GET ['serverids'])){
    $serverids = $_GET ['serverids'];
    //echo $serverids;
    $serveridsArray = explode(',',$serverids);
    foreach ($serveridsArray as $serverid){
        getmedia($appId,$appsecret, $serverid, 'test');
    }
}


function make_nonceStr()
{
    $codeSet = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    for ($i = 0; $i<16; $i++) {
        $codes[$i] = $codeSet[mt_rand(0, strlen($codeSet)-1)];
    }
    $nonceStr = implode($codes);
    return $nonceStr;
}
function make_signature($nonceStr,$timestamp,$jsapi_ticket,$url)
{
    $tmpArr = array(
        'noncestr' => $nonceStr,
        'timestamp' => $timestamp,
        'jsapi_ticket' => $jsapi_ticket,
        'url' => $url
    );
    ksort($tmpArr, SORT_STRING);
    $string1 = http_build_query( $tmpArr );
    $string1 = urldecode( $string1 );
    $signature = sha1( $string1 );
    return $signature;
}
function make_ticket($appId,$appsecret)
{
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("access_token.json"));
    if ($data->expire_time < time()) {
        $TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appsecret;
        $json = file_get_contents($TOKEN_URL);
        $result = json_decode($json,true);
        $access_token = $result['access_token'];
        if ($access_token) {
            $data->expire_time = time() + 7000;
            $data->access_token = $access_token;
            $fp = fopen("access_token.json", "w");
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    }else{
        $access_token = $data->access_token;
    }
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("jsapi_ticket.json"));
    if ($data->expire_time < time()) {
        $ticket_URL="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi";
        $json = file_get_contents($ticket_URL);
        $result = json_decode($json,true);
        $ticket = $result['ticket'];
        if ($ticket) {
            $data->expire_time = time() + 7000;
            $data->jsapi_ticket = $ticket;
            $fp = fopen("jsapi_ticket.json", "w");
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    }else{
        $ticket = $data->jsapi_ticket;
    }
    return $ticket;
}
// 获取图片地址
function getmedia($appId,$appsecret, $media_id,$foldername){
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("access_token.json"));
    if ($data->expire_time < time()) {
        $TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$appsecret;
        $json = file_get_contents($TOKEN_URL);
        $result = json_decode($json,true);
        $access_token = $result['access_token'];
        if ($access_token) {
            $data->expire_time = time() + 7000;
            $data->access_token = $access_token;
            $fp = fopen("access_token.json", "w");
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    }else{
        $access_token = $data->access_token;
    }

    $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
    //echo "/home/www/clc/weiphp/Uploads/Picture/".$foldername;
    if (!file_exists("/home/www/clc/weiphp/Uploads/Picture/".$foldername)) {
        mkdir("/home/www/clc/weiphp/Uploads/Picture//".$foldername, 0777, true);
    }
    $targetName = '/home/www/clc/weiphp/Uploads/Picture/'.$foldername.'/'.date('YmdHis').rand(1000,9999).'.jpg';
    $ch = curl_init($url); // 初始化
    $fp = fopen($targetName, 'wb'); // 打开写入
    curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    return $targetName;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>上传图片</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../style/weui.css"/>
    <link rel="stylesheet" href="../style/example.css"/>
</head>
<body ontouchstart="">
<div class="wxapi_container">
    <div class="weui_cells_title">上传图片</div>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <div class="weui_uploader">
                    <div class="weui_uploader_hd weui_cell">
                        <div class="weui_cell_bd weui_cell_primary">图片</div>
                        <div class="weui_cell_ft">0/2</div>
                    </div>
                    <ul class="weui_uploader_files">
                        <li class="weui_uploader_file" style="background-image:url(http://www.clcentury.com/weiphp/Uploads/Picture/test/201605020044186186.jpg)"></li>
                        <li class="weui_uploader_file" id="uplaodImages" style="background-image:url(../images/add.jpg)"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
        debug: false,
        appId: '<?=$appId?>',
        timestamp: <?=$timestamp?>,
        nonceStr: '<?=$nonceStr?>',
        signature: '<?=$signature?>',
        jsApiList: [
            'chooseImage',
            'uploadImage',
        ]
    });
</script>
<script src="demo.js"></script>
</html>
