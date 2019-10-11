<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:27 PM
 */

global $_W, $_GPC;

//checkauth();

$orderid = intval($_GPC['orderid']);
$order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE id = :id", array(':id' => $orderid));
if ($order['status'] != '0') {
    message('抱歉，您的订单已经付款或是被关闭，请重新进入付款！', $this->createMobileUrl('orderlist', array('storeid' => $order['storeid'])), 'error');
}
$params['tid'] = $orderid;
$params['user'] = $order['from_user'];
$params['fee'] = $order['totalprice'];
$params['title'] = $_W['account']['name'];
$params['ordersn'] = $order['ordersn'];
$params['virtual'] = false;
include $this->template('pay');