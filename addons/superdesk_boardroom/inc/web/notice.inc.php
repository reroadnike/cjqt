<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 4:43 AM
 */

global $_GPC, $_W;
load()->func('tpl');
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
$operation = in_array($operation, array('display')) ? $operation : 'display';
$pindex = max(1, intval($_GPC['page']));
$psize = 50;
if (!empty($_GPC['date'])) {
    $starttime = strtotime($_GPC['date']['start']);
    $endtime = strtotime($_GPC['date']['end']) + 86399;
} else {
    $starttime = strtotime('-1 month');
    $endtime = time();
}
$where = " WHERE `uniacid` = :uniacid AND `createtime` >= :starttime AND `createtime` < :endtime";
$paras = array(
    ':uniacid' => $_W['uniacid'],
    ':starttime' => $starttime,
    ':endtime' => $endtime
);
$keyword = $_GPC['keyword'];
if (!empty($keyword)) {
    $where .= " AND `feedbackid`=:feedbackid";
    $paras[':feedbackid'] = $keyword;
}
$type = empty($_GPC['type']) ? 0 : $_GPC['type'];
$type = intval($type);
if ($type != 0) {
    $where .= " AND `type`=:type";
    $paras[':type'] = $type;
}
$status = empty($_GPC['status']) ? 0 : intval($_GPC['status']);
$status = intval($status);
if ($status != -1) {
    $where .= " AND `status` = :status";
    $paras[':status'] = $status;
}
$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename('superdesk_boardroom_s_feedback') . $where, $paras);
$list = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_s_feedback') . $where . " ORDER BY id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, $paras);
$pager = pagination($total, $pindex, $psize);
$transaction_ids = array();
foreach ($list as $row) {
    $transaction_ids[] = $row['transaction_id'];
}
if (!empty($transaction_ids)) {
    $sql = "SELECT * FROM " . tablename('superdesk_boardroom_s_order') . " WHERE uniacid='{$_W['uniacid']}' AND transaction_id IN ( '" . implode("','", $transaction_ids) . "' )";
    $orders = pdo_fetchall($sql, array(), 'transaction_id');
}
//		$addressids = array();
//		if(is_array($orders)){
//			foreach ($orders as $transaction_id => $order) {
//				$addressids[] = $order['addressid'];
//			}
//		}
//		$addresses = array();
//		if (!empty($addressids)) {
//			$sql = "SELECT * FROM " . tablename('mc_member_address') . " WHERE uniacid='{$_W['uniacid']}' AND id IN ( '" . implode("','", $addressids) . "' )";
//			$addresses = pdo_fetchall($sql, array(), 'id');
//		}
foreach ($list as &$feedback) {
    $transaction_id = $feedback['transaction_id'];
    $order = $orders[$transaction_id];
    $feedback['order'] = $order;
//			$addressid = $order['addressid'];
//			$feedback['address'] = $addresses[$addressid];
}
include $this->template('notice');