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
$id = intval($_GPC['orderid']);

$order = pdo_fetch("SELECT a.* FROM " . tablename($this->table_order) . " AS a LEFT JOIN " . tablename($this->table_stores) . " AS b ON a.storeid=b.id  WHERE a.id ={$id} AND a.from_user='{$from_user}' ORDER BY a.id DESC LIMIT 20");

$store = pdo_fetch("SELECT * FROM " . tablename($this->table_stores) . " where weid = :weid AND id=:id ORDER BY displayorder DESC", array(':weid' => $weid, ':id' => $order['storeid']));

$order['goods'] = pdo_fetchall("SELECT a.*,b.title FROM " . tablename($this->table_order_goods) . " as a left join  " . tablename($this->table_goods) . " as b on a.goodsid=b.id WHERE a.weid = '{$weid}' and a.orderid={$order['id']}");

include $this->template('diandan/orderdetail');