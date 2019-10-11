<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:22 PM
 */

global $_GPC, $_W;
checklogin();
$action = 'emailsetting';
$title = $this->actions_titles[$action];
$storeid = intval($_GPC['storeid']);

$store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid = :weid AND id=:storeid ORDER BY `id` DESC", array(':weid' => $_W['uniacid'], ':storeid' => $storeid));
if (empty($store)) {
    message('非法操作.');
}

$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_email_setting) . " WHERE weid = :weid AND storeid=:storeid", array(':weid' => $_W['uniacid'], ':storeid' => $storeid));

if (checksubmit('submit')) {
    if (empty($_GPC['email_send']) || empty($_GPC['email_user']) || empty($_GPC['email_pwd'])) {
        message('请完整填写邮件配置信息', 'refresh', 'error');
    }
    if ($_GPC['email_host'] == 'smtp.qq.com' || $_GPC['email_host'] == 'smtp.gmail.com') {
        $secure = 'ssl';
        $port = '465';
    } else {
        $secure = 'tls';
        $port = '25';
    }
    //$result = $this->sendmail($_GPC['email_host'], $secure, $port, $_GPC['email_send'], $_GPC['email_user'], $_GPC['email_pwd'], $_GPC['email_send']);
    //public function sendmail($cfghost,$cfgsecure,$cfgport,$cfgsendmail,$cfgsenduser,$cfgsendpwd,$mailaddress) {

    $mail_config = array();
    $mail_config['host'] = $_GPC['email_host'];
    $mail_config['secure'] = $secure;
    $mail_config['port'] = $port;
    $mail_config['username'] = $_GPC['email_user'];
    $mail_config['sendmail'] = $_GPC['email_send'];
    $mail_config['password'] = $_GPC['email_pwd'];
    $mail_config['mailaddress'] = $_GPC['email'];
    $mail_config['subject'] = '微点餐提醒';
    $mail_config['body'] = '邮箱测试';


    $data = array(
        'weid' => $_W['uniacid'],
        'storeid' => $storeid,
        'email_enable' => intval($_GPC['email_enable']),
        'email_host' => $_GPC['email_host'],
        'email_send' => $_GPC['email_send'],
        'email_pwd' => $_GPC['email_pwd'],
        'email_user' => $_GPC['email_user'],
        'email' => trim($_GPC['email']),
        'email_business_tpl' => trim($_GPC['email_business_tpl']),
        'dateline' => TIMESTAMP
    );

    if (empty($setting)) {
        pdo_insert($this->table_email_setting, $data);
    } else {
        unset($data['dateline']);
        pdo_update($this->table_email_setting, $data, array('weid' => $_W['uniacid'], 'storeid' => $storeid));
    }

    $result = $this->sendmail($mail_config);
    if ($result == 1) {
        message('邮箱配置成功', 'refresh');
    } else {
        message('邮箱配置信息有误', 'refresh', 'error');
    }

    message('操作成功', $this->createWebUrl('emailsetting', array('storeid' => $storeid)), 'success');
}
include $this->template('email_setting');