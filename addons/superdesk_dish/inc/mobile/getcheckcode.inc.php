<?php
/**
 *
 * 取得短信验证码
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:26 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = trim($_GPC['from_user']);
$this->_fromuser = $from_user;
$mobile = trim($_GPC['mobile']);
$storeid = intval($_GPC['storeid']);

if (!preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|147[0-9]{8}$/", $mobile)) {
    $this->showMsg('手机号码格式不对!');
}

$passcheckcode = pdo_fetch("SELECT * FROM " . tablename('superdesk_dish_sms_checkcode') . " WHERE weid = :weid  AND from_user=:from_user AND status=1 ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':from_user' => $from_user));
if (!empty($passcheckcode)) {
    $this->showMsg('发送成功!', 1);
}

$smsSetting = pdo_fetch("SELECT * FROM " . tablename($this->table_sms_setting) . " WHERE weid=:weid AND storeid=:storeid LIMIT 1", array(':weid' => $weid, ':storeid' => $storeid));
if (empty($smsSetting)) {
    $this->showMsg('请先选择门店!');
}

$checkCodeCount = pdo_fetchcolumn("SELECT count(1) FROM " . tablename('superdesk_dish_sms_checkcode') . " WHERE weid = :weid  AND from_user=:from_user ", array(':weid' => $weid, ':from_user' => $from_user));
if ($checkCodeCount >= 3) {
    $this->showMsg('您请求的验证码已超过最大限制..' . $checkCodeCount);
}

//判断数据是否已经存在
$data = pdo_fetch("SELECT * FROM " . tablename('superdesk_dish_sms_checkcode') . " WHERE weid = :weid  AND from_user=:from_user ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':from_user' => $from_user));
if (!empty($data)) {
    if (TIMESTAMP - $data['dateline'] < 60) {
        $this->showMsg('每分钟只能获取短信一次!');
    }
}

//验证码
$checkcode = random(6, 1);
$checkcode = $this->getNewCheckCode($checkcode);
$data = array(
    'weid' => $weid,
    'from_user' => $from_user,
    'mobile' => $mobile,
    'checkcode' => $checkcode,
    'status' => 0,
    'dateline' => TIMESTAMP
);

$sendInfo = array();
$sendInfo['username'] = $smsSetting['sms_username'];
$sendInfo['pwd'] = $smsSetting['sms_pwd'];
//$sendInfo['mobile'] = $smsSetting['sms_mobile'];
$sendInfo['mobile'] = $mobile;
$sendInfo['content'] = "您的验证码是：" . $checkcode . "。如需帮助请联系客服。";
$return_result_code = $this->_sendSms($sendInfo);
if ($return_result_code != '100') {
    $code_msg = $this->sms_status[$return_result_code];
    $this->showMsg($code_msg . $return_result_code);
} else {
    pdo_insert('superdesk_dish_sms_checkcode', $data);
    $this->showMsg('发送成功!', 1);
}