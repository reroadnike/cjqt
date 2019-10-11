<?php

ini_set('display_errors', 'On');
error_reporting(30719);

require '../../../../../framework/bootstrap.inc.php';
require '../../../../../addons/superdesk_shopv2/defines.php';
require '../../../../../addons/superdesk_shopv2/core/inc/functions.php';

global $_W;
global $_GPC;

ignore_user_abort();
set_time_limit(0);

$p = com('coupon');

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

    $days = intval($trade['refunddays']);

    $daytimes = 86400 * $days;

    $orders = pdo_fetchall(
        'select id,couponid ' .
        ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
        ' where ' .
        '       uniacid=' . $_W['uniacid'] .
        '       and status=3 ' .
        '       and isparent=0 ' .
        '       and couponid<>0 ' .
        '       and finishtime + ' . $daytimes . ' <=unix_timestamp() '
    );

    if (!empty($orders)) {

        if ($p) {

            foreach ($orders as $shop_order) {

                if (!empty($shop_order['couponid'])) {

                    $p->backConsumeCoupon($shop_order['id']);
                }
            }
        }
    }
}