<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/22/17
 * Time: 10:21 AM
 *
 * view-source:192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=business_test_order_auto_refund
 */



global $_W, $_GPC;


include_once(IA_ROOT . '/addons/superdesk_shopv2/service/order/ShopOrderService.class.php');
$_shopOrderService = new ShopOrderService();

$_W['openid'] = 'oX8KYwo9sdtTHoOiXLIvVy43rmGA';// 陈文礼 超级前台服务
$order_id = 418;

//$_W['openid'] = 'oX8KYwkxwNW6qzHF4cF-tGxYTcPg';// 林进雨 超级前台服务
//$order_id = 427;

$refund_reason = '系统提交:京东订单取消(不区分取消原因)';



$refund_result_main = $_shopOrderService->refundMain($_W['uniacid'],$_W['openid'],$_W['core_user'],$order_id);

echo json_encode($refund_result_main,JSON_UNESCAPED_UNICODE);

if($refund_result_main['success']){
    $refund_price = $refund_result_main['result'];
    $refund_result_submit = $_shopOrderService->refundSubmit($_W['uniacid'],$_W['openid'],$_W['core_user'],$order_id,$refund_price,0,$refund_reason,$refund_reason);

    echo PHP_EOL;
    echo $refund_result_submit['resultMessage'];

} else {
    echo PHP_EOL;
    echo $refund_result_main['resultMessage'];
}







