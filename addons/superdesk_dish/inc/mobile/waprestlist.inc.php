<?php
/**
 *
 * 门店列表
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:03 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$do = 'rest';
$title = '我的菜单';
$areaid = intval($_GPC['areaid']);
$typeid = intval($_GPC['typeid']);
$sortid = intval($_GPC['sortid']);
$lat = trim($_GPC['lat']);
$lng = trim($_GPC['lng']);


$method = 'waprestlist'; //method
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

//        if (empty($from_user)) {
//            message('会话已过期，请重新发送关键字!');
//        }

if ($areaid != 0) {
    $strwhere = " AND areaid={$areaid} ";
}

if ($typeid != 0) {
    $strwhere .= " AND typeid={$typeid} ";
}

//所属区域
$area = pdo_fetchall("SELECT * FROM " . tablename($this->table_area) . " where weid = :weid ORDER BY displayorder DESC", array(':weid' => $weid), 'id');
$curarea = "全城";
if (!empty($area[$areaid]['name'])) {
    $curarea = $area[$areaid]['name'];
}
//门店类型
$shoptype = pdo_fetchall("SELECT * FROM " . tablename($this->table_type) . " where weid = :weid ORDER BY displayorder DESC", array(':weid' => $weid), 'id');
$curtype = "门店类型";
if (!empty($shoptype[$areaid]['name'])) {
    $curtype = $shoptype[$areaid]['name'];
}

pdo_update($this->table_stores, array('is_rest' => 0));
pdo_query("UPDATE " . tablename($this->table_stores) . " SET is_rest=1 WHERE date_format(now(),'%H:%i') between begintime and endtime");

if ($sortid == 1) {
    $restlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " where weid = :weid and is_show=1 {$strwhere} ORDER BY is_rest DESC,displayorder DESC, id DESC", array(':weid' => $weid));
} else if ($sortid == 2) {
    $restlist = pdo_fetchall("SELECT *,(lat-:lat) * (lat-:lat) + (lng-:lng) * (lng-:lng) as dist FROM " . tablename($this->table_stores) . " WHERE weid = :weid and is_show=1 ORDER BY dist, displayorder DESC,id DESC", array(':weid' => $weid, ':lat' => $lat, ':lng' => $lng));
} else {
    $restlist = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " where weid = :weid and is_show=1 {$strwhere} ORDER BY is_rest DESC,displayorder DESC, id DESC", array(':weid' => $weid));
}

//        include $this->template('dish_rest_list');
include $this->template('diandan/restlist');