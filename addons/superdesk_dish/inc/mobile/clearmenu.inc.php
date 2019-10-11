<?php
/**
 * 清空购物车
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:29 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $_GPC['from_user'];
$this->_fromuser = $from_user;

if (empty($from_user)) {
    message('会话已过期，请重新发送关键字!');
}

$storeid = intval($_GPC['storeid']);
if (empty($storeid)) {
    message('请先选择门店');
}

pdo_delete('superdesk_dish_cart', array('weid' => $weid, 'from_user' => $from_user, 'storeid' => $storeid));
$url = $this->createMobileUrl('waplist', array('storeid' => $storeid), true);
message('操作成功', $url, 'success');