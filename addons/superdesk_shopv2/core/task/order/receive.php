<?php

/**
 *
 * 设置->交易设置->自动收货
 *
 * 发货几天后 input receive
 * 订单发货后，用户收货的天数，如果在期间未确认收货，系统自动完成收货，空为不自动收货
 */
error_reporting(0);

require '../../../../../framework/bootstrap.inc.php';
require '../../../../../addons/superdesk_shopv2/defines.php';
require '../../../../../addons/superdesk_shopv2/core/inc/functions.php';
require '../../../../../addons/superdesk_shopv2/core/inc/plugin_model.php';

global $_W;
global $_GPC;

ignore_user_abort();
set_time_limit(0);

$sets = pdo_fetchall('select uniacid from ' . tablename('superdesk_shop_sysset'));

include_once('../../../../../addons/superdesk_shopv2/model/order/order.class.php');
$_orderModel = new orderModel();

foreach ($sets as $set) {

    $_W['uniacid'] = $set['uniacid'];

    if (empty($_W['uniacid'])) {
        continue;
    }

    $trade = m('common')->getSysset('trade', $_W['uniacid']);

    $days = intval($trade['receive']);

    $p = p('commission');

    $pcoupon = com('coupon');

    $orders = pdo_fetchall(
        ' select id,couponid,openid,core_user,isparent,sendtime ' .
        ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
        ' where ' .
        '       uniacid=' . $_W['uniacid'] .
        '       and merchid!=' . SUPERDESK_SHOPV2_JD_VOP_MERCHID .  //2019年4月24日 12:33:18 zjh 应宇迪要求 #2801
        '       and status=2 ',
        array(),
        'id'
    );

    if (!empty($orders)) {

        foreach ($orders as $orderid => $order) {

            $result = goodsReceive($order, $days);

            if (!$result) {
                continue;
            }

            //2019年1月15日 16:48:37 zjh 新处理方法
            $_orderModel->updateByColumn(
                array(
                    'status'     => 3,
                    'finishtime' => time()
                ),
                array(
                    'id' => $orderid
                )
            );

            //2019年1月15日 16:48:37 zjh 原处理方法
//            pdo_query(
//                'update ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
//                ' set status=3,finishtime=' . time() . ' where id=' . $orderid
//            );

            if ($order['isparent'] == 1) {
                continue;
            }

            m('member')->upgradeLevel($order['openid'], $order['core_user']);
            m('order')->setGiveBalance($orderid, 1);
            m('notice')->sendOrderMessage($orderid);

            if ($pcoupon) {
                if (!empty($order['couponid'])) {
                    $pcoupon->backConsumeCoupon($order['id']);
                }
            }

            if ($p) {
                $p->checkOrderFinish($orderid);
            }

        }
    }

}

function goodsReceive($order, $sysday = 0)
{
    $days  = array();
    $goods = pdo_fetchall(
        'select og.goodsid, g.autoreceive ' .
        ' from' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
        ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
        ' where ' .
        '       og.orderid=' . $order['id']);

    foreach ($goods as $i => $g) {
        $days[] = $g['autoreceive'];
    }

    $day = max($days);

    if ($day < 0) {
        return false;
    }

    if ($day == 0) {

        if ($sysday <= 0) {
            return false;
        }

        $day = $sysday;
    }

    $daytimes = 86400 * $day;

    if (($order['sendtime'] + $daytimes) <= time()) {
        return true;
    }

    return false;
}