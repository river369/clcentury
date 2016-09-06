
<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../../lib/wx-pay-v3/WxPay.Api.php";
require dirname(__FILE__).'/../../init.php';
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Model\PaymentsDao;

$order_id  = $_REQUEST ['returnorderid'];
$returnReason = $_REQUEST ['returnreason'];

$paymentsDao = new PaymentsDao();
$paymentData = $paymentsDao->getByKv('order_id',$order_id);
if(isset($paymentData["transaction_id"]) && $paymentData["transaction_id"] != ""){
	$transaction_id = $paymentData["transaction_id"];
	$total_fee = (int)$paymentData["total_fee"];
	$refund_fee = (int)$paymentData["total_fee"];
	$input = new WxPayRefund();
	$input->SetTransaction_id($transaction_id);
	$input->SetTotal_fee($total_fee);
	$input->SetRefund_fee($refund_fee);
    $input->SetOut_refund_no("R_".WxPayConfig::MCHID."_".$order_id);
    $input->SetOp_user_id(WxPayConfig::MCHID);
	Logs::writeReturnLog("ReturnToCustomerRequestPartial=".$input->ToXml());
	$data=WxPayApi::refund($input);

	Logs::writeReturnLog("ReturnToCustomerResponse=".json_encode($data));
	if (isset($data['result_code']) & $data['result_code'] == 'FAIL'){
		echo $data['err_code_des'] ;
		exit();
	}
	if (isset($data['return_code']) & $data['return_code'] == 'FAIL'){
		echo $data['return_msg'] ;
		exit();
	}
	header("Location:../AuthUserDispatcher.php?c=returnMoneyToCustomer&returnorderid=".$order_id."&returnreason=".$returnReason);
	exit();
} else {
	echo "Invalid payment data";
	exit();
}

?>