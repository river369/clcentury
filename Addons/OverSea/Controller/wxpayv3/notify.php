<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "../../lib/wx-pay-v3/WxPay.Api.php";
require_once '../../lib/wx-pay-v3/WxPay.Notify.php';
require dirname(__FILE__).'/../../init.php';
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Model\OrdersBo;

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Logs::writePayLog(__CLASS__.",".__FUNCTION__.",".json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			$paymentData = array();
			$out_trade_no = $result["out_trade_no"];
			$paymentData['out_trade_no'] = $out_trade_no;
			$spit = explode('_', $out_trade_no);
			$paymentData['order_id'] = $spit[2];
			$paymentData['start_date'] =  $spit[1];
			$paymentData['transaction_id'] = $result["transaction_id"];
			$paymentData['cash_fee'] = $result["cash_fee"];
			$paymentData['total_fee'] = $result["total_fee"];
			$paymentData['fee_type'] = $result["fee_type"];
			$paymentData['openid'] = $result["openid"];
			$paymentData['is_subscribe'] = $result["is_subscribe"];
			$paymentData['result_code'] = $result["result_code"];
			$paymentData['return_code'] = $result["return_code"];
			$paymentData['trade_type'] = $result["trade_type"];
			$paymentData['end_date'] = $result["time_end"];
			OrdersBo::paymentConfirmOrder($paymentData);
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Logs::writePayLog(__CLASS__.",".__FUNCTION__.",".json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}

Logs::writeClcLog(__CLASS__.",".__FUNCTION__.":"."begin notify:");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
