<?php
/**
 *
 * 商品列表
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:01 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;

$title = '全部商品';
$do = 'list';

$storeid = intval($_GPC['storeid']);
if ($storeid == 0) {
    $storeid = $this->getStoreID();
}
if (empty($storeid)) {
    message('请先选择门店', $this->createMobileUrl('waprestlist'));
}

$method = 'waplist'; //method
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

$store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . "  WHERE weid=:weid AND id=:id ORDER BY id DESC LIMIT 1", array(':weid' => $weid, ':id' => $storeid));

$pindex = max(1, intval($_GPC['page']));
$psize = 20;
$condition = '';

if (!empty($_GPC['ccate'])) {
    $cid = intval($_GPC['ccate']);
    $condition .= " AND ccate = '{$cid}'";
} elseif (!empty($_GPC['pcate'])) {
    $cid = intval($_GPC['pcate']);
    $condition .= " AND pcate = '{$cid}'";
}

$children = array();
$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = :weid AND storeid=:storeid ORDER BY  displayorder DESC,id DESC", array(':weid' => $weid, ':storeid' => $storeid));

$cid = intval($category[0]['id']);
$category_in_cart = pdo_fetchall("SELECT goodstype,count(1) as 'goodscount' FROM " . tablename($this->table_cart) . " GROUP BY weid,storeid,goodstype,from_user  having weid = '{$weid}' AND storeid='{$storeid}' AND from_user='{$from_user}'");
$category_arr = array();
foreach ($category_in_cart as $key => $value) {
    $category_arr[$value['goodstype']] = $value['goodscount'];
}

$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE weid = '{$weid}' AND storeid={$storeid} AND status = '1' AND pcate={$cid} ORDER BY displayorder DESC, subcount DESC, id DESC ");

$dish_arr = $this->getDishCountInCart($storeid);

$cart = pdo_fetchall("SELECT * FROM " . tablename($this->table_cart) . " WHERE  storeid=:storeid AND from_user=:from_user AND weid=:weid", array(':storeid' => $storeid, ':from_user' => $from_user, ':weid' => $weid));
$totalcount = 0;
$totalprice = 0;
foreach ($cart as $key => $value) {
    $totalcount = $totalcount + $value['total'];
    $totalprice = $totalprice + $value['total'] * $value['price'];
}

//智能点餐
$intelligents = pdo_fetchall("SELECT 1 FROM " . tablename($this->table_intelligent) . " WHERE weid={$weid} AND storeid={$storeid} GROUP BY name ORDER by name");

include $this->template('dish_list');