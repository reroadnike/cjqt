<?php
/**
 *
 * 智能点餐_选人数
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:10 PM
 */

global $_GPC, $_W;
$weid = $this->_weid;
$from_user = $this->_fromuser;

$title = '微点餐';
$storeid = intval($_GPC['storeid']);
if ($storeid == 0) {
    $storeid = $this->getStoreID();
}
if (empty($storeid)) {
    message('请先选择门店', $this->createMobileUrl('waprestlist'));
}
$method = 'wapselect'; //method
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

$intelligents = pdo_fetchall("SELECT * FROM " . tablename($this->table_intelligent) . " WHERE weid=:weid AND storeid=:storeid GROUP BY name ORDER by name", array(':weid' => $weid, ':storeid' => $storeid));
include $this->template('dish_select');