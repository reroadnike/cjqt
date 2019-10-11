<?php

require '../../../../framework/bootstrap.inc.php';
require '../../../../addons/superdesk_shopv2/defines.php';
require '../../../../addons/superdesk_shopv2/core/inc/functions.php';

$ordersn = str($_GET['out_trade_no']);
$attachs = explode(':', str($_GET['body']));
$get     = json_encode($_GET);
$get     = base64_encode($get);

if (empty($attachs) || !is_array($attachs)) {
    exit();
}

$uniacid = intval($attachs[0]);
$paytype = intval($attachs[1]);

$url = $_W['siteroot'] . '../../app/index.php?i=' . $uniacid . '&c=entry&m=superdesk_shopv2&do=mobile';

if (!empty($ordersn)) {
    if ($paytype == 0) {
        $url = $_W['siteroot'] . '../../app/index.php?i=' . $uniacid . '&c=entry&m=superdesk_shopv2&do=mobile&r=order.pay_alipay.complete&alidata=' . $get;
    } else if ($paytype == 1) {
        $url = $_W['siteroot'] . '../../app/index.php?i=' . $uniacid . '&c=entry&m=superdesk_shopv2&do=mobile&r=order.pay_alipay.recharge_complete&alidata=' . $get;
    }
}

header('location: ' . $url);
exit();

function str($str)
{
    $str = str_replace('"', '', $str);
    $str = str_replace('\'', '', $str);
    $str = str_replace('=', '', $str);
    return $str;
}