
<?php
session_start();
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "../../lib/wx-pay-v3/WxPay.Api.php";
require dirname(__FILE__).'/../../init.php';
use Addons\OverSea\Common\Logs;
use Addons\OverSea\Model\OrdersDao;
use Addons\OverSea\Model\SellerPayAccountsDao;
use Addons\OverSea\Model\PaymentsSellerDao;

function get_server_ip() {
	if (isset($_SERVER)) {
		if($_SERVER['SERVER_ADDR']) {
			$server_ip = $_SERVER['SERVER_ADDR'];
		} else {
			$server_ip = $_SERVER['LOCAL_ADDR'];
		}
	} else {
		$server_ip = getenv('SERVER_ADDR');
		//$server_ip = "101.201.49.153";
	}
	return $server_ip;
}

function savePaymentSeller($orderData,$activeAccount, $payData) {
	$paymentsSellerDao = new PaymentsSellerDao();

	$paymentSellerData = array();
	$paymentSellerData['order_id'] = $orderData['order_id'];
	$paymentSellerData['pattern_trade_no'] = "P_".WxPayConfig::MCHID."_".$orderData['order_id'];
	$paymentSellerData['openid'] = $activeAccount['account_id'];
	$paymentSellerData['amount'] = $orderData['service_total_fee']*100;
	$paymentSellerData['return_code'] = $payData['return_code'];
	$paymentSellerData['return_msg'] = $payData['return_msg'];
	$paymentSellerData['result_code'] = $payData['result_code'];
	$paymentSellerData['err_code_des'] = $payData['err_code_des'];
	$paymentSellerData['action_user_id'] = isset($_SESSION['signedUser']) ? $_SESSION['signedUser'] : "unknown";

	$paymentsSellerDao->insert($paymentSellerData);
}

$existedUser = $_SESSION['signedUserInfo'];
if (isset($existedUser) && $existedUser['user_type'] == 1){
	$order_id  = $_REQUEST ['payorderid'];
	$payReason = $_REQUEST ['payreason'];

	$ordersDao = new OrdersDao();
	$orderData = $ordersDao->getByKv('order_id',$order_id);
	
	if(isset($orderData["seller_id"])){
		$sellerPayAccountsDao = new SellerPayAccountsDao();
		$activeAccount = $sellerPayAccountsDao -> getPayAccountsByUserIdStatus($orderData['seller_id'], 1);
		if(isset($activeAccount['id'])) {
			$input = new WxPaySeller();
			//use static value without time in the key to avoid someone call it many times to steal money
			$input->SetPartner_trade_no("P_".WxPayConfig::MCHID."_".$orderData['order_id']);
			$input->SetAmount($orderData['service_total_fee']*100);
			$input->SetOpenid($activeAccount['account_id']);
			$input->SetCheck_name("NO_CHECK");
			$input->SetDesc("卖家履约完毕,易知海外支付款。");
			$input->SetSpbill_create_ip(get_server_ip());
			Logs::writeSellerPayLog("ReturnToCustomerRequestPartial=".$input->ToXml());
			$payData=WxPayApi::paySeller($input);
			Logs::writeSellerPayLog("PayToSellerResponse=".json_encode($payData));
			if (isset($payData['result_code']) & $payData['result_code'] == 'FAIL'){
				echo $payData['err_code_des'] ;
				savePaymentSeller($orderData,$activeAccount, $payData);
				exit();
			}
			if (isset($payData['return_code']) & $payData['return_code'] == 'FAIL'){
				echo $payData['return_msg'] ;
				savePaymentSeller($orderData,$activeAccount, $payData);
				exit();
			}
			savePaymentSeller($orderData,$activeAccount, $payData);
			header("Location:../AuthUserDispatcher.php?c=payMoneyToSeller&payorderid=".$order_id."&payreason=".$payReason);
			exit();
		} else {
			echo "Invalid seller pay account data";
			exit();
		}
	} else {
		echo "Invalid order data";
		exit();
	}
} else {
	echo "No admin permission!";
	exit();
}

?>