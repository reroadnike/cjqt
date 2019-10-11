<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');

class Virtual_SuperdeskShopV2ComModel extends ComModel
{
    private $_orderModel;


    public function __construct()
    {

        global $_W;
        global $_GPC;

        $this->_orderModel = new orderModel();
    }

    public function updateGoodsStock($shop_goods_id = 0)
    {
        global $_W;
        global $_GPC;

        $shop_goods = pdo_fetch(
            'select `virtual`,merchid ' .
            ' from ' . tablename('superdesk_shop_goods') .
            ' where ' .
            '       id=:id ' .
            '       and type=3 ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $shop_goods_id,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($shop_goods)) {
            return NULL;
        }

        $merchid = $shop_goods['merchid'];
        $stock   = 0;

        if (!empty($shop_goods['virtual'])) {

            $stock = pdo_fetchcolumn(
                'select count(*) ' .
                ' from ' . tablename('superdesk_shop_virtual_data') .
                ' where ' .
                '       typeid=:typeid ' .
                '       and uniacid=:uniacid ' .
                '       and merchid=:merchid ' .
                '       and openid=\'\' ' .
                ' limit 1',
                array(
                    ':typeid'  => $shop_goods['virtual'],
                    ':uniacid' => $_W['uniacid'],
                    ':merchid' => $merchid
                )
            );

        } else {

            $virtuals = array();

            $alloptions = pdo_fetchall(
                'select id, `virtual` ' .
                ' from ' . tablename('superdesk_shop_goods_option') .
                ' where ' .
                '       goodsid=' . $shop_goods_id
            );

            foreach ($alloptions as $opt) {

                if (empty($opt['virtual'])) {
                    continue;
                }

                $c = pdo_fetchcolumn(
                    'select count(*) ' .
                    ' from ' . tablename('superdesk_shop_virtual_data') .
                    ' where ' .
                    '       typeid=:typeid ' .
                    '       and uniacid=:uniacid ' .
                    '       and merchid=:merchid ' .
                    '       and openid=\'\' ' .
                    ' limit 1',
                    array(
                        ':typeid'  => $opt['virtual'],
                        ':uniacid' => $_W['uniacid'],
                        ':merchid' => $merchid
                    )
                );

                pdo_update(
                    'superdesk_shop_goods_option',
                    array(
                        'stock' => $c
                    ),
                    array(
                        'id' => $opt['id']
                    )
                );

                if (!in_array($opt['virtual'], $virtuals)) {
                    $virtuals[] = $opt['virtual'];
                    $stock      += $c;
                }
            }
        }

        pdo_update(
            'superdesk_shop_goods',
            array(
                'total' => $stock
            ),
            array(
                'id' => $shop_goods_id
            )
        );
    }

    /**
     * 更新 库存
     *
     * @param int $typeid
     */
    public function updateStock($typeid = 0)
    {
        global $_W;

        $goodsids = array();

        $goods = pdo_fetchall(
            'select id ' .
            ' from ' . tablename('superdesk_shop_goods') .
            ' where ' .
            '       type=3 ' .
            '       and `virtual`=:virtual ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':virtual' => $typeid,
                ':uniacid' => $_W['uniacid']
            )
        );

        foreach ($goods as $g) {
            $goodsids[] = $g['id'];
        }

        $alloptions = pdo_fetchall(
            'select id, goodsid ' .
            ' from ' . tablename('superdesk_shop_goods_option') .
            ' where ' .
            '       `virtual`=:virtual ' .
            '       and uniacid=:uniacid',
            array(
                ':uniacid' => $_W['uniacid'],
                ':virtual' => $typeid
            )
        );

        foreach ($alloptions as $opt) {

            if (!in_array($opt['goodsid'], $goodsids)) {
                $goodsids[] = $opt['goodsid'];
            }
        }

        foreach ($goodsids as $gid) {
            $this->updateGoodsStock($gid);
        }
    }

    public function pay($shop_order)
    {
        global $_W;
        global $_GPC;

        $shop_order_goods = pdo_fetch(
            'select id,goodsid,total,realprice ' .
            ' from ' . tablename('superdesk_shop_order_goods') . // TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' where ' .
            '       orderid=:orderid ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':orderid' => $shop_order['id']
            )
        );

        $shop_goods = pdo_fetch(
            'select id,credit,sales,salesreal ' .
            ' from ' . tablename('superdesk_shop_goods') .
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':id'      => $shop_order_goods['goodsid']
            )
        );

        $virtual_data = pdo_fetchall(
            'SELECT id,typeid,fields ' .
            ' FROM ' . tablename('superdesk_shop_virtual_data') .
            ' WHERE ' .
            '       typeid=:typeid ' .
            '       and openid=:openid ' .
            '       and uniacid=:uniacid ' .
            '       and merchid = :merchid ' .
            ' order by rand() ' .
            ' limit ' . $shop_order_goods['total'],
            array(
                ':openid'  => '',
                ':typeid'  => $shop_order['virtual'],
                ':uniacid' => $_W['uniacid'],
                ':merchid' => $shop_order['merchid']
            )
        );

        $type = pdo_fetch(
            'select fields ' .
            ' from ' . tablename('superdesk_shop_virtual_type') .
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and merchid = :merchid ' .
            ' limit 1 ',
            array(
                ':id'      => $shop_order['virtual'],
                ':uniacid' => $_W['uniacid'],
                ':merchid' => $shop_order['merchid']
            )
        );

        $fields = iunserializer($type['fields'], true);

        $virtual_info = array();
        $virtual_str  = array();

        foreach ($virtual_data as $vd) {

            $virtual_info[] = $vd['fields'];

            $strs = array();

            $vddatas = iunserializer($vd['fields']);

            foreach ($vddatas as $vk => $vv) {
                $strs[] = $fields[$vk] . ': ' . $vv;
            }

            $virtual_str[] = implode(' ', $strs);

            pdo_update(
                'superdesk_shop_virtual_data',
                array(
                    'openid'  => $shop_order['openid'],
                    'orderid' => $shop_order['id'],
                    'ordersn' => $shop_order['ordersn'],
                    'price'   => round($shop_order_goods['realprice'] / $shop_order_goods['total'], 2),
                    'usetime' => time()
                ),
                array(
                    'id' => $vd['id']
                )
            );

            pdo_update(
                'superdesk_shop_virtual_type',
                'usedata=usedata+1',
                array(
                    'id' => $vd['typeid']
                )
            );

            $this->updateStock($vd['typeid']);
        }

        $virtual_str  = implode("\n", $virtual_str);
        $virtual_info = '[' . implode(',', $virtual_info) . ']';

        $time = time();

        //mark kafka 为了kafka转成了model执行
        $this->_orderModel->updateByColumn(
            array(
                'virtual_info' => $virtual_info,
                'virtual_str'  => $virtual_str,
                'status'       => '3',
                'paytime'      => $time,
                'sendtime'     => $time,
                'finishtime'   => $time
            ),
            array(
                'id' => $shop_order['id']
            )
        );

        $credits = $shop_order_goods['total'] * $shop_goods['credit'];

        if (0 < $credits) {

            $shopset = m('common')->getSysset('shop');

            m('member')->setCredit($shop_order['openid'], $shop_order['core_user'],
                'credit1',
                $credits,
                array(0, $shopset['name'] . '购物积分 订单号: ' . $shop_order['ordersn'])
            );
        }

        $salesreal = pdo_fetchcolumn(
            ' select ifnull(sum(total),0) ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_order') . ' o on o.id = og.orderid ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where ' .
            '       og.goodsid=:goodsid ' .
            '       and o.status>=1 ' .
            '       and o.uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':goodsid' => $shop_goods['id'],
                ':uniacid' => $_W['uniacid']
            )
        );

        pdo_update(
            'superdesk_shop_goods',
            array(
                'salesreal' => $salesreal
            ),
            array(
                'id' => $shop_goods['id']
            )
        );

        m('member')->upgradeLevel($shop_order['openid'],$shop_order['core_user']);
        m('notice')->sendOrderMessage($shop_order['id']);
        m('order')->setGiveBalance($shop_order['id'], 1);

        if (com('coupon') && !empty($shop_order['couponid'])) {
            com('coupon')->backConsumeCoupon($shop_order['id']);
        }

        if (p('commission')) {
            p('commission')->checkOrderPay($shop_order['id']);
            p('commission')->checkOrderFinish($shop_order['id']);
        }

        return true;
    }
}