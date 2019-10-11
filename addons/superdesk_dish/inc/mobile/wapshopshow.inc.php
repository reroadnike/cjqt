<?php
/**
 *
 * 门店实景
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:09 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$title = '商家店面';

$storeid = intval($_GPC['storeid']);
if (empty($storeid)) {
    message('请先选择门店', $this->createMobileUrl('waprestlist'));
}

$method = 'wapshopshow'; //method
$authurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array('storeid' => $storeid), true) . '&authkey=1';
$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array('storeid' => $storeid), true);
if (isset($_COOKIE[$this->_auth2_openid])) {
    $from_user = $_COOKIE[$this->_auth2_openid];
    $nickname = $_COOKIE[$this->_auth2_nickname];
    $headimgurl = $_COOKIE[$this->_auth2_headimgurl];
} else {
    if (isset($_GPC['code'])) {
        $userinfo = $this->oauth2($authurl);
        if (!empty($userinfo)) {
            $from_user = $userinfo["openid"];
            $nickname = $userinfo["nickname"];
            $headimgurl = $userinfo["headimgurl"];
        } else {
            message('授权失败!');
        }
    } else {
        if (!empty($this->_appsecret)) {
            $this->getCode($url);
        }
    }
}

if (empty($from_user)) {
    message('会话已过期，请重新发送关键字!');
}

$store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE id=:id", array(':id' => $storeid));
if (empty($store)) {
    message('没有相关数据!');
}

$store['thumb_url'] = unserialize($store['thumb_url']);

include $this->template('dish_shop_show');