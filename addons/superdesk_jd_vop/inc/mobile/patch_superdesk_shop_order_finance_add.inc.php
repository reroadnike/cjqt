<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 3/4/19
 * Time: 7:05 PM
 *
 * view-source:http://192.168.1.223/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_finance_add
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_finance_add
 *
 */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_finance.class.php');

$_order_financeModel = new order_financeModel();

$params = array(
    'uniacid'      => $_W['uniacid'],
    'orderid'      => 19257,
    'ordersn'      => 'ME20181216083726646239',
    'merchid'      => 8,
    'createtime'   => 1545010834, //time(), // 2018-12-17 09:40:34
    'press_status' => 1,
    'press_time'   => 0,
);

$_order_financeModel->addOrderFinance($params);