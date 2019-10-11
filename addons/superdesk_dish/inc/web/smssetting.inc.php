<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:23 PM
 */

global $_GPC, $_W;
checklogin();
$action = 'smssetting';
$title = $this->actions_titles[$action];
$storeid = intval($_GPC['storeid']);

$store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid AND id=:storeid ORDER BY `id` DESC", array(':weid' => $_W['uniacid'], ':storeid' => $storeid));
if (empty($store)) {
    message('非法操作.');
}

$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_sms_setting) . " WHERE weid = :weid AND storeid=:storeid", array(':weid' => $_W['uniacid'], ':storeid' => $storeid));
if (checksubmit('submit')) {
    $data = array(
        'weid' => $_W['uniacid'],
        'storeid' => $storeid,
        'sms_enable' => intval($_GPC['sms_enable']),
        'sms_username' => trim($_GPC['sms_username']),
        'sms_pwd' => trim($_GPC['sms_pwd']),
        'sms_verify_enable' => intval($_GPC['sms_verify_enable']),
        'sms_mobile' => trim($_GPC['sms_mobile']),
        'sms_business_tpl' => trim($_GPC['sms_business_tpl']),
        'dateline' => TIMESTAMP
    );

    if (empty($setting)) {
        pdo_insert($this->table_sms_setting, $data);
    } else {
        unset($data['dateline']);
        pdo_update($this->table_sms_setting, $data, array('weid' => $_W['uniacid'], 'storeid' => $storeid));
    }
    message('操作成功', $this->createWebUrl('smssetting', array('storeid' => $storeid)), 'success');
}
include $this->template('sms_setting');