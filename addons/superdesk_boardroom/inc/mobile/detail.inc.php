<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 4:58 AM
 */

global $_W, $_GPC;
$goodsid = intval($_GPC['id']);
$goods = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_s_goods') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $goodsid, ':uniacid' => $_W['uniacid']));
if (empty($goods)) {
    message('抱歉，商品不存在或是已经被删除！');
}
if ($goods['istime'] == 1) {
    $backUrl = $this->createMobileUrl('list');
    $backUrl = $_W['siteroot'] . 'app' . ltrim($backUrl, '.');
    if (time() < $goods['timestart']) {
        message('抱歉，还未到购买时间, 暂时无法购物哦~', $backUrl, "error");
    }
    if (time() > $goods['timeend']) {
        message('抱歉，商品限购时间已到，不能购买了哦~', $backUrl, "error");
    }
}
$title = $goods['title'];
//浏览量
pdo_query("update " . tablename('superdesk_boardroom_s_goods') . " set viewcount=viewcount+1 where id=:id and uniacid='{$_W['uniacid']}' ", array(":id" => $goodsid));
$piclist1 = array(array("attachment" => $goods['thumb']));
$piclist = array();
if (is_array($piclist1)) {
    foreach ($piclist1 as $p) {
        $piclist[] = is_array($p) ? $p['attachment'] : $p;
    }
}
if ($goods['thumb_url'] != 'N;') {
    $urls = unserialize($goods['thumb_url']);
    if (is_array($urls)) {
        foreach ($urls as $p) {
            $piclist[] = is_array($p) ? $p['attachment'] : $p;
        }
    }
}
$marketprice = $goods['marketprice'];
$productprice = $goods['productprice'];
$originalprice = $goods['originalprice'];
$stock = $goods['total'];
//规格及规格项
$allspecs = pdo_fetchall("select * from " . tablename('superdesk_boardroom_s_spec') . " where goodsid=:id order by displayorder asc", array(':id' => $goodsid));
foreach ($allspecs as &$s) {
    $s['items'] = pdo_fetchall("select * from " . tablename('superdesk_boardroom_s_spec_item') . " where  `show`=1 and specid=:specid order by displayorder asc", array(":specid" => $s['id']));
}
unset($s);
//处理规格项
$options = pdo_fetchall("select id,title,thumb,marketprice,productprice,costprice, stock,weight,specs from " . tablename('superdesk_boardroom_s_goods_option') . " where goodsid=:id order by id asc", array(':id' => $goodsid));
//排序好的specs
$specs = array();
//找出数据库存储的排列顺序
if (count($options) > 0) {
    $specitemids = explode("_", $options[0]['specs']);
    foreach ($specitemids as $itemid) {
        foreach ($allspecs as $ss) {
            $items = $ss['items'];
            foreach ($items as $it) {
                if ($it['id'] == $itemid) {
                    $specs[] = $ss;
                    break;
                }
            }
        }
    }
}
$params = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_s_goods_param') . " WHERE goodsid=:goodsid order by displayorder asc", array(":goodsid" => $goods['id']));
$carttotal = $this->getCartTotal();
include $this->template('detail');