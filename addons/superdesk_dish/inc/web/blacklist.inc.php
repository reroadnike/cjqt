<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:15 PM
 */

global $_W, $_GPC;
$GLOBALS['frames'] = $this->getNaveMenu();
load()->model('mc');
$weid = $_W['uniacid'];

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    $pindex = max(1, intval($_GPC['page']));
    $psize = 10;

//            $list = pdo_fetchall("SELECT c.id as id,a.nickname as nickname,a.realname as realname,a.mobile as mobile,c.dateline as dateline,c.from_user as from_user,c.status as status FROM " . tablename('mc_members') . " a INNER JOIN " . tablename('mc_mapping_fans') . " b ON a.uid=b.uid INNER JOIN " . tablename('superdesk_dish_blacklist') . " c ON b.openid=c.from_user WHERE c.weid=:weid ORDER BY c.dateline DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $_W['uniacid']));
    $list = pdo_fetchall("SELECT * FROM " . tablename('superdesk_dish_blacklist') . " WHERE weid=:weid ORDER BY dateline DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(':weid' => $_W['uniacid']));

    if (!empty($list)) {
        $total = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename($this->table_blacklist) . "  WHERE weid=:weid", array(':weid' => $_W['uniacid']));
    }

    //ims_mc_members
    //ims_mc_mapping_fans
    //SELECT * FROM ims_mc_members a INNER JOIN ims_mc_mapping_fans b ON a.uid=b.uid WHERE b.openid IN (SELECT from_user FROM ims_superdesk_dish_blacklist)

    $pager = pagination($total, $pindex, $psize);
} else if ($operation == 'black') {
    $id = $_GPC['id'];

    //pdo_query("UPDATE " . tablename($this->table_blacklist) . " SET status = abs(status - 1) WHERE id=:id AND weid=:weid", array(':id' => $id, ':weid' => $_W['uniacid']));

    pdo_delete($this->table_blacklist, array('id' => $id, 'weid' => $weid));

    message('操作成功！', $this->createWebUrl('blacklist', array('op' => 'display')), 'success');
}

include $this->template('blacklist');