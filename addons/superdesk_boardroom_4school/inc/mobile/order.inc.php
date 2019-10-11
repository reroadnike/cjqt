<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 5:07 AM
 */

global $_W, $_GPC;

$orderId = intval($_GPC['orderid']);
$status = intval($_GPC['status']);
$referStatus = intval($_GPC['curtstatus']);
$sql = 'SELECT `id` FROM ' . tablename('superdesk_boardroom_4school_s_order') . ' WHERE `id` = :id AND `uniacid` = :uniacid AND `from_user`
				= :from_user';
$params = array(':id' => $orderId, ':uniacid' => $_W['uniacid'], ':from_user' => $_W['openid']);
$orderId = pdo_fetchcolumn($sql, $params);
$redirect = $this->createMobileUrl('myorder', array('status' => $referStatus));
if (empty($orderId)) {
    message('订单不存在或已经被删除', $redirect , 'error');
}

if ($_GPC['op'] == 'delete') {
    pdo_delete('superdesk_boardroom_4school_s_order', array('id' => $orderId));
    pdo_delete('superdesk_boardroom_4school_s_order_goods', array('orderid' => $orderId));
    message('订单已经成功删除！', $redirect, 'success');
} else {
    pdo_update('superdesk_boardroom_4school_s_order', array('status' => $status), array('id' => $orderId));
    $order = pdo_get('superdesk_boardroom_4school_s_order_goods', array('uniacid' => $_W['uniacid'], 'orderid' => $orderId));
    $goodid = $order['goodsid'];
    $good = pdo_get('superdesk_boardroom_4school_s_goods', array('uniacid' => $_W['uniacid'], 'id' => $goodid));
    if ($good['totalcnf'] == 0 && $status == -1) {
        pdo_update('superdesk_boardroom_4school_s_goods', array('sales' => $good['sales'] -1),array('uniacid' => $_W['uniacid'], 'id' => $goodid));
    }
    message('订单已经成功取消！', $redirect, 'success');
}