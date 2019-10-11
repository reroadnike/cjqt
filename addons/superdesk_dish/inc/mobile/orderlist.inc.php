<?php
/**
 * 我的订单
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:12 PM
 */

global $_GPC, $_W;
$weid = $this->_weid;
$from_user = $this->_fromuser;

$do = 'order';

$storeid = intval($_GPC['storeid']);
if ($storeid == 0) {
    $storeid = $this->getStoreID();
}
if (empty($storeid)) {
    message('请先选择门店', $this->createMobileUrl('waprestlist'));
}
$method = 'orderlist'; //method
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

$storelist = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid=:weid ORDER BY id DESC ", array(':weid' => $weid), 'id');

//已确认
$order_list_part1 = pdo_fetchall("SELECT a.* FROM " . tablename($this->table_order) . " AS a LEFT JOIN " . tablename($this->table_stores) . " AS b ON a.storeid=b.id  WHERE a.status=1 AND a.from_user='{$from_user}' ORDER BY a.id DESC LIMIT 20");
//数量
$order_total_part1 = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . " WHERE status=1 AND from_user='{$from_user}' ORDER BY id DESC");
foreach ($order_list_part1 as $key => $value) {
    $order_list_part1[$key]['goods'] = pdo_fetchall("SELECT a.*,b.title FROM " . tablename($this->table_order_goods) . " as a left join  " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.weid = '{$weid}' and a.orderid={$value['id']}");
}

//未确认
$order_list_part2 = pdo_fetchall("SELECT a.* FROM " . tablename($this->table_order) . " AS a LEFT JOIN " . tablename($this->table_stores) . " AS b ON a.storeid=b.id  WHERE a.status=0 AND a.from_user='{$from_user}' ORDER BY a.id DESC LIMIT 20");
//数量
$order_total_part2 = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . " WHERE status=0 AND from_user='{$from_user}' ORDER BY id DESC");
foreach ($order_list_part2 as $key => $value) {
    $order_list_part2[$key]['goods'] = pdo_fetchall("SELECT a.*,b.title FROM " . tablename($this->table_order_goods) . " AS a LEFT JOIN " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.weid = '{$weid}' and a.orderid={$value['id']}");
}

$order_list_part3 = pdo_fetchall("SELECT a.* FROM " . tablename($this->table_order) . " AS a LEFT JOIN " . tablename($this->table_stores) . " AS b ON a.storeid=b.id  WHERE (a.status=2) AND a.from_user='{$from_user}' ORDER BY a.id DESC LIMIT 20");
//数量
$order_total_part3 = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . " WHERE (status=2) AND from_user='{$from_user}' ORDER BY id DESC");
foreach ($order_list_part3 as $key => $value) {
    $order_list_part3[$key]['goods'] = pdo_fetchall("SELECT a.*,b.title FROM " . tablename($this->table_order_goods) . " as a left join  " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.weid = '{$weid}' and a.orderid={$value['id']}");
}

$order_list_part4 = pdo_fetchall("SELECT a.* FROM " . tablename($this->table_order) . " AS a LEFT JOIN " . tablename($this->table_stores) . " AS b ON a.storeid=b.id  WHERE (a.status=3) AND a.from_user='{$from_user}' ORDER BY a.id DESC LIMIT 20");
//数量
$order_total_part4 = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . " WHERE (status=3) AND from_user='{$from_user}' ORDER BY id DESC");
foreach ($order_list_part4 as $key => $value) {
    $order_list_part4[$key]['goods'] = pdo_fetchall("SELECT a.*,b.title FROM " . tablename($this->table_order_goods) . " as a left join  " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.weid = '{$weid}' and a.orderid={$value['id']}");
}

//智能点餐
$intelligents = pdo_fetchall("SELECT 1 FROM " . tablename($this->table_intelligent) . " WHERE weid={$weid} AND storeid={$storeid} GROUP BY name ORDER by name");

include $this->template('dish_order_list');