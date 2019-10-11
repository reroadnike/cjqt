<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
include_once(IA_ROOT.'/addons/site_bc/payment/Special.WxPay.Config.php');
//仿照微擎回调

$getSignKey  = SpecialWxPayConfig::KEY;

$input = file_get_contents('php://input');
$isxml = true;

if (!empty($input) && empty($_GET['out_trade_no'])) {
	$obj = isimplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
	$data = json_decode(json_encode($obj), true);
	if (empty($data)) {
		$result = array(
			'return_code' => 'FAIL',
			'return_msg' => ''
		);
		echo array2xml($result);
		exit;
	}
	if ($data['result_code'] != 'SUCCESS' || $data['return_code'] != 'SUCCESS') {
		$result = array(
			'return_code' => 'FAIL',
			'return_msg' => empty($data['return_msg']) ? $data['err_code_des'] : $data['return_msg']
		);
		echo array2xml($result);
		exit;
	}
	$get = $data;
} else {
	$isxml = false;
	$get = $_GET;
}
//WeUtility::logging('sdfsdf再试一次好无pay', $get);
$payInfos = pdo_get('core_paylog' , array( 'tid'=>$get['out_trade_no']));
$_W['uniacid'] = $_W['weid'] = $payInfos['uniacid'];

ksort($get);
$string1 = '';
foreach($get as $k => $v) {
	if($v != '' && $k != 'sign') {
		$string1 .= "{$k}={$v}&";
	}
}

$wechat['signkey'] = $getSignKey;
$sign = strtoupper(md5($string1 . "key={$wechat['signkey']}"));

if($sign == $get['sign']) {
	$updateState = pdo_update('core_paylog' , array('status'=>1) , array('tid'=>$get['out_trade_no'] , 'uniacid'=>$_W['uniacid']));

	//		自定义模块的回调操作,模块里建立一个 payResult 方法
	$site = WeUtility::createModuleSite($payInfos['module']);

	if(method_exists($site,'payResult')){
		WeUtility::logging( $get['out_trade_no'].'更改订单状态','成功');
		$ret['ordernum'] = $get['out_trade_no'];
		$ret['uniacid']  = $_W['uniacid'];
		$site->payResult($ret);
	}else{
		WeUtility::logging( $get['out_trade_no'].'更改订单状态','失败');
	}

	if(empty($updateState)){
		$result = array(
			'return_code' => 'FAIL',
			'return_msg' => '状态更改失败'
		);
		echo array2xml($result);
		exit;
	}else{
		if($isxml) {
			$result = array(
				'return_code' => 'SUCCESS',
				'return_msg' => 'OK'
			);
			echo array2xml($result);
			exit;
		} else {
			exit('success');
		}
	}
}

if($isxml) {
	$result = array(
		'return_code' => 'FAIL',
		'return_msg' => ''
	);
	echo array2xml($result);
	exit;
} else {
	exit('fail');
}
