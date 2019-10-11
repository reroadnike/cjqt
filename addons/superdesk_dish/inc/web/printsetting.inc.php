<?php
/**
 *
 * 打印机设置
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:21 PM
 */

global $_GPC, $_W;
checklogin();
$action = 'printsetting';
$title = $this->actions_titles[$action];
$storeid = intval($_GPC['storeid']);
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid AND id=:storeid ORDER BY `id` DESC", array(':weid' => $_W['uniacid'], ':storeid' => $storeid));
if (empty($store)) {
    message('非法操作！门店不存在.');
}

if ($operation == 'display') {
    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE weid = :weid AND storeid=:storeid", array(':weid' => $_W['uniacid'], ':storeid' => $storeid));
    $print_order_count = pdo_fetchall("SELECT print_usr,COUNT(1) as count FROM " . tablename($this->table_print_order) . "  GROUP BY print_usr,weid having weid = :weid", array(':weid' => $_W['weid']), 'print_usr');
} else if ($operation == 'post') {
    $id = intval($_GPC['id']);
    $setting = pdo_fetch("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE weid = :weid AND storeid=:storeid AND id=:id", array(':weid' => $_W['uniacid'], ':storeid' => $storeid, ':id' => $id));
    if (checksubmit('submit')) {
        $data = array(
            'weid' => $_W['uniacid'],
            'storeid' => $storeid,
            'weid' => $_W['uniacid'],
            'title' => trim($_GPC['title']),
            'type' => trim($_GPC['type']),
            'member_code' => trim($_GPC['member_code']),
            'feyin_key' => trim($_GPC['feyin_key']),
            'print_status' => trim($_GPC['print_status']),
            'print_type' => trim($_GPC['print_type']),
            'print_usr' => trim($_GPC['print_usr']),
            'print_nums' => 1,
            'print_top' => trim($_GPC['print_top']),
            'print_bottom' => trim($_GPC['print_bottom']),
            'qrcode_status' => intval($_GPC['qrcode_status']),
            'qrcode_url' => trim($_GPC['qrcode_url']),
            'dateline' => TIMESTAMP
        );
        if (empty($setting)) {
            $flag = pdo_fetch("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE print_usr=:print_usr LIMIT 1", array(':print_usr' => trim($_GPC['print_usr'])));
            if (!empty($flag)) {
                message('打印机终端编号已经被使用,不能重复添加！', $this->createWebUrl('printsetting', array('storeid' => $storeid)), 'success');
            }
            pdo_insert($this->table_print_setting, $data);
        } else {
            unset($data['dateline']);
            $flag = pdo_fetch("SELECT * FROM " . tablename($this->table_print_setting) . " WHERE print_usr=:print_usr AND id<>:id LIMIT 1", array(':print_usr' => trim($_GPC['print_usr']), ':id' => $id));
            if (!empty($flag)) {
                message('打印机终端编号已经被使用,不能重复添加！', $this->createWebUrl('printsetting', array('storeid' => $storeid)), 'success');
            }

            pdo_update($this->table_print_setting, $data, array('weid' => $_W['uniacid'], 'storeid' => $storeid, 'id' => $id));
        }
        message('操作成功', $this->createWebUrl('printsetting', array('storeid' => $storeid)), 'success');
    }
} else if ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $print = pdo_fetch("SELECT id FROM " . tablename($this->table_print_setting) . " WHERE id = '$id'");
    if (empty($print)) {
        message('抱歉，不存在或是已经被删除！', $this->createWebUrl('printsetting', array('op' => 'display', 'storeid' => $storeid)), 'error');
    }

    pdo_delete($this->table_print_setting, array('id' => $id, 'weid' => $_W['uniacid']));
    message('删除成功！', $this->createWebUrl('printsetting', array('op' => 'display', 'storeid' => $storeid)), 'success');
}

include $this->template('print_setting');