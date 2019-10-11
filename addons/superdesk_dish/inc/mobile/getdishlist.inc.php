<?php
/**
 * 取得商品列表
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:32 PM
 */

global $_W, $_GPC;
$weid = $this->_weid;
$from_user = $_GPC['from_user'];
$this->_fromuser = $from_user;

if (empty($from_user)) {
    message('会话已过期，请重新发送关键字!');
}

$storeid = intval($_GPC['storeid']);
$categoryid = intval($_GPC['categoryid']);
$list = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE weid = :weid AND status = 1 AND storeid=:storeid AND pcate=:pcate order by displayorder DESC,id DESC", array(':weid' => $weid, ':storeid' => $storeid, ':pcate' => $categoryid));

//        $result['debug'] = 'weid:'.$weid.'storeid'.$storeid.'cate:'.$categoryid;
//        message($result, '', 'ajax');

$dish_arr = $this->getDishCountInCart($storeid);

foreach ($list as $key => $row) {
    $subcount = intval($row['subcount']);
    $data[$key] = array(
        'id' => $row['id'],
        'title' => $row['title'],
        'dSpecialPrice' => $row['marketprice'],
        'dPrice' => $row['productprice'],
        'dDescribe' => $row['description'], //描述
        'dTaste' => $row['taste'], //口味
        'dSubCount' => $row['subcount'], //被点次数
        'thumb' => $row['thumb'],
        'unitname' => $row['unitname'],
        'dIsSpecial' => $row['isspecial'],
        'dIsHot' => $subcount > 20 ? 2 : 0,
        'total' => empty($dish_arr) ? 0 : intval($dish_arr[$row['id']]) //商品数量
    );
}
$result['data'] = $data;
$result['categoryid'] = $categoryid;

//json_encode($result)

message($result, '', 'ajax');