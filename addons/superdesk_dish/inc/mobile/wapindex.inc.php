<?php
/**
 *
 * 导航首页
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:00 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$method = 'wapindex'; //method
$authurl = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true) . '&authkey=1';
$url = $_W['siteroot'] . 'app/' . $this->createMobileUrl($method, array(), true);
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
    message('会话已经过时，请从微信端重新发送关键字登录！');
}

$storeid = intval($_GPC['storeid']);
if (!empty($storeid)) {
    $store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE id=:id", array(":id" => $storeid));
} else {
    $store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . "  WHERE weid=:weid ORDER BY id DESC LIMIT 1", array(':weid' => $weid));
    $storeid = $store['id'];
}

if (empty($store)) {
    message('商家不存在！');
}

$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid ORDER BY id DESC LIMIT 1", array(':weid' => $weid));
$title = $setting['title'];
if (!empty($setting)) {
    $storeid = $setting['storeid'];
} else {
    $store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . "  WHERE weid=:weid  ORDER BY id DESC LIMIT 1", array(':weid' => $weid));
    $storeid = $store['id'];
}

$nave = pdo_fetchall("SELECT * FROM " . tablename($this->table_nave) . " WHERE weid=:weid AND status=1 ORDER BY displayorder DESC,id DESC", array(':weid' => $weid));

include $this->template('dish_index');