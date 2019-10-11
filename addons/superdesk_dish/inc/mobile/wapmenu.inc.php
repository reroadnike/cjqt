<?php
/**
 *
 * 我的菜单
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:02 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;

$title = '我的菜单';
$do = 'menu';
$storeid = intval($_GPC['storeid']);

$this->check_black_list();
$mealtimes = pdo_fetchall("SELECT * FROM " . tablename($this->table_mealtime) . " WHERE weid=:weid AND storeid=0 ORDER BY id ASC", array(':weid' => $weid));
$select_mealtime = '';
foreach ($mealtimes as $key => $value) {
    $tmptime = intval(strtotime(date('Y-m-d ') . $value['begintime']));
    $nowtime = intval(TIMESTAMP + 900 * 1);
//            if ($tmptime > $nowtime) {//debug
    $select_mealtime .= '<option value="' . $value['begintime'] . '~' . $value['endtime'] . '">' . $value['begintime'] . '~' . $value['endtime'] . '</option>';
//            }
}

//message($select_mealtime);

//        {loop $mealtimes $item}
//        <option value="{$item['begintime']}~{$item['endtime']}">{$item['begintime']}~{$item['endti//                                {/loop}
//$inhour = $this->check_mealtime();
//        if ($inhour == 0) {
//            message('店铺未在营业时间中!');
//        }

if (empty($storeid)) {
    message('请先选择门店', $this->createMobileUrl('waprestlist'));
}

$method = 'wapmenu'; //method
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
//        if (empty($from_user)) {
//            message('会话已过期，请重新发送关键字!');
//        }

$store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid=:weid AND id=:id LIMIT 1", array(':weid' => $weid, ':id' => $storeid));
$flag = false;
$issms = intval($store['is_sms']);
$checkcode = pdo_fetch("SELECT * FROM " . tablename('superdesk_dish_sms_checkcode') . " WHERE weid = :weid  AND from_user=:from_user AND status=1 ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':from_user' => $from_user));
if ($issms == 1 && empty($checkcode)) {
    $flag = true;
}

$user = pdo_fetch("SELECT * FROM " . tablename('superdesk_dish_address') . " WHERE weid = :weid  AND from_user=:from_user ORDER BY `id` DESC limit 1", array(':weid' => $weid, ':from_user' => $from_user));

//        $user = fans_search($from_user);

$setting = pdo_fetch("SELECT * FROM " . tablename($this->table_setting) . " WHERE weid=:weid LIMIT 1", array(':weid' => $weid));

$cart = pdo_fetchall("SELECT * FROM " . tablename($this->table_cart) . " a LEFT JOIN " . tablename('superdesk_dish_goods') . " b ON a.goodsid=b.id WHERE a.weid=:weid AND a.from_user=:from_user AND a.storeid=:storeid", array(':weid' => $weid, ':from_user' => $from_user, ':storeid' => $storeid));

if (!empty($from_user) && !(empty($weid))) {
    $order = pdo_fetch("SELECT * FROM " . tablename($this->table_order) . " WHERE weid=:weid AND from_user=:from_user ORDER BY id DESC LIMIT 1", array(':from_user' => $from_user, ':weid' => $weid));
}

$my_order_total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . " WHERE storeid=:storeid AND from_user=:from_user ", array(':from_user' => $from_user, ':storeid' => $storeid));
$my_order_total = intval($my_order_total);

include $this->template('dish_menu');