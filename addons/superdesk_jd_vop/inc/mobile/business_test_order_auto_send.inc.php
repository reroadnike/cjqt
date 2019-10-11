<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/22/17
 * Time: 10:21 AM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=business_test_order_auto_send
 */



global $_W, $_GPC;


include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
$_orderService = new OrderService();

$_orderService->autoSendByShopOrderId($_W['uniacid'],1,72);