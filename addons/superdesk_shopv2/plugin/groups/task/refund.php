<?php

error_reporting(0);

require '../../../../../framework/bootstrap.inc.php';
require '../../../../../addons/superdesk_shopv2/defines.php';
require '../../../../../addons/superdesk_shopv2/core/inc/functions.php';

global $_W;
global $_GPC;

ignore_user_abort();
set_time_limit(0);

$sets = pdo_fetchall(
    'select uniacid,refund ' .
    ' from ' . tablename('superdesk_shop_groups_set')
);

foreach ($sets as $key => $value) {

    global $_W;
    global $_GPC;
    global $_S;

    $_W['uniacid'] = $value['uniacid'];
    $shopset       = $_S['shop'];

    if (empty($_W['uniacid'])) {
        continue;
    }

    $hours = intval($value['refund']);
    if ($hours <= 0) {
        continue;
    }

    $times = $hours * 60 * 60;

    $orders = pdo_fetchall(
        'select id,orderno,openid,credit,creditmoney,price,freight,status,pay_type,teamid,apppay ' .
        ' from ' . tablename('superdesk_shop_groups_order') .
        ' where  ' .
        '       uniacid=' . $_W['uniacid'] .
        '       and status = 1 ' .
        '       and success = -1 ' .
        '       and refundtime = 0 ' .
        '       and canceltime + ' . $times . ' <= ' . time() . ' '
    );

    foreach ($orders as $k => $order) {

        $realprice = ($order['price'] - $order['creditmoney']) + $order['freight'];
        $credits   = $order['credit'];

        if ($order['pay_type'] == 'credit') {

            $result = m('member')->setCredit($order['openid'], $order['core_user'],
                'credit2', $realprice, array(0, $shopset['name'] . '退款: ' . $realprice . '元 订单号: ' . $order['orderno']));

        } else if ($order['pay_type'] == 'wechat') {

            $realprice = round($realprice, 2);

            $result = m('finance')->refund(
                $order['openid'],
                $order['orderno'],
                $order['orderno'],
                floatval($realprice) * 100, $realprice * 100, (!empty($order['apppay']) ? true : false)
            );

            $refundtype = 2;

        } else {

            if ($realprice < 1) {
                show_json(0, '退款金额必须大于1元，才能使用微信企业付款退款!');
            }

            $result = m('finance')->pay($order['openid'], $order['core_user'],
                1, $realprice * 100, $order['orderno'], $shopset['name'] . '退款: ' . $realprice . '元 订单号: ' . $order['orderno']);

            $refundtype = 1;
        }

        if (0 < $credits) {
            m('member')->setCredit($order['openid'], $order['core_user'],
                'credit1', $credits, array('0', $shopset['name'] . '购物返还抵扣积分 积分: ' . $order['credit'] . ' 抵扣金额: ' . $order['creditmoney'] . ' 订单号: ' . $order['orderno']));
        }

        pdo_update(
            'superdesk_shop_groups_order',
            array(
                'refundstate' => 0,
                'status'      => -1,
                'refundtime'  => time()
            ),
            array(
                'id'      => $order['id'],
                'uniacid' => $_W['uniacid']
            )
        );

        $sales = pdo_fetch(
            'select id,sales,stock ' .
            ' from ' . tablename('superdesk_shop_groups_goods') .
            ' where ' .
            '       id = :id ' .
            '       and uniacid = :uniacid ',
            array(
                ':id'      => $order['goodid'],
                ':uniacid' => $uniacid
            )
        );

        pdo_update(
            'superdesk_shop_groups_goods',
            array(
                'sales' => $sales['sales'] - 1,
                'stock' => $sales['stock'] + 1
            ),
            array(
                'id'      => $sales['id'],
                'uniacid' => $uniacid
            )
        );

        plog('groups.task.refund', '订单退款 ID: ' . $order['id'] . ' 订单号: ' . $order['orderno']);
    }
}