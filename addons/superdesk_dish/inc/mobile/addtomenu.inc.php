<?php
/**
 * 添加商品到菜单
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:29 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $_GPC['from_user'];
$this->_fromuser = $from_user;

if (empty($from_user)) {
    message('会话已过期，请重新发送关键字!');
}

$storeid = intval($_GPC['storeid']);

$clearMenu = intval($_GPC['clearMenu']);
//清空购物车
if ($clearMenu == 1) {
    pdo_delete('superdesk_dish_cart', array('weid' => $weid, 'from_user' => $from_user, 'storeid' => $storeid));
}

//添加菜单所属商品到
$intelligentid = intval($_GPC['intelligentid']);
$intelligent = pdo_fetch("SELECT * FROM " . tablename($this->table_intelligent) . " WHERE id={$intelligentid} limit 1");

if (!empty($intelligent)) {
    $goods = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE FIND_IN_SET(id, '{$intelligent['content']}') AND weid={$weid} AND storeid={$storeid}");

    foreach ($goods as $key => $item) {
        //查询购物车有没该商品
        $cart = pdo_fetch("SELECT * FROM " . tablename($this->table_cart) . " WHERE goodsid=:goodsid AND weid=:weid AND storeid=:storeid AND from_user='" . $from_user . "'", array(':goodsid' => $item['id'], ':weid' => $weid, ':storeid' => $storeid));
        if (empty($cart)) {
            //不存在的话增加商品点击量
            pdo_query("UPDATE " . tablename($this->table_goods) . " SET subcount=subcount+1 WHERE id=:id", array(':id' => $item['id']));
            //添加进购物车
            $data = array(
                'weid' => $weid,
                'storeid' => $item['storeid'],
                'goodsid' => $item['id'],
                'goodstype' => $item['pcate'],
                'price' => $item['isspecial'] == 1 ? $item['productprice'] : $item['marketprice'],
                'from_user' => $from_user,
                'total' => 1
            );
            pdo_insert($this->table_cart, $data);
        }
    }
}

//跳转
$url = $this->createMobileUrl('wapmenu', array('storeid' => $storeid), true);
die('<script>location.href = "' . $url . '";</script>');