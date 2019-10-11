<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');

class Refund_goods_SuperdeskShopV2Page extends MerchWebPage
{
    private $_orderModel;
    private $_order_goodsModel;

    public function __construct()
    {

        global $_W;
        global $_GPC;
        parent::__construct();

        $this->_orderModel       = new orderModel();
        $this->_order_goodsModel = new order_goodsModel();
    }

    protected function opData()
    {
        global $_W;
        global $_GPC;

        $refundid = intval($_GPC['refundid']);

        $refund         = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_order_refund') .
            ' where id=:id ' .
            ' limit 1',
            array(
                ':id' => $refundid
            )
        );
        $refund['imgs'] = iunserializer($refund['imgs']);

        $goods = pdo_fetch(
            'SELECT ' .
            '   g.*, ' .
            '   o.goodssn as option_goodssn, o.productsn as option_productsn,o.total,g.type,o.optionname,o.optionid,o.rstate,' .
            '   o.price as orderprice,o.realprice,o.changeprice,o.oldprice,o.commission1,o.commission2,o.commission3,o.commissions' .
            ' FROM ' . tablename('superdesk_shop_order_goods') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            '   left join ' . tablename('superdesk_shop_goods') . ' g on o.goodsid=g.id ' .
            ' WHERE ' .
            '   o.id = :id ' .
            '   and o.uniacid = :uniacid ' .
            '   and o.merchid = :merchid',
            array(
                ':id'      => $refund['order_goods_id'],
                ':uniacid' => $_W['uniacid'],
                ':merchid' => $_W['merchid']
            )
        );

        if (!empty($goods['option_goodssn'])) {
            $goods['goodssn'] = $goods['option_goodssn'];
        }

        if (!empty($goods['option_productsn'])) {
            $goods['productsn'] = $goods['option_productsn'];
        }

        $order = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE ' .
            '   id = :id ' .
            '   and uniacid=:uniacid ' .
            '   and merchid = :merchid  ' .
            ' Limit 1',
            array(
                ':id'      => $refund['orderid'],
                ':uniacid' => $_W['uniacid'],
                ':merchid' => $_W['merchid']
            )
        );

        if (empty($order)) {
            if ($_W['isajax']) {
                show_json(0, '未找到订单!');
            }
            $this->message('未找到订单!', '', 'error');
        }


        $r_type = array('退货', '换货', '维修', '退货退款');

        return array(
            'order'  => $order,
            'refund' => $refund,
            'r_type' => $r_type,
            'goods'  => $goods
        );
    }

    /**
     * refundstatus
     * -1 驳回申请
     * 1 同意退款
     * 2 手动退款
     * 3 通过申请(需客户寄回商品)
     * 5 确认发货
     * 10 关闭申请(换货完成)
     *
     *
     * $refund['status']
     * -2 客户取消
     * -1 已拒绝
     * 0 等待商家处理申请
     * 1 完成
     * 3 等待客户退回物品
     * 4 客户退回物品，等待商家确认收货
     * 5 等待客户收货
     */
    public function submit()
    {
        global $_W;
        global $_GPC;
        global $_S;

        $opdata = $this->opData();
        extract($opdata);

        $uniacid = $_W['uniacid'];
        $merchid = $_W['merchid'];

        if ($_W['ispost']) {

            $shopset = $_S['shop'];

            if (empty($goods['rstate'])) {
                show_json(0, '订单未申请维权，不需处理！');
            }

            if (($refund['status'] < 0) || ($refund['status'] == 1)) {

                //mark kafka 为了kafka转成了model执行
                $this->_order_goodsModel->updateByColumn(
                    array(
                        'rstate' => 0
                    ),
                    array(
                        'id'      => $refund['order_goods_id'],
                        'uniacid' => $_W['uniacid'],
                        'merchid' => $_W['merchid']
                    )
                );

                show_json(0, '未找需要处理的维权申请，不需处理！');

            }

            if (empty($refund['refundno'])) {

                $refund['refundno'] = m('common')->createNO('order_refund', 'refundno', 'SR');

                pdo_update(
                    'superdesk_shop_order_refund',
                    array(
                        'refundno' => $refund['refundno']
                    ),
                    array(
                        'id' => $refund['id']
                    )
                );

            }

            $refundstatus  = intval($_GPC['refundstatus']);
            $refundcontent = trim($_GPC['refundcontent']);
            $time          = time();
            $change_refund = array();
            $refund_status = 0;

            if ($refundstatus == 0) { // 处理结果

                show_json(1);

            } else if ($refundstatus == 3) { // 处理结果 通过申请(需客户寄回商品)

                $raid    = $_GPC['raid'];
                $message = trim($_GPC['message']);

                if ($raid == 0) {

                    $raddress = pdo_fetch(
                        'select * ' .
                        ' from ' . tablename('superdesk_shop_refund_address') .// TODO 标志 楼宇之窗 openid shop_refund_address 不处理
                        ' where ' .
                        '   isdefault = 1 ' .
                        '   and uniacid = :uniacid ' .
                        '   and merchid = :merchid ' .
                        ' limit 1',
                        array(
                            ':uniacid' => $uniacid,
                            ':merchid' => $merchid
                        )
                    );

                } else {

                    $raddress = pdo_fetch(
                        'select * ' .
                        ' from ' . tablename('superdesk_shop_refund_address') .// TODO 标志 楼宇之窗 openid shop_refund_address 不处理
                        ' where ' .
                        '   id = :id ' .
                        '   and uniacid = :uniacid ' .
                        '   and merchid = :merchid ' .
                        ' limit 1',
                        array(
                            ':id'      => $raid,
                            ':uniacid' => $uniacid,
                            ':merchid' => $merchid
                        )
                    );

                }

                if (empty($raddress)) {

                    $raddress = pdo_fetch(
                        ' select * ' .
                        ' from ' . tablename('superdesk_shop_refund_address') .// TODO 标志 楼宇之窗 openid shop_refund_address 不处理
                        ' where ' .
                        '   uniacid = :uniacid ' .
                        '   and merchid = :merchid ' .
                        ' order by id desc ' .
                        ' limit 1',
                        array(
                            ':uniacid' => $uniacid,
                            ':merchid' => $merchid
                        )
                    );
                }

                unset($raddress['uniacid']);
                unset($raddress['merchid']);
                unset($raddress['openid']);
                unset($raddress['isdefault']);
                unset($raddress['deleted']);

                $raddress                         = iserializer($raddress);
                $change_refund['reply']           = '';
                $change_refund['refundaddress']   = $raddress;
                $change_refund['refundaddressid'] = $raid;
                $change_refund['message']         = $message;

                if (empty($refund['operatetime'])) {
                    $change_refund['operatetime'] = $time;
                }

                if ($refund['status'] != 4) {
                    $refund_status           = 3;
                    $change_refund['status'] = $refund_status;
                }

                pdo_update(
                    'superdesk_shop_order_refund',
                    $change_refund,
                    array(
                        'id' => $refund['id']
                    )
                );

                //m('notice')->sendOrderMessage($order['id'], true);

            } else if ($refundstatus == 5) { // 处理结果

                $change_refund['rexpress']    = $_GPC['rexpress'];
                $change_refund['rexpresscom'] = $_GPC['rexpresscom'];
                $change_refund['rexpresssn']  = trim($_GPC['rexpresssn']);
                $refund_status                = 5;
                $change_refund['status']      = $refund_status;

                if (($refund['status'] != 5) && empty($refund['returntime'])) {

                    $change_refund['returntime'] = $time;

                    if (empty($refund['operatetime'])) {

                        $change_refund['operatetime'] = $time;

                    }
                }

                pdo_update(
                    'superdesk_shop_order_refund',
                    $change_refund,
                    array(
                        'id' => $refund['id']
                    )
                );

                //m('notice')->sendOrderMessage($order['id'], true);

            } else if ($refundstatus == 10) { // 处理结果

                $refund_status             = 1;
                $refund_data['status']     = $refund_status;
                $refund_data['refundtime'] = $time;

                pdo_update(
                    'superdesk_shop_order_refund',
                    $refund_data,
                    array(
                        'id'      => $refund['id'],
                        'uniacid' => $uniacid
                    )
                );

                $order_goods_data                     = array();
                $order_goods_data['rstate']           = 0;
                $order_goods_data['return_goods_nun'] = 0;
                $order_goods_data['refund_status']    = $refund_status;
//                $order_goods_data['status']      = 3;
                $order_goods_data['refundtime'] = $time;

                //mark kafka 为了kafka转成了model执行
                $this->_order_goodsModel->updateByColumn(
                    $order_goods_data,
                    array(
                        'id'      => $refund['order_goods_id'],
                        'uniacid' => $uniacid,
                        'merchid' => $merchid
                    )
                );


                //m('notice')->sendOrderMessage($order['id'], true);

            } else if ($refundstatus == 1) { // 处理结果 同意退款(无需客户发货直接退款)

                if (0 < $order['parentid']) {

                    $parent_item = pdo_fetch(
                        ' SELECT id,ordersn,ordersn2,price ' .
                        ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
                        ' WHERE ' .
                        '   id = :id ' .
                        '   and uniacid=:uniacid ' .
                        ' Limit 1',
                        array(
                            ':id'      => $order['parentid'],
                            ':uniacid' => $_W['uniacid']
                        )
                    );

                    if (empty($parent_item)) {
                        show_json(0, '未找到退款订单!');
                    }

                    $ordersn = $parent_item['ordersn'];

                    if (!empty($parent_item['ordersn2'])) {
                        $var     = sprintf('%02d', $parent_item['ordersn2']);
                        $ordersn .= 'GJ' . $var;
                    }

                } else {

                    $ordersn = $order['ordersn'];

                    if (!empty($order['ordersn2'])) {
                        $var     = sprintf('%02d', $order['ordersn2']);
                        $ordersn .= 'GJ' . $var;
                    }

                }

                $realprice = $refund['applyprice'];

                $refundtype = 0;

                if ($order['paytype'] == 1) {

                    m('member')->setCredit($order['openid'], $order['core_user'],
                        'credit2',
                        $realprice,
                        array(
                            0,
                            $shopset['name'] . '退款: ' . $realprice . '元 订单号: ' . $order['ordersn'] . ' 商品名称:' . $goods['title']
                        ), array(
                            'type'       => 4,
                            'createtime' => time(),
                            'orderid'    => $refund['id']
                        )
                    );

                    $result = true;

                } else if ($order['paytype'] == 21) {

                    $realprice = round($realprice - $order['deductcredit2'], 2);

                    if (0 < $realprice) {

                        if (empty($order['isborrow'])) {

                            $result = m('finance')->refund(
                                $order['openid'],
                                $ordersn,
                                $refund['refundno'],
                                $goods['realprice'] * 100,
                                $realprice * 100,
                                (!empty($order['apppay']) ? true : false)
                            );

                        } else {

                            $result = m('finance')->refundBorrow(
                                $order['borrowopenid'],
                                $ordersn,
                                $refund['refundno'],
                                $goods['realprice'] * 100,
                                $realprice * 100,
                                (!empty($order['ordersn2']) ? 1 : 0)
                            );

                        }
                    }

                    $refundtype = 2;

                } else {

                    if ($realprice < 1) {

                        show_json(0, '退款金额必须大于1元，才能使用微信企业付款退款!');
                    }

                    $realprice = round($realprice - $order['deductcredit2'], 2);

                    $result = m('finance')->pay($order['openid'], $order['core_user'],
                        1,
                        $realprice * 100,
                        $refund['refundno'],
                        $shopset['name'] . '退款: ' . $realprice . '元 订单号: ' . $order['ordersn'] . ' 商品名称:' . $goods['title']
                    );

                    $refundtype = 1;

                }

                if (is_error($result)) {

                    show_json(0, $result['message']);
                }

                $credits = 0;
                $gcredit = trim($goods['credit']);
                if (!empty($gcredit)) {
                    if (strexists($gcredit, '%')) {
                        $credits += intval((floatval(str_replace('%', '', $gcredit)) / 100) * $goods['realprice']);
                    } else {
                        $credits += intval($goods['credit']) * $goods['total'];
                    }

                    if (0 < $credits) {

                        m('member')->setCredit($order['openid'], $order['core_user'],
                            'credit1',
                            -$credits,
                            array(
                                0,
                                $shopset['name'] . '退款扣除购物赠送积分: ' . $credits . ' 订单号: ' . $order['ordersn'] . ' 商品名称:' . $goods['title']
                            )
                        );
                    }
                }

                //暂时屏蔽.不管抵扣
//                if (0 < $order['deductcredit']) {
//
//                    m('member')->setCredit(
//                        $order['openid'],
//                        $order['core_user'],
//                        'credit1',
//                        $order['deductcredit'],
//                        array(
//                            '0',
//                            $shopset['name'] . '购物返还抵扣积分 积分: ' . $order['deductcredit'] . ' 抵扣金额: ' . $order['deductprice'] . ' 订单号: ' . $order['ordersn']
//                        )
//                    );
//                }

                if (!empty($refundtype)) {

                    //暂时屏蔽,不管余额抵扣
                    //m('order')->setDeductCredit2($order);
                }

                $change_refund['reply']      = '';
                $refund_status               = 1;
                $change_refund['status']     = $refund_status;
                $change_refund['refundtype'] = $refundtype;
                $change_refund['price']      = $realprice;
                $change_refund['refundtime'] = $time;

                if (empty($refund['operatetime'])) {
                    $change_refund['operatetime'] = $time;
                }

                pdo_update(
                    'superdesk_shop_order_refund',
                    $change_refund,
                    array(
                        'id' => $refund['id']
                    )
                );

                m('order')->setGiveBalanceByGoods($order['id'], $refund['order_goods_id'], 2);
//                m('order')->setGiveBalance($order['id'], 2);
                //m('notice')->sendOrderMessage($order['id'], true);

                //mark kafka 为了kafka转成了model执行
                $this->_order_goodsModel->updateByColumn(
                    array(
                        'rstate'        => 0,
                        'refundtime'    => $time,
                        'refund_status' => $refund_status
                    ),
                    array(
                        'id'      => $refund['order_goods_id'],
                        'uniacid' => $uniacid,
                        'merchid' => $merchid
                    )
                );


                //查找是否全部商品都已进行退款.并且退款完成.
//                $check_is_all_refund = pdo_fetchcolumn(
//                    'select count(1) ' .
//                    ' from ' . tablename('superdesk_shop_order_goods') . // TODO 标志 楼宇之窗 openid shop_order_goods 不处理
//                    ' where ' .
//                    '       refundtime = 0 and orderid = :orderid',
//                    array(':orderid' => $refund['orderid'])
//                );
//
//                //假如全部已退款完成,那么就把订单关闭掉
//                if ($check_is_all_refund == 0) {
//                    //mark kafka 为了kafka转成了model执行
//                    $this->_orderModel->updateByColumn(
//                        array(
//                            'refundstate' => 0,
//                            'status'      => -1,
//                            'refundtime'  => $time
//                        ),
//                        array(
//                            'id'      => $refund['orderid'],
//                            'uniacid' => $uniacid
//                        )
//                    );
//                }

                $salesreal = pdo_fetchcolumn(
                    'select ifnull(sum(total),0) ' .
                    ' from ' . tablename('superdesk_shop_order_goods') .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                    ' where ' .
                    '   goodsid = :goodsid ' .
                    '   and refund_status = 1 ' .
                    '   and uniacid = :uniacid ' .
                    '   and merchid = :merchid ' .
                    ' limit 1',
                    array(
                        ':goodsid' => $goods['id'],
                        ':uniacid' => $uniacid,
                        ':merchid' => $merchid // MARK ERROR
                    )
                );

                pdo_update(
                    'superdesk_shop_goods',
                    array(
                        'salesreal' => $salesreal
                    ),
                    array(
                        'id' => $goods['id']
                    )
                );

                $log = '订单退款 ID: ' . $order['id'] . ' 订单号: ' . $order['ordersn'];

                if (0 < $order['parentid']) {
                    $log .= ' 父订单号:' . $ordersn;
                }

                $log .= ' 商品名称:' . $goods['title'];

                plog('order.refund', $log);

            } else if ($refundstatus == -1) { // 处理结果 驳回申请

                $refund_status            = -1;
                $change_refund['status']  = $refund_status;
                $change_refund['reply']   = $refundcontent;
                $change_refund['endtime'] = $time;

                pdo_update(
                    'superdesk_shop_order_refund',
                    $change_refund,
                    array(
                        'id' => $refund['id']
                    )
                );

                //m('notice')->sendOrderMessage($order['id'], true);

                plog(
                    'order.refund',
                    '订单退款拒绝 ID: ' . $order['id'] . ' 订单号: ' . $order['ordersn'] . ' 原因: ' . $refundcontent
                );

                //mark kafka 为了kafka转成了model执行
                $this->_order_goodsModel->updateByColumn(
                    array(
                        'rstate' => 0,
                        'return_goods_nun' => 0
                    ),
                    array(
                        'id'      => $refund['order_goods_id'],
                        'uniacid' => $uniacid,
                        'merchid' => $merchid
                    )
                );


                m('notice')->sendOrderMessage($order['id'], true);

            } else if ($refundstatus == 2) { // 手动退款

                $refundtype                  = 2;
                $refund_status               = 1;
                $change_refund['status']     = $refund_status;
                $change_refund['reply']      = '';
                $change_refund['refundtype'] = $refundtype;
                $change_refund['price']      = $refund['applyprice'];
                $change_refund['refundtime'] = $time;

                if (empty($refund['operatetime'])) {
                    $change_refund['operatetime'] = $time;
                }

                pdo_update(
                    'superdesk_shop_order_refund',
                    $change_refund,
                    array(
                        'id' => $refund['id']
                    )
                );

                m('order')->setGiveBalanceByGoods($order['id'], $refund['order_goods_id'], 2);
                //m('notice')->sendOrderMessage($order['id'], true);

                //mark kafka 为了kafka转成了model执行
                $this->_order_goodsModel->updateByColumn(
                    array(
                        'rstate'        => 0,
                        'refund_status' => $refund_status,
                        'refundtime'    => $time
                    ),
                    array(
                        'id'      => $refund['order_goods_id'],
                        'uniacid' => $uniacid,
                        'merchid' => $merchid
                    )
                );

                //查找是否全部商品都已进行退款.并且退款完成.
//                $check_is_all_refund = pdo_fetchcolumn(
//                    'select count(1) ' .
//                    ' from ' . tablename('superdesk_shop_order_goods') . // TODO 标志 楼宇之窗 openid shop_order_goods 不处理
//                    ' where ' .
//                    '       refundtime = 0 and orderid = :orderid',
//                    array(':orderid' => $refund['orderid'])
//                );
//
//                //假如全部已退款完成,那么就把订单关闭掉
//                if ($check_is_all_refund == 0) {
//                    //mark kafka 为了kafka转成了model执行
//                    $this->_orderModel->updateByColumn(
//                        array(
//                            'refundstate' => 0,
//                            'status'      => -1,
//                            'refundtime'  => $time
//                        ),
//                        array(
//                            'id'      => $refund['orderid'],
//                            'uniacid' => $uniacid
//                        )
//                    );
//                }

                $credits = 0;
                $gcredit = trim($goods['credit']);
                if (!empty($gcredit)) {
                    if (strexists($gcredit, '%')) {
                        $credits += intval((floatval(str_replace('%', '', $gcredit)) / 100) * $goods['realprice']);
                    } else {
                        $credits += intval($goods['credit']) * $goods['total'];
                    }

                    if (0 < $credits) {

                        m('member')->setCredit($order['openid'], $order['core_user'],
                            'credit1',
                            -$credits,
                            array(
                                0,
                                $shopset['name'] . '退款扣除购物赠送积分: ' . $credits . ' 订单号: ' . $order['ordersn'] . ' 商品名称:' . $goods['title']
                            )
                        );
                    }
                }

                $salesreal = pdo_fetchcolumn(
                    'select ifnull(sum(total),0) ' .
                    ' from ' . tablename('superdesk_shop_order_goods') .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                    ' where ' .
                    '   goodsid = :goodsid ' .
                    '   and refund_status = 1 ' .
                    '   and uniacid = :uniacid ' .
                    '   and merchid = :merchid ' .
                    ' limit 1',
                    array(
                        ':goodsid' => $goods['id'],
                        ':uniacid' => $uniacid,
                        ':merchid' => $merchid // MARK ERROR
                    )
                );

                pdo_update(
                    'superdesk_shop_goods',
                    array(
                        'salesreal' => $salesreal
                    ),
                    array(
                        'id' => $goods['id']
                    )
                );

                m('notice')->sendOrderMessage($order['id'], true);
            }

            show_json(1);

        } // end $_W['ispost']

        // 商户 退货地址 列表 select
        $refund_address = pdo_fetchall(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_refund_address') .// TODO 标志 楼宇之窗 openid shop_refund_address 不处理
            ' where ' .
            '   uniacid=:uniacid ' .
            '   and merchid = :merchid',
            array(
                ':uniacid' => $_W['uniacid'],
                ':merchid' => $merchid
            )
        );

        $express_list = m('express')->getExpressList();

        $r_type = array(
            0 => '退货',
            1 => '换货',
            2 => '维修',
            3 => '退货退款'
        );

        $rtype_value = $r_type[$refund['rtype']];

        include $this->template();
    }

    public function detail()
    {
        global $_W;
        global $_GPC;
        global $_S;

        $opdata = $this->opData();
        extract($opdata);

        $step_array             = array();
        $step_array[1]['step']  = 1;
        $step_array[1]['title'] = '客户申请维权';
        $step_array[1]['time']  = $refund['createtime'];
        $step_array[1]['done']  = 1;
        $step_array[2]['step']  = 2;
        $step_array[2]['title'] = '商家处理维权申请';
        $step_array[2]['done']  = 1;
        $step_array[3]['step']  = 3;
        $step_array[3]['done']  = 0;

        if (0 <= $refund['status']) {

            if ($refund['rtype'] == 0) {

                $step_array[3]['title'] = '客户退回物品';
                $step_array[4]['step']  = 4;
                $step_array[4]['title'] = '退货完成';

            } else if ($refund['rtype'] == 1) {

                $step_array[3]['title'] = '客户退回物品';
                $step_array[4]['step']  = 4;
                $step_array[4]['title'] = '商家重新发货';
                $step_array[5]['step']  = 5;
                $step_array[5]['title'] = '换货完成';

            } else if ($refund['rtype'] == 2) {

                $step_array[3]['title'] = '客户退回物品';
                $step_array[4]['step']  = 4;
                $step_array[4]['title'] = '商家重新发货';
                $step_array[5]['step']  = 5;
                $step_array[5]['title'] = '维修完成';

            } else if ($refund['rtype'] == 3) {

                $step_array[3]['title'] = '客户退回物品';
                $step_array[4]['step']  = 4;
                $step_array[4]['title'] = '退货退款完成';

            }

            if ($refund['status'] == 0) {

                $step_array[2]['done'] = 0;
                $step_array[3]['done'] = 0;
            }

            $step_array[2]['time'] = $refund['operatetime'];

            if (($refund['status'] == 1) || (4 <= $refund['status'])) {

                $step_array[3]['done'] = 1;
                $step_array[3]['time'] = $refund['sendtime'];
            }

            if (($refund['status'] == 1) || ($refund['status'] == 5)) {

                $step_array[4]['done'] = 1;

                if ($refund['rtype'] == 1) {

                    $step_array[4]['time'] = $refund['refundtime'];

                } else if ($refund['rtype'] == 2) {

                    $step_array[4]['time'] = $refund['returntime'];

                    if ($refund['status'] == 1) {
                        $step_array[5]['done'] = 1;
                        $step_array[5]['time'] = $refund['refundtime'];
                    }

                }

            }

        } else if ($refund['status'] == -1) {

            $step_array[2]['done']  = 1;
            $step_array[2]['time']  = $refund['endtime'];
            $step_array[3]['done']  = 1;
            $step_array[3]['title'] = '拒绝' . $r_type[$refund['rtype']];
            $step_array[3]['time']  = $refund['endtime'];

        } else if ($refund['status'] == -2) {

            if (!empty($refund['operatetime'])) {
                $step_array[2]['done'] = 1;
                $step_array[2]['time'] = $refund['operatetime'];
            }

            $step_array[3]['done']  = 1;
            $step_array[3]['title'] = '客户取消' . $r_type[$refund['rtype']];
            $step_array[3]['time']  = $refund['refundtime'];
        }

        $member = m('member')->getMember($order['openid'], $order['core_user']);

        $express_list = m('express')->getExpressList();

        include $this->template();
    }

    public function main()
    {
        global $_W;
        global $_GPC;
        $orderData = $this->orderData('', 'main');
    }

    public function status1()
    {
        global $_W;
        global $_GPC;
        $orderData = $this->orderData(1, 'status1');
    }

    //-1, 'status_1'
    public function orderData($status, $st)
    {
        global $_W;
        global $_GPC;


        $merch_user = $_W['merch_user'];
        $pindex     = max(1, intval($_GPC['page']));
        $psize      = 20;

        if ($st == 'main') {
            $st = '';
        } else {
            $st = '.' . $st;
        }

        $condition = ' og.uniacid = :uniacid and og.merchid = :merchid and o.deleted=0 and o.isparent=0 ';

        $uniacid = $_W['uniacid'];
        $merchid = $_W['merchid'];
        $params  = $params1 = array(':uniacid' => $uniacid, ':merchid' => $merchid);
        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime   = time();
        }


        $searchtime = trim($_GPC['searchtime']);

        if (!empty($searchtime) && is_array($_GPC['time']) && in_array($searchtime, array('create', 'pay', 'send', 'finish'))) {
            $starttime            = strtotime($_GPC['time']['start']);
            $endtime              = strtotime($_GPC['time']['end']);
            $condition            .= ' AND o.' . $searchtime . 'time >= :starttime AND o.' . $searchtime . 'time <= :endtime ';
            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }


        if ($_GPC['paytype'] != '') {
            if ($_GPC['paytype'] == '2') {
                $condition .= ' AND ( o.paytype =21 or o.paytype=22 or o.paytype=23 )';
            } else {
                $condition .= ' AND o.paytype =' . intval($_GPC['paytype']);
            }
        }

        if (!empty($_GPC['searchfield']) && !empty($_GPC['keyword'])) {

            $searchfield        = trim(strtolower($_GPC['searchfield']));
            $_GPC['keyword']    = trim($_GPC['keyword']);
            $params[':keyword'] = $_GPC['keyword'];
            $sqlcondition       = '';

            if ($searchfield == 'ordersn') {
                $condition .= ' AND locate(:keyword,o.ordersn)>0';
            } else if ($searchfield == 'member') {
                $condition .= ' AND (locate(:keyword,m.realname)>0 or locate(:keyword,m.mobile)>0 or locate(:keyword,m.nickname)>0)';
            } else if ($searchfield == 'address') {
                $condition .= ' AND ( locate(:keyword,a.realname)>0 or locate(:keyword,a.mobile)>0 or locate(:keyword,o.carrier)>0)';
            } else if ($searchfield == 'location') {
                $condition .= ' AND ( locate(:keyword,a.province)>0 or locate(:keyword,a.city)>0 or locate(:keyword,a.area)>0 or locate(:keyword,a.address)>0)';
            } else if ($searchfield == 'expresssn') {
                $condition .= ' AND locate(:keyword,o.expresssn)>0';
            } else if ($searchfield == 'saler') {
                $condition .= ' AND (locate(:keyword,sm.realname)>0 or locate(:keyword,sm.mobile)>0 or locate(:keyword,sm.nickname)>0 or locate(:keyword,s.salername)>0 )';
            } else if ($searchfield == 'store') {
                $condition    .= ' AND (locate(:keyword,store.storename)>0)';
                $sqlcondition = ' left join ' . tablename('superdesk_shop_merch_store') . ' store on store.id = o.verifystoreid and store.uniacid=o.uniacid';
            } else if ($searchfield == 'goodstitle') {
                $sqlcondition =
                    ' inner join ( select og.orderid from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                    '              left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
                    '              where og.uniacid = \'' . $uniacid . '\' and (locate(:keyword,g.title)>0)) gs on gs.orderid=o.id';
            } else if ($searchfield == 'goodssn') {
                $sqlcondition =
                    ' inner join ( select og.orderid from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                    '              left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
                    '              where og.uniacid = \'' . $uniacid . '\' and (((locate(:keyword,g.goodssn)>0)) or (locate(:keyword,og.goodssn)>0))) gs on gs.orderid=o.id';
            } else if ($searchfield == 'jdorderid') {

                if (true || $jd_vop_plugin) {
                    $condition    .= ' AND (locate(:keyword,jd_vop_o.jd_vop_result_jdOrderId)>0)';
                    $sqlcondition = ' left join ' . tablename('superdesk_jd_vop_order_submit_order') . ' jd_vop_o on jd_vop_o.order_id = o.id '; //and jd_vop_o.uniacid=o.uniacid
                }

//                echo '<br/>';
//                echo $condition;
//                echo '<br/>';
//                echo $sqlcondition;
//                echo '<br/>';


            } else if ($searchfield == 'parent_order_sn') {
                $parent_id = pdo_fetchcolumn(
                    'select id from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
                    ' where locate(:keyword,ordersn)>0',
                    array(':keyword' => $params[':keyword'])
                );
                unset($params[':keyword']);
                $condition           .= ' AND o.parentid=:parentid ';
                $params[':parentid'] = $parent_id;
            }

        }

        $statuscondition = '';

        if ($status !== '') {
            $statuscondition = ' AND og.refundtime<>0';
        } else {
            $statuscondition = ' AND og.rstate>0 and og.refundid<>0 ';
        }

        $sql =
            ' select g.id,g.title,g.thumb,g.goodssn, g.productsn,og.goodssn as option_goodssn,og.productsn as option_productsn, ' .
            '       og.total,og.price,og.optionname as optiontitle, og.realprice,og.changeprice,og.oldprice,og.orderid,og.createtime,og.refundid, ' .
            '       op.specs, ' .
            '       o.ordersn,o.status,o.paytype,o.isverify,o.isvirtual,o.virtual,o.addressid,o.refundtime,o.dispatchtype,o.carrier,o.address,o.openid,o.core_user,o.expresssn,o.express, ' .
            '       r.rtype,r.status as rstatus,r.createtime as rcreatetime,r.applyprice,r.refundno,r.status as refundstatus,r.refund_total, ' .
            '       a.realname as arealname,a.mobile as amobile,a.province as aprovince ,a.city as acity , a.area as aarea, a.town as atown, a.address as aaddress, ' .
            '       d.dispatchname,m.nickname,m.id as mid,m.realname as mrealname,m.mobile as mmobile ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' left join ' . tablename('superdesk_shop_goods_option') . ' op on og.optionid = op.id ' .
            ' left join ' . tablename('superdesk_shop_order') . ' o on o.id=og.orderid ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' left join ' . tablename('superdesk_shop_order_refund') . ' r on r.id =og.refundid ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on m.openid=o.openid and m.core_user=o.core_user and m.uniacid =  o.uniacid ' .// TODO 标志 楼宇之窗 openid superdesk_shop_member 已处理
            ' left join ' . tablename('superdesk_shop_member_address') . ' a on a.id=o.addressid ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
            ' left join ' . tablename('superdesk_shop_dispatch') . ' d on d.id = o.dispatchid ' .
            ' left join ' . tablename('superdesk_shop_merch_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' .// TODO 标志 楼宇之窗 openid shop_order 待处理
            ' left join ' . tablename('superdesk_shop_member') . ' sm on sm.openid = s.openid and sm.core_user = s.core_user and sm.uniacid=s.uniacid' .// TODO 标志 楼宇之窗 openid superdesk_shop_member 已处理
            ' ' .
            $sqlcondition .
            ' where ' .
            $condition .
            ' ' .
            $statuscondition;

        $sql .= ' GROUP BY r.orderid,r.order_goods_id ';

        if (empty($_GPC['orderBy']) || $_GPC['orderBy'] == 1) {
            $sql .= ' ORDER BY o.createtime DESC ';
        } else if ($_GPC['orderBy'] == 2) {
            $sql .= ' ORDER BY r.createtime DESC ';
        }

        if (empty($_GPC['export'])) {
            $sql .= 'LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
        }

        $list = pdo_fetchall($sql, $params);

        $paytype     = array(
            0  => array('css' => 'default', 'name' => '未支付'),
            1  => array('css' => 'danger', 'name' => '余额支付'),
            11 => array('css' => 'default', 'name' => '后台付款'),
            2  => array('css' => 'danger', 'name' => '在线支付'),
            21 => array('css' => 'success', 'name' => '微信支付'),
            22 => array('css' => 'warning', 'name' => '支付宝支付'),
            23 => array('css' => 'warning', 'name' => '银联支付'),
            3  => array('css' => 'primary', 'name' => '企业月结')
        );
        $orderstatus = array(
            -1 => array('css' => 'default', 'name' => '已关闭'),
            0  => array('css' => 'danger', 'name' => '待付款'),
            1  => array('css' => 'info', 'name' => '待发货'),
            2  => array('css' => 'warning', 'name' => '待收货'),
            3  => array('css' => 'success', 'name' => '已完成')
        );
//        -2 客户取消
//    * -1 已拒绝
//    * 0 等待商家处理申请
//    * 1 完成
//    * 3 等待客户退回物品
//    * 4 客户退回物品，等待商家确认收货
//    * 5 等待客户收货
        $refundstatusArray = array(
            -2 => array('css' => 'default', 'name' => '客户取消'),
            -1 => array('css' => 'default', 'name' => '已拒绝'),
            0  => array('css' => 'danger', 'name' => '等待商家处理申请'),
            1  => array('css' => 'success', 'name' => '完成'),
            3  => array('css' => 'danger', 'name' => '等待客户退回物品'),
            4  => array('css' => 'danger', 'name' => '等待商家处理申请'),
            5  => array('css' => 'danger', 'name' => '等待客户收货'),
        );

        foreach ($list as &$value) {

            if (!empty($value['specs'])) {
                $thumb = m('goods')->getSpecThumb($value['specs']);

                if (!empty($thumb)) {
                    $og['thumb'] = $thumb;
                }

            }

            $s  = $value['status'];
            $pt = $value['paytype'];

            $value['statusvalue'] = $s;
            $value['statuscss']   = $orderstatus[$value['status']]['css'];
            $value['status']      = $orderstatus[$value['status']]['name'];

            if (($pt == 3) && empty($value['statusvalue'])) {
                $value['statuscss'] = $orderstatus[1]['css'];
                $value['status']    = $orderstatus[1]['name'];
            }

            if ($s == 1) {
                if ($value['isverify'] == 1) {
                    $value['status'] = '待使用';
                } else if (empty($value['addressid'])) {
                    $value['status'] = '待取货';
                }

            }

            if ($s == -1) {
                if (!empty($value['refundtime'])) {
                    $value['status'] = '已退款';
                }

            }

            $value['refundstatusvalue'] = $value['refundstatus'];
            $value['refundstatuscss']   = $refundstatusArray[$value['refundstatus']]['css'];
            $value['refundstatus']      = $refundstatusArray[$value['refundstatus']]['name'];

            $value['paytypevalue'] = $pt;
            $value['css']          = $paytype[$pt]['css'];
            $value['paytype']      = $paytype[$pt]['name'];
            $value['dispatchname'] = ((empty($value['addressid']) ? '自提' : $value['dispatchname']));

            if (empty($value['dispatchname'])) {
                $value['dispatchname'] = '快递';
            }

            if ($pt == 3) {
                $value['dispatchname'] = '企业月结';
            } else if ($value['isverify'] == 1) {
                $value['dispatchname'] = '线下核销';
            } else if ($value['isvirtual'] == 1) {
                $value['dispatchname'] = '虚拟物品';
            } else if (!empty($value['virtual'])) {
                $value['dispatchname'] = '虚拟物品(卡密)<br/>自动发货';
            }

            if (($value['dispatchtype'] == 1) || !empty($value['isverify']) || !empty($value['virtual']) || !empty($value['isvirtual'])) {

                $value['address'] = '';
                $carrier          = iunserializer($value['carrier']);

                if (is_array($carrier)) {
                    $value['addressdata']['realname'] = $value['realname'] = $carrier['carrier_realname'];
                    $value['addressdata']['mobile']   = $value['mobile'] = $carrier['carrier_mobile'];
                }

            } else {

                $address           = iunserializer($value['address']);
                $isarray           = is_array($address);
                $value['realname'] = (($isarray ? $address['realname'] : $value['arealname']));
                $value['mobile']   = (($isarray ? $address['mobile'] : $value['amobile']));

                $value['province'] = (($isarray ? $address['province'] : $value['aprovince']));
                $value['city']     = (($isarray ? $address['city'] : $value['acity']));
                $value['area']     = (($isarray ? $address['area'] : $value['aarea']));
                $value['town']     = (($isarray ? $address['town'] : $value['atown']));

                $value['address'] = (($isarray ? $address['address'] : $value['aaddress']));

                $value['address_province'] = $value['province'];
                $value['address_city']     = $value['city'];
                $value['address_area']     = $value['area'];
                $value['address_town']     = $value['town'];

                $value['address_address'] = $value['address'];

                $value['address']     = $value['province'] . ' ' . $value['city'] . ' ' . $value['area'] . ' ' . $value['town'] . ' ' . $value['address'];
                $value['addressdata'] = array('realname' => $value['realname'], 'mobile' => $value['mobile'], 'address' => $value['address']);


                $value['price_one']     = $value['price'] / $value['total'];
                $value['realprice_one'] = $value['realprice'] / $value['total'];
            }
        }

        unset($value);

        $list = set_medias($list, 'thumb');

        if ($_GPC['export'] == 1) {
            plog('order.export', '导出订单');
            $columns = array(
                array('title' => '订单编号', 'field' => 'ordersn', 'width' => 24),
                array('title' => '粉丝昵称', 'field' => 'nickname', 'width' => 12),
                array('title' => '会员姓名', 'field' => 'mrealname', 'width' => 12),
                array('title' => 'openid', 'field' => 'openid', 'width' => 24),
                array('title' => '会员手机手机号', 'field' => 'mmobile', 'width' => 12),
                array('title' => '收货姓名(或自提人)', 'field' => 'realname', 'width' => 12),
                array('title' => '联系电话', 'field' => 'mobile', 'width' => 12),
                array('title' => '收货地址', 'field' => 'address_province', 'width' => 12),
                array('title' => '', 'field' => 'address_city', 'width' => 12),
                array('title' => '', 'field' => 'address_area', 'width' => 12),
                array('title' => '', 'field' => 'address_address', 'width' => 12),
                array('title' => '商品名称', 'field' => 'title', 'width' => 24),
                array('title' => '商品编码', 'field' => 'goodssn', 'width' => 12),
                array('title' => '商品规格', 'field' => 'optiontitle', 'width' => 12),
                array('title' => '商品数量', 'field' => 'total', 'width' => 12),
                array('title' => '商品单价(折扣前)', 'field' => 'price_one', 'width' => 12),
                array('title' => '商品单价(折扣后)', 'field' => 'realprice_one', 'width' => 12),
                array('title' => '商品价格(折扣后)', 'field' => 'price', 'width' => 12),
                array('title' => '商品价格(折扣后)', 'field' => 'realprice', 'width' => 12),
                array('title' => '支付方式', 'field' => 'paytype', 'width' => 12),
                array('title' => '配送方式', 'field' => 'dispatchname', 'width' => 12),
                array('title' => '状态', 'field' => 'status', 'width' => 12),
                array('title' => '下单时间', 'field' => 'createtime', 'width' => 24),
            );

            foreach ($list as &$row) {
                $row['ordersn']    = $row['ordersn'] . ' ';
                $row['expresssn']  = $row['expresssn'] . ' ';
                $row['createtime'] = date('Y-m-d H:i:s', $row['createtime']);
            }

            unset($row);

            m('excel')->export(
                $list,
                array(
                    'title'   => '订单数据-' . date('Y-m-d-H-i', time()),
                    'columns' => $columns
                )
            );
        }


        $t = pdo_fetch(
            ' SELECT COUNT(*) as count, ifnull(sum(og.realprice),0) as sumprice  ' .
            ' FROM ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' left join ' . tablename('superdesk_shop_goods_option') . ' op on og.optionid = op.id ' .
            ' left join ' . tablename('superdesk_shop_order') . ' o on o.id=og.orderid ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' left join ' . tablename('superdesk_shop_order_refund') . ' r on r.id =o.refundid ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on m.openid=o.openid and m.core_user=o.core_user and m.uniacid =  o.uniacid ' .// TODO 标志 楼宇之窗 openid superdesk_shop_member 已处理
            ' left join ' . tablename('superdesk_shop_member_address') . ' a on a.id=o.addressid ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
            ' left join ' . tablename('superdesk_shop_dispatch') . ' d on d.id = o.dispatchid ' .
            ' left join ' . tablename('superdesk_shop_merch_saler') . ' s on s.openid = o.verifyopenid and s.uniacid=o.uniacid' .// TODO 标志 楼宇之窗 openid shop_order 待处理
            ' left join ' . tablename('superdesk_shop_member') . ' sm on sm.openid = s.openid and sm.core_user = s.core_user and sm.uniacid=s.uniacid' .// TODO 标志 楼宇之窗 openid superdesk_shop_member 已处理
            ' ' .
            $sqlcondition .
            ' WHERE ' .
            $condition .
            ' ' .
            $statuscondition,
            $params
        );

        $total      = $t['count'];
        $totalmoney = $t['sumprice'];

        $pager = pagination($total, $pindex, $psize);

        $r_type = array('退货', '换货', '维修', '退货退款');

        load()->func('tpl');

        include $this->template('order/refund_goods');
    }
}