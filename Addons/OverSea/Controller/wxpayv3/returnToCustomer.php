
<?php
session_start();
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../../lib/wx-pay-v3/WxPay.Api.php";
require dirname(__FILE__).'/../../init.php';
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Model\PaymentsDao;
use Addons\OverSea\Model\PaymentsRefundDao;

function saveRefund($paymentData, $refundData) {
	$paymentsRefundDao = new PaymentsRefundDao();

	$paymentRefundData = array();
	$paymentRefundData['order_id'] = $paymentData['order_id'];
	$paymentRefundData['transaction_id'] = $paymentData['transaction_id'];
	$paymentRefundData['out_refund_no'] = "R_".WxPayConfig::MCHID."_".$paymentData['order_id'];
	$paymentRefundData['total_fee'] = $paymentData['total_fee'];
	$paymentRefundData['refund_fee'] = $paymentData['total_fee'];
	$paymentRefundData['return_code'] = $refundData['return_code'];
	$paymentRefundData['return_msg'] = $refundData['return_msg'];
	$paymentRefundData['result_code'] = $refundData['result_code'];
	$paymentRefundData['err_code_des'] = $refundData['err_code_des'];
	$paymentRefundData['action_user_id'] = isset($_SESSION['signedUser']) ? $_SESSION['signedUser'] : "unknown";
	
	$paymentsRefundDao->insert($paymentRefundData);
}

$existedUser = $_SESSION['signedUserInfo'];
if (isset($existedUser) && $existedUser['user_type'] == 1){
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
		$refundData=WxPayApi::refund($input);

		Logs::writeReturnLog("ReturnToCustomerResponse=".json_encode($refundData));
		if (isset($refundData['return_code']) & $refundData['return_code'] == 'FAIL'){
			saveRefund($paymentData,$refundData);
			echo $refundData['return_msg'] ;
			exit();
		}
		if (isset($refundData['result_code']) & $refundData['result_code'] == 'FAIL'){
			saveRefund($paymentData,$refundData);
			echo $refundData['err_code_des'] ;
			exit();
		}
		saveRefund($paymentData,$refundData);
		header("Location:../AuthUserDispatcher.php?c=returnMoneyToCustomer&returnorderid=".$order_id."&returnreason=".$returnReason);
		exit();
	} else {
		echo "Invalid payment data!";
		exit();
	}
} else {
	echo "No admin permission!";
	exit();
}
?>