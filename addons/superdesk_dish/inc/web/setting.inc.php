<?php
/**
 * 基本设置
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:20 PM
 */

global $_W, $_GPC;
$GLOBALS['frames'] = $this->getNaveMenu();

$weid = $this->_weid;
$action = 'setting';
$title = '网站设置';

load()->func('tpl');

$stores = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid ORDER BY `id` DESC", array(':weid' => $_W['uniacid']));
if (empty($stores)) {
    $url = $this->createWebUrl('stores', array('op' => 'display'));
    message('请先添加门店', $url);
}

$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid = :weid", array(':weid' => $_W['uniacid']));
if (checksubmit('submit')) {
    $data = array(
        'weid' => $_W['uniacid'],
        'title' => trim($_GPC['title']),
        'thumb' => trim($_GPC['thumb']),
        'storeid' => intval($_GPC['storeid']),
        'entrance_type' => intval($_GPC['entrance_type']),
        'entrance_storeid' => intval($_GPC['entrance_storeid']),
        'order_enable' => intval($_GPC['order_enable']),
        'dining_mode' => intval($_GPC['dining_mode']),
        'istplnotice' => intval($_GPC['istplnotice']),
        'tplneworder' => trim($_GPC['tplneworder']),
        'searchword' => trim($_GPC['searchword']),
        'tpluser' => trim($_GPC['tpluser']),
        'dateline' => TIMESTAMP
    );

    if (empty($setting)) {
        pdo_insert($this->table_setting, $data);
    } else {
        unset($data['dateline']);
        pdo_update($this->table_setting, $data, array('weid' => $_W['uniacid']));
    }

    if (is_array($_GPC['begintime'])) {
        foreach ($_GPC['begintime'] as $id => $val) {
            $begintime = $_GPC['begintime'][$id];
            $endtime = $_GPC['endtime'][$id];
            if (empty($begintime) || empty($endtime)) {
                continue;
            }

            $data = array(
                'weid' => $weid,
                'storeid' => 0,
                'begintime' => $begintime,
                'endtime' => $endtime,
            );
            pdo_update('superdesk_dish_mealtime', $data, array('id' => $id));
        }
    }

    //增加
    if (is_array($_GPC['newbegintime'])) {
        foreach ($_GPC['newbegintime'] as $nid => $val) {
            $begintime = $_GPC['newbegintime'][$nid];
            $endtime = $_GPC['newendtime'][$nid];
            if (empty($begintime) || empty($endtime)) {
                continue;
            }

            $data = array(
                'weid' => $weid,
                'storeid' => 0,
                'begintime' => $begintime,
                'endtime' => $endtime,
                'dateline' => TIMESTAMP
            );
            pdo_insert('superdesk_dish_mealtime', $data);
        }
    }
    message('操作成功', $this->createWebUrl('setting'), 'success');
}

$timelist = pdo_fetchall("SELECT * FROM " . tablename('superdesk_dish_mealtime') . " WHERE weid = '{$_W['uniacid']}' order by id");

include $this->template('setting');