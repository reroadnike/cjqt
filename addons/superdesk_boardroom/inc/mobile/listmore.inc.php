<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 5:41 AM
 */

global $_GPC, $_W;
$pindex = max(1, intval($_GPC['page']));
$psize = 4;
$condition = '';
$params = array(':uniacid' => $_W['uniacid']);
$cid = intval($_GPC['ccate']);
if (empty($cid)) {
    return NULL;
}

$catePid = $_GPC['pcate'];
if (empty($catePid)) {
    $condition .= ' AND `pcate` = :pcate';
    $params[':pcate'] = $cid;
} else {
    $condition .= ' AND `ccate` = :ccate';
    $params[':ccate'] = $cid;
}


$sql = 'SELECT * FROM ' . tablename('superdesk_boardroom_s_goods') . ' WHERE `uniacid` = :uniacid AND `deleted` = :deleted AND `status` = :status ' . $condition .
    ' ORDER BY `displayorder` DESC, `sales` DESC LIMIT ' . ($pindex - 1) * $psize . ',' . $psize;
$params[':deleted'] = 0;
$params[':status'] = 1;
$list = pdo_fetchall($sql, $params);

include $this->template('list_more');