<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';

load()->app('common');
load()->app('template');

$sl = $_GPC['ps'];
$params = @json_decode(base64_decode($sl), true);
$returnUrl = $params['forward'];

if($_GPC['done'] == '1') {
	header("location: $returnUrl");
}


$special_site = WeUtility::createModuleSite($params['module']);
$check = $special_site->orderCheck($params , 'check');
if($check == 'paid'){
	exit('这个订单已经支付成功, 不需要重复支付.');
}



//特约商户生成签名并记录订单入core_paylog表，即系统订单记录表
$special_site_log = WeUtility::createModuleSite('site_bc');
$ret = array();
$ret['uniacid'] 	= $params['uniacid'];
$ret['module']  	= $params['module'];
$ret['body']  		= $params['order_body'];
$ret['attach']  	= $params['order_attach'];
$ret['out_trade_no']= $params['out_trade_no'];
$ret['total_fee']  	= $params['acount'];
$ret['openid']  	= $params['openid'];

//因同创的特殊性，设置拼接字符获取不同商户号
if(!empty($params['shop_mch'])){
	$ret['shop_mch']	= $params['shop_mch'];
}

$wOpt = $special_site_log->specialPay($ret);
//var_dump($wOpt);exit;
//签名状态判断
if (is_error($wOpt)) {
	if ($wOpt['message'] == 'invalid out_trade_no' || $wOpt['message'] == 'OUT_TRADE_NO_USED') {
		@message("抱歉，发起支付失败，系统已经修复此问题，请重新尝试支付。");
	}
	message("抱歉，发起支付失败，具体原因为：“{$wOpt['errno']}:{$wOpt['message']}”。请及时联系站点管理员。");
	exit;
}

//统一下单成功后，调用模块的记录订单方法 。
//$special_site = WeUtility::createModuleSite($params['module']);
$special_site->orderCheck($params , 'add');






?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>加载中...</title>
	<!--    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>-->
</head>
<body>
<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
	WeixinJSBridge.invoke('getBrandWCPayRequest', {
		'appId' : '<?php echo $wOpt['appId'];?>',
		'timeStamp': '<?php echo $wOpt['timeStamp'];?>',
		'nonceStr' : '<?php echo $wOpt['nonceStr'];?>',
		'package' : '<?php echo $wOpt['package'];?>',
		'signType' : '<?php echo $wOpt['signType'];?>',
		'paySign' : '<?php echo $wOpt['paySign'];?>'
	}, function(res) {
		
		if(res.err_msg == 'get_brand_wcpay_request:ok') {
			alert('支付成功');
			location.search += '&done=1';
		} else {
			alert('启动微信支付失败');
		//	alert(res.err_desc);
			history.go(-1);

		}
	});
}, false);
</script>
</body>
</html>
