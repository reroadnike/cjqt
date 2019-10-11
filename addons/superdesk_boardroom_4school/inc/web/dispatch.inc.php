<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 5:05 AM
 */

global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    $list = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_4school_s_dispatch') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (checksubmit('submit')) {
        $data = array(
            'uniacid' => $_W['uniacid'],
            'displayorder' => intval($_GPC['displayorder']),
            'dispatchtype' => intval($_GPC['dispatchtype']),
            'dispatchname' => $_GPC['dispatchname'],
            'express' => $_GPC['express'],
            'firstprice' => $_GPC['firstprice'],
            'firstweight' => $_GPC['firstweight'],
            'secondprice' => $_GPC['secondprice'],
            'secondweight' => $_GPC['secondweight'],
            'description' => $_GPC['description'],
            'enabled' => $_GPC['enabled']
        );
        if (!empty($id)) {
            pdo_update('superdesk_boardroom_4school_s_dispatch', $data, array('id' => $id));
        } else {
            pdo_insert('superdesk_boardroom_4school_s_dispatch', $data);
            $id = pdo_insertid();
        }
        message('更新配送方式成功！', $this->createWebUrl('dispatch', array('op' => 'display')), 'success');
    }
    //修改
    $dispatch = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_4school_s_dispatch') . " WHERE id = '$id' and uniacid = '{$_W['uniacid']}'");
    $express = pdo_fetchall("select * from " . tablename('superdesk_boardroom_4school_s_express') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY displayorder DESC");
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $dispatch = pdo_fetch("SELECT id FROM " . tablename('superdesk_boardroom_4school_s_dispatch') . " WHERE id = '$id' AND uniacid=" . $_W['uniacid'] . "");
    if (empty($dispatch)) {
        message('抱歉，配送方式不存在或是已经被删除！', $this->createWebUrl('dispatch', array('op' => 'display')), 'error');
    }
    pdo_delete('superdesk_boardroom_4school_s_dispatch', array('id' => $id));
    message('配送方式删除成功！', $this->createWebUrl('dispatch', array('op' => 'display')), 'success');
} else {
    message('请求方式不存在');
}
include $this->template('dispatch', TEMPLATE_INCLUDEPATH, true);