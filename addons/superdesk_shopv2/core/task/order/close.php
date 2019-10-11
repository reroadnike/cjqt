<?php
/**
 *
 * 设置->交易设置->自动关闭未付款订单
 *
 * 下单几天后 input closeorder
 * 订单下单未付款，n天后自动关闭，空为不自动关闭
 */
error_reporting(0);

require '../../../../../framework/bootstrap.inc.php';
require '../../../../../addons/superdesk_shopv2/defines.php';
require '../../../../../addons/superdesk_shopv2/core/inc/functions.php';

global $_W;
global $_GPC;

ignore_user_abort();
set_time_limit(0);

$sets = pdo_fetchall(
    'select uniacid ' .
    ' from ' . tablename('superdesk_shop_sysset')
);

foreach ($sets as $set) {

    $_W['uniacid'] = $set['uniacid'];
    if (empty($_W['uniacid'])) {
        continue;
    }

    $trade = m('common')->getSysset('trade', $_W['uniacid']);

    $days = intval($trade['closeorder']);
    if ($days <= 0) {
        continue;
    }

    $daytimes = 86400 * $days;

    $orders = pdo_fetchall(
        ' select id,openid,core_user,deductcredit2,ordersn,isparent,deductcredit,deductprice ' .
        ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
        ' where ' .
        '       uniacid=' . $_W['uniacid'] .
        '       and status=0 ' .
        '       and paytype<>3  ' .
        '       and createtime + ' . $daytimes . ' <=unix_timestamp() '
    );

    $p = com('coupon');

    foreach ($orders as $order) {

        $order_be_close = pdo_fetch(
            'select status,isparent ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where ' .
            '       id=:id and status=0 ' .
            '       and paytype<>3  and createtime + ' . $daytimes . ' <=unix_timestamp() ' .
            ' limit 1',
            array(
                ':id' => $order['id']
            )
        );

        if (!empty($order_be_close) && ($order_be_close['status'] == 0)) {

            if ($order['isparent'] == 0) {

                if ($p) {
                    if (!empty($order['couponid'])) {
                        $p->returnConsumeCoupon($order['id']);
                    }
                }

                m('order')->setStocksAndCredits($order['id'], 2);
                m('order')->setDeductCredit2($order);

                if (0 < $order['deductprice']) {

                    m('member')->setCredit($order['openid'], $order['core_user'],
                        'credit1',
                        $order['deductcredit'],
                        array('0',
                            $_W['shopset']['shop']['name'] .
                            '自动关闭订单返还抵扣积分 积分: ' . $order['deductcredit'] .
                            ' 抵扣金额: ' . $order['deductprice'] .
                            ' 订单号: ' . $order['ordersn']));
                }
            }

            pdo_query(
                'update ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
                ' set status=-1,canceltime=' . time() .
                ' where id=' . $order['id']
            );
        }
    }
}