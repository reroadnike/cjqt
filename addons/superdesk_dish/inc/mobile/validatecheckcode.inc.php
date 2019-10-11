<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:27 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $_GPC['from_user'];
$this->_fromuser = $from_user;
$mobile = trim($_GPC['mobile']);
$checkcode = trim($_GPC['checkcode']);

if (empty($mobile)) {
    $this->showMsg('请输入手机号码!');
}

if (empty($checkcode)) {
    $this->showMsg('请输入验证码!');
}

$item = pdo_fetch("SELECT * FROM " . tablename('superdesk_dish_sms_checkcode') . " WHERE weid = :weid  AND from_user=:from_user AND checkcode=:checkcode ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':from_user' => $from_user, ':checkcode' => $checkcode));

if (empty($item)) {
    $this->showMsg('验证码输入错误!');
} else {
    pdo_update('superdesk_dish_sms_checkcode', array('status' => 1), array('id' => $item['id']));
}

$this->showMsg('验证成功!', 1);