<?php
/**
 * Created by PhpStorm.
 * User: zjh
 * Date: 2018年9月8日 15:04:02
 *
 * 企业采购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_goods_insert_costprice
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_goods_insert_costprice
 */

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');

global $_W;
global $_GPC;

/**
 * -- 订单商品表进货价补丁.
 * update ims_superdesk_shop_order_goods as og
 * LEFT JOIN ims_superdesk_shop_goods as g on og.goodsid = g.id
 * set og.costprice = IFNULL(g.costprice,0)
 * where og.costprice = 0
 */
die();

//正在显示第 0 - 24 行 (共 13844 行, 查询花费 0.0008 秒。)
//SELECT * FROM ims_superdesk_shop_order_goods WHERE costprice = 0.00
//die;

//mark kafka 为了kafka转成了model执行
$_order_goodsModel    = new order_goodsModel();

$page      = isset($_GPC['page']) && !empty($_GPC['page']) ? $_GPC['page'] : 1;
$page_size = isset($_GPC['page_size']) && !empty($_GPC['page_size']) ? $_GPC['page_size'] : 100;

$order_goods_list = pdo_fetchall(
    ' select og.id,g.costprice from ' . tablename('superdesk_shop_order_goods') . ' as og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
    ' left join ' . tablename('superdesk_shop_goods') . ' as g on g.id = og.goodsid ' .
    ' where og.uniacid = :uniacid ' .
    '   and og.costprice = 0 ' .    //查找订单商品表中没有进货价的
    '   and g.costprice is not null ' . //查找有对应商品的
    '   and g.costprice != 0 ' .
    ' limit ' . (($page - 1) * $page_size) . ',' . $page_size ,    //查找对应商品有填写进货价的
    array(':uniacid' => $_W['uniacid'])
);

if (count($order_goods_list) > 0) {
    foreach ($order_goods_list as $v) {
        //mark kafka 为了kafka转成了model执行
        $_order_goodsModel->updateByColumn(
            array(
                'costprice' => $v['costprice']
            ),
            array(
                'id' => $v['id']
            )
        );

    }
}

show_json(1, $order_goods_list);