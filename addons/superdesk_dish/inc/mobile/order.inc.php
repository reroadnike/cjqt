<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:07 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $this->_fromuser;
$status = 0;

if (!empty($_GPC['status'])) {
    $status = intval($_GPC['status']);
}

$do = 'order';
$method = 'order'; //method
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

$storelist = pdo_fetchall("SELECT * FROM " . tablename($this->table_stores) . " WHERE weid=:weid ORDER BY id DESC ", array(':weid' => $weid), 'id');

//已确认
$order_list = pdo_fetchall("SELECT a.* FROM " . tablename($this->table_order) . " AS a LEFT JOIN " . tablename($this->table_stores) . " AS b ON a.storeid=b.id  WHERE a.status={$status} AND a.from_user='{$from_user}' ORDER BY a.id DESC LIMIT 20");
//数量
$order_total = pdo_fetchcolumn("SELECT COUNT(1) FROM " . tablename($this->table_order) . " WHERE status=1 AND from_user='{$from_user}' ORDER BY id DESC");
foreach ($order_list as $key => $value) {
    $order_list[$key]['goods'] = pdo_fetchall("SELECT a.*,b.title FROM " . tablename($this->table_order_goods) . " as a left join  " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.weid = '{$weid}' and a.orderid={$value['id']}");
}

include $this->template('diandan/order');