<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/area.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_address.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/member/member_invoice.class.php');

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_finance.class.php');

class Op_SuperdeskShopV2Page extends MerchWebPage
{
    private $_areaModel;
    private $_member_addressModel;
    private $_member_invoiceModel;

    private $_order_financeModel;
    private $_orderModel;
    private $_order_goodsModel;

    public function __construct()
    {
        parent::__construct();

        $this->_areaModel           = new areaModel();
        $this->_member_addressModel = new member_addressModel();
        $this->_member_invoiceModel = new member_invoiceModel();

        $this->_order_financeModel = new order_financeModel();

        $this->_orderModel           = new orderModel();
        $this->_order_goodsModel    = new order_goodsModel();
    }

    public function delete()
    {
        global $_W;
        global $_GPC;

        $status  = intval($_GPC['status']);
        $orderid = intval($_GPC['id']);

        //mark kafka 为了kafka转成了model执行
        $this->_orderModel->updateByColumn(
            array(
                'deleted' => 1
            ),
            array(
                'id'      => $orderid,
                'uniacid' => $_W['uniacid'],
                'merchid' => $_W['merchid']
            )
        );

        plog('order.op.delete', '订单删除 ID: ' . $orderid);

        show_json(1, webUrl('order', array('status' => $status)));
    }

    protected function opData()
    {
        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $item = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE id = :id ' .
            '       and uniacid=:uniacid ' .
            '       and merchid = :merchid',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid'],
                ':merchid' => $_W['merchid']
            )
        );

        if (empty($item)) {
            if ($_W['isajax']) {
                show_json(0, '未找到订单!');
            }
            $this->message('未找到订单!', '', 'error');
        }

        return array('id' => $id, 'item' => $item);
    }

    public function changeprice()
    {
        global $_W;
        global $_GPC;

        $opdata = $this->opData();
        extract($opdata);

        $merch_user = $_W['merch_user'];

        if ($_W['ispost']) {

            if (empty($merch_user['changepricechecked'])) {
                show_json(0, '您没有改价的权限!');
            }

            if (0 < $item['parentid']) {
                $parent_order       = array();
                $parent_order['id'] = $item['parentid'];
            }

            $changegoodsprice = $_GPC['changegoodsprice'];
            if (!is_array($changegoodsprice)) {
                show_json(0, '未找到改价内容!');
            }

            $changeprice = 0;

            foreach ($changegoodsprice as $ogid => $change) {
                $changeprice += floatval($change);
            }

            $dispatchprice = floatval($_GPC['changedispatchprice']);
            if ($dispatchprice < 0) {
                $dispatchprice = 0;
            }

            $orderprice          = $item['price'] + $changeprice;
            $changedispatchprice = 0;

            if ($dispatchprice != $item['dispatchprice']) {
                $changedispatchprice = $dispatchprice - $item['dispatchprice'];
                $orderprice          += $changedispatchprice;
            }

            if ($orderprice < 0) {
                show_json(0, '订单实际支付价格不能小于0元!');
            }

            foreach ($changegoodsprice as $ogid => $change) {

                $og = pdo_fetch(
                    ' select price,realprice ' .
                    ' from ' . tablename('superdesk_shop_order_goods') .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                    ' where id=:ogid and uniacid=:uniacid and merchid = :merchid limit 1',
                    array(
                        ':ogid'    => $ogid,
                        ':uniacid' => $_W['uniacid'],
                        ':merchid' => $_W['merchid']
                    )
                );

                if (!empty($og)) {
                    $realprice = $og['realprice'] + $change;
                    if ($realprice < 0) {
                        show_json(0, '单个商品不能优惠到负数');
                    }
                }
            }

            $ordersn2 = $item['ordersn2'] + 1;
            if (99 < $ordersn2) {
                show_json(0, '超过改价次数限额');
            }

            $orderupdate = array();

            if ($orderprice != $item['price']) {
                $orderupdate['price']    = $orderprice;
                $orderupdate['ordersn2'] = $item['ordersn2'] + 1;
                if (0 < $item['parentid']) {
                    $parent_order['price_change'] = $orderprice - $item['price'];
                }
            }

            $orderupdate['changeprice'] = $item['changeprice'] + $changeprice;

            if ($dispatchprice != $item['dispatchprice']) {
                $orderupdate['dispatchprice']       = $dispatchprice;
                $orderupdate['changedispatchprice'] = $item['changedispatchprice'] + $changedispatchprice;
                if (0 < $item['parentid']) {
                    $parent_order['dispatch_change'] = $changedispatchprice;
                }
            }

            if (!empty($orderupdate)) {

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    $orderupdate,
                    array(
                        'id'      => $item['id'],
                        'uniacid' => $_W['uniacid'],
                        'merchid' => $_W['merchid']
                    )
                );
            }

            if (0 < $item['parentid']) {
                if (!empty($parent_order)) {
                    m('order')->changeParentOrderPrice($parent_order);
                }
            }

            foreach ($changegoodsprice as $ogid => $change) {

                $og = pdo_fetch(
                    ' select price,realprice,changeprice ' .
                    ' from ' . tablename('superdesk_shop_order_goods') .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                    ' where id=:ogid ' .
                    '       and uniacid=:uniacid ' .
                    '       and merchid = :merchid ' .
                    ' limit 1',
                    array(
                        ':ogid'    => $ogid,
                        ':uniacid' => $_W['uniacid'],
                        ':merchid' => $_W['merchid']
                    )
                );
                if (!empty($og)) {

                    $realprice   = $og['realprice'] + $change;
                    $changeprice = $og['changeprice'] + $change;

                    //mark kafka 为了kafka转成了model执行
                    $this->_order_goodsModel->updateByColumn(
                        array(
                            'realprice'   => $realprice,
                            'changeprice' => $changeprice
                        ),
                        array(
                            'id' => $ogid
                        )
                    );

                }
            }

            if (0 < abs($changeprice)) {
                $pluginc = p('commission');
                if ($pluginc) {
                    $pluginc->calculate($item['id'], true);
                }
            }

            plog(
                'order.op.changeprice',
                '订单号： ' . $item['ordersn'] .
                ' <br/> 价格： ' . $item['price'] .
                ' -> ' . $orderprice
            );
            show_json(1);
        }
        $order_goods = pdo_fetchall(
            ' select og.id,g.title,g.thumb,g.goodssn,og.goodssn as option_goodssn, g.productsn,og.productsn as option_productsn, og.total,og.price,og.optionname as optiontitle, og.realprice,og.oldprice ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            '       left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where '.
            '       og.uniacid=:uniacid '.
            '       and og.merchid = :merchid '.
            '       and og.orderid=:orderid ',
            array(
                ':uniacid' => $_W['uniacid'],
                ':merchid' => $_W['merchid'],
                ':orderid' => $item['id']
            )
        );
        if (empty($item['addressid'])) {

            $user = unserialize($item['carrier']);

            $item['addressdata'] = array(
                'realname' => $user['carrier_realname'],
                'mobile'   => $user['carrier_mobile']
            );

        } else {

            $user = iunserializer($item['address']);

            if (!is_array($user)) {
                $user = pdo_fetch(
                    ' SELECT * ' .
                    ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
                    ' WHERE id = :id ' .
                    '       and uniacid=:uniacid',
                    array(
                        ':id'      => $item['addressid'],
                        ':uniacid' => $_W['uniacid']
                    )
                );
            }

            $user['address'] = $user['province'] . ' ' . $user['city'] . ' ' . $user['area'] . ' ' . $user['address'];

            $item['addressdata'] = array(
                'realname' => $user['realname'],
                'mobile'   => $user['mobile'],
                'address'  => $user['address']
            );
        }
        include $this->template();
    }

    public function pay($a = array(), $b = array())
    {
        global $_W;
        global $_GPC;

        $opdata = $this->opData();
        extract($opdata);

//        show_json(1);

        if (1 < $item['status']) {
            show_json(0, '订单已付款，不需重复付款！');
        }

        if (!empty($item['virtual']) && com('virtual')) {

            com('virtual')->pay($item);

        } else {

            //mark kafka 为了kafka转成了model执行
            $this->_orderModel->updateByColumn(
                array(
                    'status'  => 1,
                    'paytype' => 11, // 后台付款
                    'paytime' => time()
                ), array(
                    'id'      => $item['id'],
                    'uniacid' => $_W['uniacid'],
                    'merchid' => $_W['merchid']
                )
            );

            //2018年12月28日 17:00:29 zjh
            //后台确认付款,因为没有走真正的确认付款所以会出现遗漏.如merchshow的问题.会有前端显示主单.后台显示拆分单的问题
            //判断是否子单.并且没有修改merchshow
            //修改主单merchshow.并修改同样主单的所有子单(即包括了除了该单以外的子单)的merchshow
            if($item['merchshow'] == 1 && $item['parentid'] > 0){
                //mark kafka 为了kafka转成了model执行

                //更新父单
                $this->_orderModel->updateByColumn(
                    array(
                        'merchshow'  => 1,
                    ), array(
                        'id'      => $item['parentid'],
                        'uniacid' => $_W['uniacid']
                    )
                );

                //更新所有子单
                $this->_orderModel->updateByColumn(
                    array(
                        'merchshow'  => 0,
                    ), array(
                        'parentid' => $item['parentid'],
                        'uniacid'  => $_W['uniacid']
                    )
                );
            }

            m('order')->setStocksAndCredits($item['id'], 1);
            m('notice')->sendOrderMessage($item['id']);

            if (com('coupon') && !empty($item['couponid'])) {
                com('coupon')->backConsumeCoupon($item['id']);
            }

            if (p('commission')) {
                p('commission')->checkOrderPay($item['id']);
            }

        }

        plog('order.op.pay', '订单确认付款 ID: ' . $item['id'] . ' 订单号: ' . $item['ordersn']);

        show_json(1);
    }

    public function close()
    {
        global $_W;
        global $_GPC;

        $opdata = $this->opData();
        extract($opdata);

        if ($item['status'] == -1) {

            show_json(0, '订单已关闭，无需重复关闭！');

        }
        // TODO 如果是经理reject 已付款 未发货 这边是可以走下一步的
//        else if (){
//          不改 只要总端改
//        }
        else if (1 <= $item['status']) {

            show_json(0, '订单已付款，不能关闭！');

        }

        if ($_W['ispost']) {

            if (0 < $item['parentid']) {
                show_json(1);
            }

            if (!empty($item['transid'])) {
            }

            $time = time();
            if ((0 < $item['refundstate']) && !empty($item['refundid'])) {

                $change_refund               = array();
                $change_refund['status']     = -1;
                $change_refund['refundtime'] = $time;

                pdo_update(
                    'superdesk_shop_order_refund',
                    $change_refund,
                    array(
                        'id'      => $item['refundid'],
                        'uniacid' => $_W['uniacid'],
                        'merchid' => $_W['merchid']
                    )
                );
            }
            if (0 < $item['deductcredit']) {

                m('member')->setCredit($item['openid'],$item['core_user'],
                    'credit1',
                    $item['deductcredit'],

                    array(
                        '0',
                        $_W['shopset']['shop']['name'] .
                        '购物返还抵扣积分 积分: ' . $item['deductcredit'] .
                        ' 抵扣金额: ' . $item['deductprice'] .
                        ' 订单号: ' . $item['ordersn']
                    )
                );
            }

            m('order')->setDeductCredit2($item);

            if (com('coupon') && !empty($item['couponid'])) {
                com('coupon')->returnConsumeCoupon($item['id']);
            }

            m('order')->setStocksAndCredits($item['id'], 2);

            //mark kafka 为了kafka转成了model执行
            $this->_orderModel->updateByColumn(
                array(
                    'status'      => -1,
                    'refundstate' => 0,
                    'canceltime'  => $time,
                    'remarkclose' => $_GPC['remark']
                ),
                array(
                    'id'      => $item['id'],
                    'uniacid' => $_W['uniacid']
                )
            );

            plog(
                'order.op.close',
                '订单关闭 ID: ' . $item['id'] .
                ' 订单号: ' . $item['ordersn']
            );

            show_json(1);
        }
        include $this->template();
    }

    public function paycancel()
    {
        global $_W;
        global $_GPC;

        $opdata = $this->opData();
        extract($opdata);

        if ($item['status'] != 1) {
            show_json(0, '订单未付款，不需取消！');
        }

        if ($_W['ispost']) {

            m('order')->setStocksAndCredits($item['id'], 2);

            //mark kafka 为了kafka转成了model执行
            $this->_orderModel->updateByColumn(
                array(
                    'status'        => 0,
                    'cancelpaytime' => time()
                ),
                array(
                    'id'      => $item['id'],
                    'uniacid' => $_W['uniacid'],
                    'merchid' => $_W['merchid']
                )
            );

            plog(
                'order.op.paycancel',
                '订单取消付款 ID: ' . $item['id'] .
                ' 订单号: ' . $item['ordersn']
            );

            show_json(1);
        }
    }

    public function finish()
    {
        global $_W;
        global $_GPC;

        $opdata = $this->opData();

        extract($opdata);

        $merch_user = $_W['merch_user'];

        if (empty($merch_user['finishchecked'])) {
            show_json(0, '您没有确认收货的权限!');
        }

        //mark kafka 为了kafka转成了model执行
        $this->_orderModel->updateByColumn(
            array(
                'status'     => 3,
                'finishtime' => time()
            ),
            array(
                'id'      => $item['id'],
                'uniacid' => $_W['uniacid'],
                'merchid' => $_W['merchid']
            )
        );


        m('member')->upgradeLevel($item['openid']);
        m('order')->setGiveBalance($item['id'], 1);
        m('notice')->sendOrderMessage($item['id']);

        if (!empty($item['couponid'])) {
            com('coupon')->backConsumeCoupon($item['id']);
        }

        if (p('commission')) {
            p('commission')->checkOrderFinish($item['id']);
        }

        plog(
            'order.op.finish',
            '订单完成 ID: ' . $item['id'] .
            ' 订单号: ' . $item['ordersn']
        );

        show_json(1);
    }

    public function fetchcancel()
    {
        global $_W;
        global $_GPC;

        $opdata = $this->opData();

        extract($opdata);

        if ($item['status'] != 3) {
            show_json(0, '订单未取货，不需取消！');
        }

        //mark kafka 为了kafka转成了model执行
        $this->_orderModel->updateByColumn(
            array(
                'status'     => 1,
                'finishtime' => 0
            ),
            array(
                'id'      => $item['id'],
                'uniacid' => $_W['uniacid'],
                'merchid' => $_W['merchid']
            )
        );

        plog(
            'order.op.fetchcancel',
            '订单取消取货 ID: ' . $item['id'] .
            ' 订单号: ' . $item['ordersn']
        );

        show_json(1);
    }

    public function sendcancel()
    {
        global $_W;
        global $_GPC;

        $opdata = $this->opData();
        extract($opdata);

        if ($item['status'] != 2) {
            show_json(0, '订单未发货，不需取消发货！');
        }

        if ($_W['ispost']) {

            if (!empty($item['transid'])) {
            }

            $remark = trim($_GPC['remark']);

            if (!empty($item['remarksend'])) {
                $remark = $item['remarksend'] . "\r\n" . $remark;
            }

            $this->_orderModel->updateByColumn(
                array(
                    'status'     => 1,
                    'sendtime'   => 0,
                    'remarksend' => $remark
                ),
                array(
                    'id'      => $item['id'],
                    'uniacid' => $_W['uniacid'],
                    'merchid' => $_W['merchid']
                )
            );

            if ($item['paytype'] == 3) {
                m('order')->setStocksAndCredits($item['id'], 2);
            }

            plog('order.op.sendcancel',
                '订单取消发货 ID: ' . $item['id'] .
                ' 订单号: ' . $item['ordersn'] .
                ' 原因: ' . $remark);

            show_json(1);
        }

        include $this->template();
    }

    /**
     * 取货
     */
    public function fetch()
    {
        global $_W;
        global $_GPC;

        $opdata = $this->opData();

        extract($opdata);

        if ($item['status'] != 1) {
            message('订单未付款，无法确认取货！');
        }

        $time = time();

        $d = array(
            'status'     => 3,
            'sendtime'   => $time,
            'finishtime' => $time
        );

        if ($item['isverify'] == 1) {
            $d['verified']     = 1;
            $d['verifytime']   = $time;
            $d['verifyopenid'] = '';
        }

        //mark kafka 为了kafka转成了model执行
        $this->_orderModel->updateByColumn(
            $d,
            array(
                'id'      => $item['id'],
                'uniacid' => $_W['uniacid'],
                'merchid' => $_W['merchid']
            )
        );

        if (!empty($item['refundid'])) {

            $refund = pdo_fetch(
                ' select * ' .
                ' from ' . tablename('superdesk_shop_order_refund') .
                ' where id=:id limit 1',
                array(
                    ':id' => $item['refundid']
                )
            );

            if (!empty($refund)) {

                pdo_update(
                    'superdesk_shop_order_refund',
                    array(
                        'status' => -1
                    ),
                    array(
                        'id' => $item['refundid']
                    )
                );

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    array(
                        'refundstate' => 0
                    ),
                    array(
                        'id' => $item['id']
                    )
                );
            }
        }
        m('order')->setGiveBalance($item['id'], 1);
        m('member')->upgradeLevel($item['openid']);
        m('notice')->sendOrderMessage($item['id']);

        if (p('commission')) {
            p('commission')->checkOrderFinish($item['id']);
        }

        plog('order.op.fetch', '订单确认取货 ID: ' . $item['id'] . ' 订单号: ' . $item['ordersn']);

        show_json(1);
    }

    /**
     * 订单管理->确认发货(&id)(order id)
     */
    public function send()
    {
        global $_W;
        global $_GPC;

        $opdata = $this->opData();

        extract($opdata);

        if (empty($item['addressid'])) {
            show_json(0, '无收货地址，无法发货！');
        }

        if ($item['paytype'] != 3) {

            if ($item['status'] != 1) {
                show_json(0, '订单未付款，无法发货！');
            }

        }

        if ($_W['ispost']) {

            if (!empty($_GPC['isexpress']) && empty($_GPC['expresssn'])) {
                show_json(0, '请输入快递单号！');
            }

            if (!empty($item['transid'])) {
            }

            $time = time();

            //mark kafka 为了kafka转成了model执行
            $this->_orderModel->updateByColumn(
                array(
                    'status'     => 2,
                    'express'    => trim($_GPC['express']),
                    'expresscom' => trim($_GPC['expresscom']),
                    'expresssn'  => trim($_GPC['expresssn']),
                    'sendtime'   => $time
                ),
                array(
                    'id'      => $item['id'],
                    'uniacid' => $_W['uniacid'],
                    'merchid' => $_W['merchid']
                )
            );

            if (!empty($item['cancelid'])) {

                $refund = pdo_fetch(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_order_cancel') .
                    ' where id=:id limit 1',
                    array(
                        ':id' => $item['cancelid']
                    )
                );

                if (!empty($refund)) {

                    pdo_update('superdesk_shop_order_cancel',
                        array(
                            'status'  => -1,
                            'canceltime' => $time
                        ),
                        array(
                            'id' => $item['cancelid']
                        )
                    );

                    //mark kafka 为了kafka转成了model执行
                    $this->_orderModel->updateByColumn(
                        array(
                            'cancel_status' => 0
                        ),
                        array(
                            'id' => $item['id']
                        )
                    );
                }
            }

            plog('order.op.send',
                '订单发货 ID: ' . $item['id'] .
                ' 订单号: ' . $item['ordersn'] .
                ' <br/>快递公司: ' . $_GPC['expresscom'] .
                ' 快递单号: ' . $_GPC['expresssn']);


//            $id   为 order_id
//            $item 为 order
            // TODO jd_vop 如果是企业月结与商户SUPERDESK_SHOPV2_JD_VOP_MERCHID 要向京东提交确认订单
            if ($item['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID) {
                include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
                $_orderService = new OrderService();
                $_orderService->confirmOrderByShopOrderId($item['id']);
            }

            // TODO 插入订单财务表 zjh 2018年4月25日 11:09:33 添加
            $this->_order_financeModel->addOrderFinance(array(
                'orderid' => $item['id'],
                'ordersn' => $item['ordersn'],
                'merchid' => $item['merchid'],
                'press_status' => 1
            ));

            m('notice')->sendOrderMessage($item['id']);

            if ($item['paytype'] == 3) {
                m('order')->setStocksAndCredits($item['id'], 1);
            }

            show_json(1);
        }

        $address = iunserializer($item['address']);

        if (!is_array($address)) {
            $address = pdo_fetch(
                ' SELECT * ' .
                ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
                ' WHERE '.
                '       id = :id '.
                '       and uniacid=:uniacid',
                array(
                    ':id'      => $item['addressid'],
                    ':uniacid' => $_W['uniacid']
                )
            );
        }

        $express_list = m('express')->getExpressList();


        // jd_vop start 订单发货默认填充


        if ($item['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID) {

            include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
            $_orderService = new OrderService();

            $_order_submit_order = $_orderService->getJDOrderSubmitByShopOrderId($id);

            if ($_order_submit_order) {
                $item['express']    = 'jd';
                $item['expresssn']  = $_order_submit_order['jd_vop_result_jdOrderId'];
                $item['expresscom'] = '京东快递';
            }
        }


        // jd_vop end

        include $this->template();
    }

    public function remarksaler()
    {
        global $_W;
        global $_GPC;

        $opdata = $this->opData();
        extract($opdata);

        if ($_W['ispost']) {

            //mark kafka 为了kafka转成了model执行
            $this->_orderModel->updateByColumn(
                array(
                    'remarksaler' => $_GPC['remark']
                ),
                array(
                    'id'      => $item['id'],
                    'uniacid' => $_W['uniacid'],
                    'merchid' => $_W['merchid']
                )
            );

            plog(
                'order.op.remarksaler',
                '订单备注 ID: ' . $item['id'] .
                ' 订单号: ' . $item['ordersn'] .
                ' 备注内容: ' . $_GPC['remark']
            );

            show_json(1);
        }
        include $this->template();
    }

    public function changeexpress()
    {
        global $_W;
        global $_GPC;

        $opdata = $this->opData();

        extract($opdata);

        $edit_flag = 1;

        if ($_W['ispost']) {

            $express    = $_GPC['express'];
            $expresscom = $_GPC['expresscom'];
            $expresssn  = trim($_GPC['expresssn']);

            if (empty($id)) {
                $ret = '参数错误！';
                show_json(0, $ret);
            }

            if (!empty($expresssn)) {
                $change_data               = array();
                $change_data['express']    = $express;
                $change_data['expresscom'] = $expresscom;
                $change_data['expresssn']  = $expresssn;

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    $change_data,
                    array(
                        'id'      => $id,
                        'uniacid' => $_W['uniacid'],
                        'merchid' => $_W['merchid']
                    )
                );

                plog(
                    'order.op.changeexpress',
                    '修改快递状态 ID: ' . $item['id'] .
                    ' 订单号: ' . $item['ordersn'] .
                    ' 快递公司: ' . $expresscom .
                    ' 快递单号: ' . $expresssn
                );

                show_json(1);

            } else {
                show_json(0, '请填写快递单号！');
            }
        }
        $address = iunserializer($item['address']);

        if (!is_array($address)) {

            $address = pdo_fetch(
                ' SELECT * ' .
                ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
                ' WHERE id = :id ' .
                '       and uniacid=:uniacid',
                array(
                    ':id'      => $item['addressid'],
                    ':uniacid' => $_W['uniacid']
                )
            );
        }

        $express_list = m('express')->getExpressList();

        include $this->template('order/op/send');
    }

    public function changeinvoice()
    {
        global $_W;
        global $_GPC;

        $view_invoiceType = array(
            1 => '增值税普票',
            2 => '增值税专票'
        );

        $view_selectedInvoiceTitle = array(
            4 => '个人',
            5 => '单位'
        );

        $view_invoiceContent = array(
            1  => '明细',
            3  => '电脑配件',
            19 => '耗材',
            22 => '办公用品'
        );

        $opdata = $this->opData();

        extract($opdata);

//        socket_log("iunserializer(item)");
//        socket_log(json_encode($item, JSON_UNESCAPED_UNICODE));

//        array(
//            'id'   => $id, // order id
//            'item' => $item// order
//        )

        if (empty($item['invoiceid'])) {

//            $invoice = unserialize($item['carrier']);
//            socket_log("empty(item['invoiceid'])");
//            socket_log(json_encode('这个是不开票', JSON_UNESCAPED_UNICODE));

        } else {

            $invoice = iunserializer($item['invoice']);// 结构 invoice

//            socket_log("iunserializer(item['invoice'])");
//            socket_log(json_encode($invoice, JSON_UNESCAPED_UNICODE));

// 结构 invoice
//{
//    "id": "286",
//    "uniacid": "16",
//    "openid": "oX8KYwutiI5NEJ20LIF8zNINwSng",
//    "realname": "",
//    "invoiceState": "1",
//    "invoiceType": "1",
//    "selectedInvoiceTitle": "5",
//    "companyName": "代采购订单",
//    "taxpayersIDcode": "",
//    "invoiceContent": "1",
//    "invoiceName": "",
//    "invoicePhone": "",
//    "invoiceProvice": "0",
//    "invoiceCity": "0",
//    "invoiceCounty": "0",
//    "invoiceAddress": "",
//    "invoiceBank": "",
//    "invoiceAccount": "",
//    "isdefault": "1",
//    "deleted": "0",
//    "createtime": "0",
//    "updatetime": "0"
//}

            if (!is_array($invoice)) {

                $invoice = pdo_fetch(
                    ' SELECT * ' .
                    ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 不处理
                    ' WHERE id = :id and uniacid=:uniacid',
                    array(
                        ':id'      => $item['invoiceid'],
                        ':uniacid' => $_W['uniacid']
                    )
                );// 结构 invoice

//                socket_log("!is_array(invoice)");
//                socket_log(json_encode($invoice, JSON_UNESCAPED_UNICODE));
            }

//            $invoice_info = $invoice['invoice'];

//            $invoice['invoice'] = $invoice['province'] . ' ' . $invoice['city'] . ' ' . $invoice['area'] . ' ' . $invoice['town'] . ' ' . $invoice['invoice'];

            $item['invoicedata'] = $oldinvoice = array(
                'realname' => $invoice['realname'],
                'mobile'   => $invoice['mobile'],
                'invoice'  => $invoice['invoice']
            );
        }

        if ($_W['ispost']) {


            $invoiceType          = intval(trim($_GPC['invoiceType']));// 发票类型 增值税普票 1 增值税专票 2
            $selectedInvoiceTitle = intval(trim($_GPC['selectedInvoiceTitle']));//发票抬头 个人 4 单位 5
            $companyName          = trim($_GPC['companyName']);//请在此填写发票抬头
            $taxpayersIDcode      = trim($_GPC['taxpayersIDcode']);//请在此填写纳税人识别号
            $invoiceAddress       = trim($_GPC['invoiceAddress']);//请在此填写注册地址
            $invoicePhone         = trim($_GPC['invoicePhone']);//请在此填写注册电话
            $invoiceBank          = trim($_GPC['invoiceBank']);//请在此填写开户银行
            $invoiceAccount       = trim($_GPC['invoiceAccount']);//请在此填写银行账号
            $invoiceContent       = intval(trim($_GPC['invoiceContent']));// 发票内容 明细 1 电脑配件 3 耗材 19 办公用品 22

            $invoice = trim($_GPC['invoice']);

            if (!empty($id)) {


                // check data start
                // 发票类型 增值普票 1 增值专票 2
                // 发票抬头 个人 4 单位 5

                if (empty($invoiceType)) {
                    $ret = '请选择发票类型！';
                    show_json(0, $ret);
                }

                if (empty($selectedInvoiceTitle)) {
                    $ret = '请选择发票抬头！';
                    show_json(0, $ret);
                }

                if (empty($invoiceContent)) {
                    $ret = '请选择发票内容！';
                    show_json(0, $ret);
                }

                if (empty($companyName)) {
                    $ret = '请填写发票抬头';
                    show_json(0, $ret);
                }

                if ($invoiceType == 1) { // 增值普票 1

                    if ($selectedInvoiceTitle == 4) {// 个人 4

                    } else if ($selectedInvoiceTitle == 5) { // 单位 5
                        if (empty($taxpayersIDcode)) {
                            $ret = '请填写纳税人识别号';
                            show_json(0, $ret);
                        }
                    }

                } else if ($invoiceType == 2) {

                    if (empty($taxpayersIDcode)) {
                        $ret = '请填写纳税人识别号';
                        show_json(0, $ret);
                    }

                    if ($selectedInvoiceTitle == 4) {// 个人 4
                        $ret = '增值专票不为个人开放';
                        show_json(0, $ret);

                    } else if ($selectedInvoiceTitle == 5) { // 单位 5

                        if (empty($invoiceAddress)) {
                            $ret = '请填写注册地址';
                            show_json(0, $ret);
                        }
                        if (empty($invoicePhone)) {
                            $ret = '请填写注册电话';
                            show_json(0, $ret);
                        }
                        if (empty($invoiceBank)) {
                            $ret = '请填写开户银行';
                            show_json(0, $ret);
                        }
                        if (empty($invoiceAccount)) {
                            $ret = '请填写银行账号';
                            show_json(0, $ret);
                        }
                    }
                }
                // check data end


                $second_query_order = pdo_fetch(
                    ' SELECT id, ordersn, invoice, invoiceid' .
                    ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
                    ' WHERE id = :id ' .
                    '       and uniacid=:uniacid ' .
                    '       and merchid = :merchid',
                    array(
                        ':id'      => $id,
                        ':uniacid' => $_W['uniacid'],
                        ':merchid' => $_W['merchid']
                    )
                );

                $invoice_array = iunserializer($second_query_order['invoice']);
                if ($invoice_array == false || !is_array($invoice_array)) { // 这个是为了修正之前 invoiceName问题
                    $invoice_array = array();
                }

                $invoice_array['invoiceType']          = $invoiceType;// 发票类型 增值税普票 1 增值税专票 2
                $invoice_array['selectedInvoiceTitle'] = $selectedInvoiceTitle;//发票抬头 个人 4 单位 5
                $invoice_array['companyName']          = $companyName;//请在此填写发票抬头
                $invoice_array['taxpayersIDcode']      = $taxpayersIDcode;//请在此填写纳税人识别号
                $invoice_array['invoiceAddress']       = $invoiceAddress;//请在此填写注册地址
                $invoice_array['invoicePhone']         = $invoicePhone;//请在此填写注册电话
                $invoice_array['invoiceBank']          = $invoiceBank;//请在此填写开户银行
                $invoice_array['invoiceAccount']       = $invoiceAccount;//请在此填写银行账号
                $invoice_array['invoiceContent']       = $invoiceContent;// 发票内容 明细 1 电脑配件 3 耗材 19 办公用品 22

                $this->_member_invoiceModel->update($invoice_array, $second_query_order['invoiceid']);//$invoice_array['id']

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    array(
                        'invoice' => iserializer($invoice_array)
                    ),
                    array(
                        'id'      => $id,
                        'uniacid' => $_W['uniacid'],
                        'merchid' => $_W['merchid']
                    )
                );

//                plog('order.op.changeinvoice',
//                    '修改收货地址 ID: ' . $item['id'] .
//                    ' 订单号: ' . $item['ordersn'] .
//                    ' <br>原地址: 收件人: ' . $oldinvoice['realname'] .
//                    ' 手机号: ' . $oldinvoice['mobile'] .
//                    ' 收件地址: ' . $oldinvoice['invoice'] .
//                    ' <br>新地址: 收件人: ' . $realname .
//                    ' 手机号: ' . $mobile .
//                    ' 收件地址: ' . $invoice_array['province'] . ' ' . $invoice_array['city'] . ' ' . $invoice_array['area'] . ' ' . $invoice_array['town'] . ' ' . $invoice_array['invoice']
//                );

                show_json(1);
            }
        }
        include $this->template();
    }

    public function changeaddress()
    {
        global $_W;
        global $_GPC;
        $opdata = $this->opData();

        extract($opdata);

//        array(
//            'id'   => $id, // order id
//            'item' => $item// order
//        )

        if (empty($item['addressid'])) {

            $user = unserialize($item['carrier']);
            socket_log("empty(item['addressid'])");
            socket_log(json_encode($user, JSON_UNESCAPED_UNICODE));

        } else {

            $user = iunserializer($item['address']);// 结构 address

//            socket_log("iunserializer(item['address'])");
//            socket_log(json_encode($user, JSON_UNESCAPED_UNICODE));

// 结构 address
//{
//    "id": "605",
//    "uniacid": "16",
//    "openid": "oX8KYwoONLCdoCM21IRrnTU-acqs",
//    "realname": "肖冠鸿",
//    "mobile": "13164736799",
//    "province": "广东",
//    "city": "深圳市",
//    "area": "宝安区",
//    "address": "西乡街道乐群社区艇巷村46号502",
//    "isdefault": "1",
//    "zipcode": "",
//    "deleted": "0",
//    "jd_vop_province_code": "19",
//    "jd_vop_city_code": "1607",
//    "jd_vop_county_code": "4773",
//    "jd_vop_town_code": "0",
//    "jd_vop_area": "19_1607_4773_0"
//}

            if (!is_array($user)) {

                $user = pdo_fetch(
                    ' SELECT * ' .
                    ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
                    ' WHERE id = :id and uniacid=:uniacid',
                    array(
                        ':id'      => $item['addressid'],
                        ':uniacid' => $_W['uniacid']
                    )
                );// 结构 address

//                socket_log("!is_array(user)");
//                socket_log(json_encode($user, JSON_UNESCAPED_UNICODE));
            }

            $address_info = $user['address'];

            $user['address'] = $user['province'] . ' ' . $user['city'] . ' ' . $user['area'] . ' ' . $user['town'] . ' ' . $user['address'];

            $item['addressdata'] = $oldaddress = array(
                'realname' => $user['realname'],
                'mobile'   => $user['mobile'],
                'address'  => $user['address']
            );
        }

        if ($_W['ispost']) {

            $realname = trim($_GPC['realname']);
            $mobile   = trim($_GPC['mobile']);

            $province = intval(trim($_GPC['province']));
            $city     = intval(trim($_GPC['city']));
            $area     = intval(trim($_GPC['area']));
            $town     = intval(trim($_GPC['town']));

            $address = trim($_GPC['address']);

            if (!empty($id)) {
                if (empty($realname)) {
                    $ret = '请填写收件人姓名！';
                    show_json(0, $ret);
                }
                if (empty($mobile)) {
                    $ret = '请填写收件人手机！';
                    show_json(0, $ret);
                }
                if ($province == '请选择省份') {
                    $ret = '请选择省份！';
                    show_json(0, $ret);
                }
                if (empty($address)) {
                    $ret = '请填写详细地址！';
                    show_json(0, $ret);
                }

                $second_query_order = pdo_fetch(
                    ' SELECT id, ordersn, address, addressid ' .
                    ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
                    ' WHERE id = :id ' .
                    '       and uniacid=:uniacid ' .
                    '       and merchid = :merchid',
                    array(
                        ':id'      => $id,
                        ':uniacid' => $_W['uniacid'],
                        ':merchid' => $_W['merchid']
                    )
                );

                $address_array             = iunserializer($second_query_order['address']);
                $address_array['realname'] = $realname;
                $address_array['mobile']   = $mobile;

                $address_array['province'] = $this->_areaModel->getTextByCode($province);
                $address_array['city']     = $this->_areaModel->getTextByCode($city);
                $address_array['area']     = $this->_areaModel->getTextByCode($area);
                $address_array['town']     = $this->_areaModel->getTextByCode($town);

                $address_array['jd_vop_province_code'] = $province;
                $address_array['jd_vop_city_code']     = $city;
                $address_array['jd_vop_county_code']   = $area;
                $address_array['jd_vop_town_code']     = $town;
                $address_array['jd_vop_area']          = $province . '_' . $city . '_' . $area . '_' . $town;


                $address_array['address'] = $address;


                $this->_member_addressModel->update($address_array, $second_query_order['addressid']);

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    array(
                        'address' => iserializer($address_array)
                    ),
                    array(
                        'id'      => $id,
                        'uniacid' => $_W['uniacid'],
                        'merchid' => $_W['merchid']
                    )
                );

                plog('order.op.changeaddress',
                    '修改收货地址 ID: ' . $item['id'] .
                    ' 订单号: ' . $item['ordersn'] .
                    ' <br>原地址: 收件人: ' . $oldaddress['realname'] .
                    ' 手机号: ' . $oldaddress['mobile'] .
                    ' 收件地址: ' . $oldaddress['address'] .
                    ' <br>新地址: 收件人: ' . $realname .
                    ' 手机号: ' . $mobile .
                    ' 收件地址: ' . $address_array['province'] . ' ' . $address_array['city'] . ' ' . $address_array['area'] . ' ' . $address_array['town'] . ' ' . $address_array['address']
                );

                show_json(1);
            }
        }
        include $this->template();
    }

    public function transfer()
    {
        global $_W;
        global $_GPC;
        $opdata = $this->opData();
        extract($opdata);

        if ($_W['ispost']) {
            $new_id = $_GPC['new_id'];

            if (empty($id)) {
                $ret = '参数错误！';
                show_json(0, $ret);
            }


            if (!empty($new_id)) {

                pdo_insert('superdesk_shop_order_transfer',
                    array(
                        'uniacid'    => $_W['uniacid'],
                        'orderid'    => $id,
                        'old_id'     => $item['merchid'],
                        'new_id'     => $new_id,
                        'createtime' => $item['createtime'],
                        'updatetime' => time()
                    )
                );

                $change_data            = array();
                $change_data['merchid'] = $new_id;

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    $change_data,
                    array(
                        'id'      => $id,
                        'uniacid' => $_W['uniacid']
                    )
                );

                plog(
                    'order.op.transfer',
                    '修改快递状态 ID: ' . $item['id'] .
                    ' 订单号: ' . $item['ordersn'] .
                    ' 旧商户id: ' . $item['merchid'] .
                    ' 新商户id: ' . $new_id
                );

                show_json(1);
            } else {
                show_json(0, '请选择转让商户！');
            }
        }

        $merchlist = pdo_fetchall(
            ' SELECT id,merchname ' .
            ' FROM ' . tablename('superdesk_shop_merch_user') .
            ' WHERE uniacid=:uniacid ' .
            '   and id!=:id and status!=2 ',
            array(
                ':id'      => $item['merchid'],
                ':uniacid' => $_W['uniacid']
            )
        );

        include $this->template();
    }

    public function send_examine_notice(){
        global $_W,$_GPC;

        $opdata = $this->opData();

        extract($opdata);

        $member = m('member')->getMember($item['openid'], $item['core_user']);

        include_once(IA_ROOT . '/addons/superdesk_shopv2/service/member/MemberService.class.php');
        $_memberService      = new MemberService();

        //获取需要推送的采购经理
        $manager_arr = $_memberService->getMemberListByCashRule($member['core_enterprise']);

        // 推送
        foreach ($manager_arr as $auditor) {

//            openid,core_user,core_enterprise,core_organization,mobile

            m('notice')->sendExamineCreateNotice(
                $auditor['openid'],
                $auditor['core_user'],
                $auditor['core_enterprise'],
                $auditor['core_organization'],
                $auditor['mobile'],
                $member['realname'], $item['ordersn'], $item['price'], $item['id']);
        }

        show_json(1);
    }
}