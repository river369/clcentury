<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "../../lib/wx-pay-v3/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

require dirname(__FILE__).'/../../init.php';
use Addons\OverSea\Common\Logs;

session_start();
$orderData= $_SESSION['orderData'];
//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
		Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":".$key.":".$value);
    }
}

//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($orderData['sellername']);
$input->SetAttach("yz");
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis")."_".$orderData['id']);
$input->SetTotal_fee($orderData['servicetotalfee']);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://www.clcentury.com/weiphp/Addons/OverSea/Controller/wxpayv3/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>易知海外</title>

	<script src="../../View/resource/js/jquery/jquery-1.11.1.min.js"></script>
	<script src="../../View/resource/js/jquery/jquery.mobile-1.4.5.min.js"></script>
	<link rel="stylesheet" href="../../View/resource/style/jquery/jquery.mobile-1.4.5.min.css" />
	<link rel="stylesheet" href="../../View/resource/style/themes/my-theme.min.css" />

    <script type="text/javascript">
		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					alert(res.err_code+res.err_desc+res.err_msg);
				}
			);
		};

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
				if( document.addEventListener ){
					document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
				}else if (document.attachEvent){
					document.attachEvent('WeixinJSBridgeReady', jsApiCall);
					document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
				}
			}else{
				jsApiCall();
			}
		}
	</script>
</head>
<body>
<div data-url="panel-fixed-page1" data-role="page" class="jqm-demos" id="panel-fixed-page1" data-title="易知海外">
	<div data-role="header" data-position="fixed" data-theme="c">
		<h1>支付</h1>
	</div>
    <br/>
	<div align="center" data-role="content" data-theme="c">
		<font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px"><?php echo $orderData['servicetotalfee'];?></span>元</b></font><br/><br/>
		<input data-mini="true" value="立即支付" type="button" onclick="callpay()">
	</div>

	<div data-role="footer" data-position="fixed" data-theme="c">
		<h4>Copyright (c) 2016 .</h4>
	</div>
</div>
</body>
</html>