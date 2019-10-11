<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_finance.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');

include_once(IA_ROOT . '/framework/library/logs/LogsUtil.class.php');

class Order_SuperdeskShopV2Model
{
    private $_order_financeModel;
    private $_orderModel;

    public function __construct()
    {
        $this->_order_financeModel = new order_financeModel();
        $this->_orderModel         = new orderModel();
    }

    /**
     * 支付回调
     *
     * @param $params
     *
     * @return bool
     */
    public function payResult($params)
    {
        global $_W;

//        socket_log("payResult start =>");
//        socket_log("1 level return params ...");
//        socket_log(json_encode($params));

        // 企业月结返回 测试过,会在京东平台上添加
        // {"result":"success","type":"cash","from":"return","tid":"ME20171211144610094785","user":"otNFxuOh8MWAIewTiZ_tpLdiSKc0","fee":"37.59","weid":16,"uniacid":16}

        // 余额支付
        // {"result":"success","type":"",    "from":"return","tid":"ME20171211145329142548","user":"0","fee":"37.59","weid":null,"uniacid":"16"} // 测试记录
        // {"result":"success","type":"",    "from":"return","tid":"ME20171215164244686848","user":"4206","fee":"13.35","weid":null,"uniacid":"16"} // 正式记录

        // 微信支付
        // {"result":"success","type":"",      "from":"return","tid":"ME20171215180352864578","user":"4206","fee":"0.01","tag":"","weid":null,"uniacid":"16"} // 正式setup01
        // {"result":"success","type":"wechat","from":"return","tid":"ME20171215180352864578","user":"4206","fee":"0.01","weid":null,"uniacid":"16","deduct":false} // 正式setup02

        $fee  = intval($params['fee']);
        $data = array('status' => ($params['result'] == 'success' ? 1 : 0));

        $ordersn = $params['tid'];

        $order = pdo_fetch(
            ' select ' .
            '       id,ordersn, price,openid,core_user,dispatchtype,addressid,carrier,status,isverify,deductcredit2,' .
            '       `virtual`,isvirtual,couponid,isvirtualsend,isparent,paytype,' .
            '       ismerch,merchid,' .
            '       agentid,createtime,buyagainprice ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where  ordersn=:ordersn and uniacid=:uniacid limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':ordersn' => $ordersn
            )
        );

        $orderid = $order['id'];

        if ($params['from'] == 'return') {

            $address = false;

            if (empty($order['dispatchtype'])) {

                $address = pdo_fetch(
                    ' select realname,mobile,address ' .
                    ' from ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
                    ' where ' .
                    '       id=:id ' .
                    ' limit 1',
                    array(
                        ':id' => $order['addressid']
                    )
                );
            }

            $carrier = false;

            if (($order['dispatchtype'] == 1) || ($order['isvirtual'] == 1)) {
                $carrier = unserialize($order['carrier']);
            }

            // 企业月结
            if ($params['type'] == 'cash') {

                if ($order['isparent'] == 1) {

                    $change_data              = array();
                    $change_data['merchshow'] = 1;

                    //mark kafka 为了kafka转成了model执行
                    $this->_orderModel->updateByColumn(
                        $change_data,
                        array(
                            'id' => $orderid
                        )
                    );

                    $this->setChildOrderPayResult($order, 0, 0);
                }

                socket_log("TEST_POINT_core_model_order: " . json_encode($order));

                // TODO 审核功能    zjh 20180420 添加
                // TODO BUG_20180716
                m('examine')->addExamine($order);


                m('notice')->sendOrderMessage($orderid);

                com_run('printer::sendOrderMessage', $orderid);

                return true;
            }

            if ($order['status'] == 0) {

                if (!empty($order['virtual']) && com('virtual')) {
                    return com('virtual')->pay($order);
                }
                if ($order['isvirtualsend']) {
                    return $this->payVirtualSend($order['id']);
                }

                $time = time();

                $change_data            = array();
                $change_data['status']  = 1;
                $change_data['paytime'] = $time;

                if ($order['isparent'] == 1) {
                    $change_data['merchshow'] = 1;
                }

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    $change_data,
                    array(
                        'id' => $orderid
                    )
                );

                // 向多个商户购买
                if ($order['isparent'] == 1) {

                    // 更新子订单状态
                    $this->setChildOrderPayResult($order, $time, 1);

                } /* jd_vop start */
                else {

                    // 这个IF 是为了自动发货才加的 余额 and 微信 支付成功
                    if ($order['ismerch'] == 1 /*是商户*/
                        && $order['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID /* TODO 商户ID=8 is avic_jdbbuy */
                    ) {
                        // call jd_vop 7.2  确认预占库存订单接口

                        include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
                        $_orderService = new OrderService();
//                        $_orderService->confirmOrderByShopOrderId($orderid);
                        $_orderService->autoSendByShopOrderId($_W['uniacid'], $order['merchid'], $orderid);

                        // $params['type'] == 'wechat' 微信支付
                        // $params['type'] == '' 余额支付
                        // 以上情况加入 财务跟踪
                        $this->_order_financeModel->addOrderFinanceDefaultPressStatusEQ2($order['id'], $order['ordersn'], $order['merchid']);
                    }


                }
                /* jd_vop end   */

                $this->setStocksAndCredits($orderid, 1);

                if (com('coupon') && !empty($order['couponid'])) {
                    com('coupon')->backConsumeCoupon($order['id']);
                }


                m('notice')->sendOrderMessage($orderid);
                com_run('printer::sendOrderMessage', $orderid);

                //加载 20190704 luoxt 订单信息加入队列
                try {
                    load()->func('communication');
                    $loginurl = $_W['config']['api_host'].'/api/base/message/collect';
                    $post = [
                        "merchid"  => $order['merchid'],
                        "msg_type"  =>  "orders",
                        "msg_body"=>[
                            "ordersn" => $order['ordersn'],
                            "order_type"  =>  1,//1付款完成 2已发货 3:已完成,-1:已关闭
                            "time"  =>  time()
                        ]
                    ];
                    $response = ihttp_post($loginurl, $post);
                } catch (Exception $e) { }

                if (p('commission')) {
                    p('commission')->checkOrderPay($order['id']);
                }

            }
            return true;
        }
        return false;
    }


    public function setChildOrderPayResult($order, $time, $type/* 1 wechat timing | 0 cash timing */)
    {
        global $_W;

        $orderid = $order['id'];// main order id

        $sub_order_list = $this->getChildOrder($orderid);

        if (!empty($sub_order_list)) {

            $change_data = array();

            if ($type == 1) {
                $change_data['status']  = 1;
                $change_data['paytime'] = $time;
            }

            $change_data['merchshow'] = 0;

            foreach ($sub_order_list as $key => $sub_order) {

                if ($sub_order['status'] == 0) {
                    //mark kafka 为了kafka转成了model执行
                    $this->_orderModel->updateByColumn(
                        $change_data,
                        array(
                            'id' => $sub_order['id']
                        )
                    );
                }

                // 这个IF 是为了自动发货才加的 余额 and 微信 支付成功
                if ($sub_order['ismerch'] == 1 /*是商户*/
                    && $sub_order['merchid'] == SUPERDESK_SHOPV2_JD_VOP_MERCHID /* TODO 商户ID=8 is avic_jdbbuy*/
                ) {
                    if ($type == 1) {
                        // call jd_vop 7.2  确认预占库存订单接口

                        include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
                        $_orderService = new OrderService();
//                        $_orderService->confirmOrderByShopOrderId($sub_order['id']);
                        $_orderService->autoSendByShopOrderId($_W['uniacid'], $sub_order['merchid'], $sub_order['id']);

                        // $params['type'] == 'wechat' 微信支付
                        // $params['type'] == '' 余额支付
                        // 以上情况加入 财务跟踪
                        $this->_order_financeModel->addOrderFinanceDefaultPressStatusEQ2($sub_order['id'], $sub_order['ordersn'], $sub_order['merchid']);

                    }
                }


            }
        }
    }

    public function setOrderPayType($orderid, $paytype)
    {
        global $_W;

        // 更新主订单
        //mark kafka 为了kafka转成了model执行
        $this->_orderModel->updateByColumn(
            array(
                'paytype' => $paytype
            ),
            array(
                'id' => $orderid
            )
        );

        $order = pdo_fetch(
            ' select isparent ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where  id=:id and uniacid=:uniacid limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':id'      => $orderid
            )
        );

        // 更新单
        if (!empty($order['isparent'])) {
            //mark kafka 为了kafka转成了model执行
            $this->_orderModel->updateByColumn(
                array(
                    'paytype' => $paytype
                ),
                array(
                    'parentid' => $orderid,
                    'uniacid'  => $_W['uniacid']
                )
            );
        }
    }

    /**
     * 获取子订单
     *
     * @param $orderid
     *
     * @return array
     */
    public function getChildOrder($orderid)
    {
        global $_W;

        $list = pdo_fetchall(
            ' select ' .
            '       id,ordersn,status,finishtime,couponid,merchid,ismerch,price  ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where ' .
            '       parentid=:parentid ' .
            '       and uniacid=:uniacid',
            array(
                ':parentid' => $orderid,
                ':uniacid'  => $_W['uniacid']
            )
        );
        return $list;
    }

    public function payVirtualSend($orderid = 0)
    {
        global $_W;
        global $_GPC;

        $order = pdo_fetch(
            ' select ' .
            '       id,ordersn, price,openid,core_user,dispatchtype,addressid,carrier,status,isverify,deductcredit2,`virtual`,isvirtual,couponid ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':id'      => $orderid
            )
        );

        $order_goods = pdo_fetch(
            ' select ' .
            '       g.virtualsend,g.virtualsendcontent ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where ' .
            '       og.orderid=:orderid ' .
            '       and og.uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':orderid' => $orderid
            )
        );


        $time = time();

        //mark kafka 为了kafka转成了model执行
        $this->_orderModel->updateByColumn(
            array(
                'virtualsend_info' => $order_goods['virtualsendcontent'],
                'status'           => '3',
                'paytime'          => $time,
                'sendtime'         => $time,
                'finishtime'       => $time
            ),
            array(
                'id' => $orderid
            )
        );

        $this->setStocksAndCredits($orderid, 1);

        m('member')->upgradeLevel($order['openid'], $order['core_user']);
        m('order')->setGiveBalance($orderid, 1);

        if (com('coupon') && !empty($order['couponid'])) {
            com('coupon')->backConsumeCoupon($order['id']);
        }

        m('notice')->sendOrderMessage($orderid);

        if (p('commission')) {
            p('commission')->checkOrderPay($order['id']);
            p('commission')->checkOrderFinish($order['id']);
        }

        return true;
    }

    /**
     * 计算订单中商品累计赠送的积分
     *
     * @param $goods
     *
     * @return int
     */
    public function getGoodsCredit($goods)
    {
        global $_W;
        $credits = 0;
        foreach ($goods as $g) {
            $gcredit = trim($g['credit']);
            if (!empty($gcredit)) {
                if (strexists($gcredit, '%')) {
                    $credits += intval((floatval(str_replace('%', '', $gcredit)) / 100) * $g['realprice']);
                } else {
                    $credits += intval($g['credit']) * $g['total'];
                }
            }
        }
        return $credits;
    }

    /**
     * 返还抵扣的余额
     *
     * @param $order
     */
    public function setDeductCredit2($order)
    {
        global $_W;
        if (0 < $order['deductcredit2']) {
            m('member')->setCredit($order['openid'], $order['core_user'],
                'credit2',
                $order['deductcredit2'],
                array('0', $_W['shopset']['shop']['name'] . '购物返还抵扣余额 余额: ' . $order['deductcredit2'] . ' 订单号: ' . $order['ordersn'])
            );
        }
    }

    /**
     * 处理赠送余额情况
     *
     * @param string $orderid
     * @param int    $type 1 订单完成 2 售后
     */
    public function setGiveBalance($orderid = '', $type = 0)
    {
        global $_W;
        $order = pdo_fetch(
            ' select id,ordersn,price,openid,core_user,dispatchtype,addressid,carrier,status ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where id=:id limit 1',
            array(
                ':id' => $orderid
            )
        );

        $goods = pdo_fetchall(
            'select og.goodsid,og.total,g.totalcnf,og.realprice,g.money,og.optionid,g.total as goodstotal,og.optionid,g.sales,g.salesreal ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' . // TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where og.orderid=:orderid and og.uniacid=:uniacid ',
            array(
                ':uniacid' => $_W['uniacid'],
                ':orderid' => $orderid
            )
        );

        $balance = 0;
        foreach ($goods as $g) {
            $gbalance = trim($g['money']);
            if (!empty($gbalance)) {
                if (strexists($gbalance, '%')) {
                    $balance += intval((floatval(str_replace('%', '', $gbalance)) / 100) * $g['realprice']);
                } else {
                    $balance += intval($g['money']) * $g['total'];
                }
            }
        }
        if (0 < $balance) {

            $shopset = m('common')->getSysset('shop');

            if ($type == 1) {

                if ($order['status'] == 3) {
                    m('member')->setCredit($order['openid'], $order['core_user'],
                        'credit2', $balance, array(0, $shopset['name'] . '购物赠送余额 订单号: ' . $order['ordersn']));
                    return;
                }
            } else if ($type == 2) {
                if (1 <= $order['status']) {
                    m('member')->setCredit($order['openid'], $order['core_user'],
                        'credit2', -$balance, array(0, $shopset['name'] . '购物取消订单扣除赠送余额 订单号: ' . $order['ordersn']));
                }
            }
        }
    }

    /**
     * 处理赠送余额情况
     *
     * @param string $orderid
     * @param int    $type 1 订单完成 2 售后
     */
    public function setGiveBalanceByGoods($orderid = '', $order_goods_id = '', $type = 0)
    {
        global $_W;
        $order = pdo_fetch(
            ' select id,ordersn,price,openid,core_user,dispatchtype,addressid,carrier,status ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where id=:id limit 1',
            array(
                ':id' => $orderid
            )
        );

        $goods   = pdo_fetch(
            ' select og.goodsid,og.total,g.totalcnf,og.realprice,g.money,og.optionid,g.total as goodstotal,og.optionid,g.sales,g.salesreal,g.title ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where og.id=:id and og.uniacid=:uniacid ',
            array(':uniacid' => $_W['uniacid'], ':id' => $order_goods_id)
        );
        $balance = 0;

        $gbalance = trim($goods['money']);
        if (!empty($gbalance)) {
            if (strexists($gbalance, '%')) {
                $balance = intval((floatval(str_replace('%', '', $gbalance)) / 100) * $goods['realprice']);
            } else {
                $balance = intval($goods['money']) * $goods['total'];
            }
        }

        if (0 < $balance) {
            $shopset = m('common')->getSysset('shop');
            if ($type == 1) {
                if ($order['status'] == 3) {
                    m('member')->setCredit($order['openid'], $order['core_user'],
                        'credit2', $balance, array(0, $shopset['name'] . '购物赠送余额 订单号: ' . $order['ordersn'] . ' 商品名称:' . $goods['title']));
                    return;
                }
            } else if ($type == 2) {
                if (1 <= $order['status']) {
                    m('member')->setCredit($order['openid'], $order['core_user'],
                        'credit2', -$balance, array(0, $shopset['name'] . '购物取消订单扣除赠送余额 订单号: ' . $order['ordersn'] . ' 商品名称:' . $goods['title']));
                }
            }
        }
    }

    /**
     * 处理订单库存及用户积分情况(赠送积分)
     *
     * @param string $orderid
     * @param int    $type 0 下单 1 支付 2 取消
     */
    public function setStocksAndCredits($orderid = '', $type = 0)
    {
        global $_W;

        try{
            $order = pdo_fetch(
                ' select id,ordersn,price,openid,core_user,dispatchtype,addressid,carrier,status,isparent ' .
                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where id=:id limit 1',
                array(
                    ':id' => $orderid
                )
            );


            $param             = array();
            $param[':uniacid'] = $_W['uniacid'];

            if ($order['isparent'] == 1) {
                $condition               = ' og.parentorderid=:parentorderid';
                $param[':parentorderid'] = $orderid;
            } else {
                $condition         = ' og.orderid=:orderid';
                $param[':orderid'] = $orderid;
            }


            $goods = pdo_fetchall(
                ' select og.goodsid,og.total,g.totalcnf,og.realprice,g.credit,og.optionid,g.total as goodstotal,og.optionid,g.sales,g.salesreal ' .
                ' from ' . tablename('superdesk_shop_order_goods') . ' og ' . // TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
                ' where ' . $condition . ' and og.uniacid=:uniacid ', $param);


            $credits = 0;
            foreach ($goods as $g) {
                $stocktype = 0;
                if ($type == 0) {
                    if ($g['totalcnf'] == 0) {
                        $stocktype = -1;
                    }
                } else if ($type == 1) {
                    if ($g['totalcnf'] == 1) {
                        $stocktype = -1;
                    }
                } else if ($type == 2) {
                    if (1 <= $order['status']) {
                        if ($g['totalcnf'] == 1) {
                            $stocktype = 1;
                        }
                    } else if ($g['totalcnf'] == 0) {
                        $stocktype = 1;
                    }
                }
                if (!empty($stocktype)) {
                    if (!empty($g['optionid'])) {
                        $option = m('goods')->getOption($g['goodsid'], $g['optionid']);
                        if (!empty($option) && ($option['stock'] != -1)) {
                            $stock = -1;
                            if ($stocktype == 1) {
                                $stock = $option['stock'] + $g['total'];
                            } else if ($stocktype == -1) {
                                $stock = $option['stock'] - $g['total'];
                                ($stock <= 0) && ($stock = 0);
                            }
                            if ($stock != -1) {
                                pdo_update('superdesk_shop_goods_option', array('stock' => $stock), array('uniacid' => $_W['uniacid'], 'goodsid' => $g['goodsid'], 'id' => $g['optionid']));
                            }
                        }
                    }
                    if (!empty($g['goodstotal']) && ($g['goodstotal'] != -1)) {
                        $totalstock = -1;
                        if ($stocktype == 1) {
                            $totalstock = $g['goodstotal'] + $g['total'];
                        } else if ($stocktype == -1) {
                            $totalstock = $g['goodstotal'] - $g['total'];
                            ($totalstock <= 0) && ($totalstock = 0);
                        }
                        if ($totalstock != -1) {
                            pdo_update('superdesk_shop_goods', array('total' => $totalstock), array('uniacid' => $_W['uniacid'], 'id' => $g['goodsid']));
                        }
                    }
                }
                $gcredit = trim($g['credit']);
                if (!empty($gcredit)) {
                    if (strexists($gcredit, '%')) {
                        $credits += intval((floatval(str_replace('%', '', $gcredit)) / 100) * $g['realprice']);
                    } else {
                        $credits += intval($g['credit']) * $g['total'];
                    }
                }
                if ($type == 0) {
                    if ($g['totalcnf'] != 1) {
                        pdo_update('superdesk_shop_goods', array('sales' => $g['sales'] + $g['total']), array('uniacid' => $_W['uniacid'], 'id' => $g['goodsid']));
                    }
                } else if ($type == 1) {
                    if (1 <= $order['status']) {
                        if ($g['totalcnf'] != 1) {
                            pdo_update('superdesk_shop_goods', array('sales' => $g['sales'] - $g['total']), array('uniacid' => $_W['uniacid'], 'id' => $g['goodsid']));
                        }
                        $salesreal = pdo_fetchcolumn(
                            ' select ifnull(sum(total),0) from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                            ' left join ' . tablename('superdesk_shop_order') . ' o on o.id = og.orderid ' . // TODO 标志 楼宇之窗 openid shop_order 不处理
                            ' where og.goodsid=:goodsid and o.status>=1 and o.uniacid=:uniacid limit 1',
                            array(
                                ':goodsid' => $g['goodsid'],
                                ':uniacid' => $_W['uniacid']
                            )
                        );

                        pdo_update('superdesk_shop_goods', array('salesreal' => $salesreal), array('id' => $g['goodsid']));
                    }
                }
            }
            if (0 < $credits) {
                $shopset = m('common')->getSysset('shop');
                if ($type == 1) {
                    m('member')->setCredit($order['openid'], $order['core_user'],
                        'credit1', $credits, array(0, $shopset['name'] . '购物积分 订单号: ' . $order['ordersn']));
                    return;
                }
                if ($type == 2) {
                    if (1 <= $order['status']) {
                        m('member')->setCredit($order['openid'], $order['core_user'],
                            'credit1', -$credits, array(0, $shopset['name'] . '购物取消订单扣除积分 订单号: ' . $order['ordersn']));
                    }
                }
            }
        } catch(\Exception $e){

            //日志记录
            LogsUtil::logging('info', $e, 'set_stock_error');
        }

    }

    public function getTotals($merch = 0)
    {
        global $_W;

        $paras = array(':uniacid' => $_W['uniacid']);

        $merch = intval($merch);

        $condition = ' and isparent=0';

        $goods_condition = '';
        if ($merch < 0) {
            $condition .= ' and merchid=0';
            $goods_condition .= ' and merchid=0';
        }

        $totals['all']      = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and ismr=0 and deleted=0', $paras);// TODO 标志 楼宇之窗 openid shop_order 不处理
        $totals['status_1'] = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and ismr=0 and status=-1 and refundtime=0 and deleted=0', $paras);// TODO 标志 楼宇之窗 openid shop_order 不处理
        $totals['status0']  = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and ismr=0  and status=0 and paytype<>3 and deleted=0', $paras);// TODO 标志 楼宇之窗 openid shop_order 不处理
        $totals['status1']  = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and ismr=0  and ( status=1 or ( status=0 and paytype=3) ) and deleted=0', $paras);// TODO 标志 楼宇之窗 openid shop_order 不处理
        $totals['status2']  = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and ismr=0  and status=2 and deleted=0', $paras);// TODO 标志 楼宇之窗 openid shop_order 不处理
        $totals['status3']  = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and ismr=0  and status=3 and deleted=0', $paras);// TODO 标志 楼宇之窗 openid shop_order 不处理
        $totals['status4']  = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and ismr=0  and refundstate>0 and refundid<>0 and deleted=0', $paras);// TODO 标志 楼宇之窗 openid shop_order 不处理
        $totals['status5']  = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order') . '' . ' WHERE uniacid = :uniacid ' . $condition . ' and ismr=0 and refundtime<>0 and deleted=0', $paras);// TODO 标志 楼宇之窗 openid shop_order 不处理

        //新维权
        $totals['status6']  = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order_goods') . '' . ' WHERE uniacid = :uniacid ' . $goods_condition . ' and rstate>0 and refundid<>0 ', $paras);// TODO 标志 楼宇之窗 openid shop_order 不处理
        $totals['status7']  = pdo_fetchcolumn('SELECT COUNT(1) FROM ' . tablename('superdesk_shop_order_goods') . '' . ' WHERE uniacid = :uniacid ' . $goods_condition . ' and refundtime<>0 ', $paras);// TODO 标志 楼宇之窗 openid shop_order 不处理

        return $totals;
    }

    public function getFormartDiscountPrice($isd, $gprice, $gtotal = 1)
    {
        $price = $gprice;
        if (!empty($isd)) {
            if (strexists($isd, '%')) {
                $dd = floatval(str_replace('%', '', $isd));
                if ((0 < $dd) && ($dd < 100)) {
                    $price = round(($dd / 100) * $gprice, 2);
                }
            } else if (0 < floatval($isd)) {
                $price = round(floatval($isd * $gtotal), 2);
            }
        }
        return $price;
    }

    public function getGoodsDiscounts($goods, $isdiscount_discounts, $levelid, $options = array())
    {
        $key    = ((empty($levelid) ? 'default' : 'level' . $levelid));
        $prices = array();
        if (empty($goods['merchsale'])) {
            if (!empty($isdiscount_discounts[$key])) {
                foreach ($isdiscount_discounts[$key] as $k => $v) {
                    $k              = substr($k, 6);
                    $op_marketprice = m('goods')->getOptionPirce($goods['id'], $k);
                    $gprice         = $this->getFormartDiscountPrice($v, $op_marketprice);
                    $prices[]       = $gprice;
                    if (!empty($options)) {
                        foreach ($options as $key => $value) {
                            if ($value['id'] == $k) {
                                $options[$key]['marketprice'] = $gprice;
                            }
                        }
                    }
                }
            }
        } else if (!empty($isdiscount_discounts['merch'])) {
            foreach ($isdiscount_discounts['merch'] as $k => $v) {
                $k              = substr($k, 6);
                $op_marketprice = m('goods')->getOptionPirce($goods['id'], $k);
                $gprice         = $this->getFormartDiscountPrice($v, $op_marketprice);
                $prices[]       = $gprice;
                if (!empty($options)) {
                    foreach ($options as $key => $value) {
                        if ($value['id'] == $k) {
                            $options[$key]['marketprice'] = $gprice;
                        }
                    }
                }
            }
        }
        $data            = array();
        $data['prices']  = $prices;
        $data['options'] = $options;
        return $data;
    }

    public function getGoodsDiscountPrice($g, $level, $type = 0)
    {
        if ($type == 0) {
            $total = $g['total'];
        } else {
            $total = 1;
        }
	
        $gprice = $g['marketprice'] * $total;
        if (empty($g['buyagain_islong'])) {
            //假如没开启购买一次后都用这个折扣
            $gprice = $g['marketprice'] * $total;
        }
        $buyagain_sale = true;
        $buyagainprice = 0;
        $canbuyagain   = false;
        if (0 < floatval($g['buyagain'])) {
            //是否有重复购买折扣
            if (m('goods')->canBuyAgain($g)) {
                $canbuyagain = true;
                //是否可与其他优惠叠加
                if (empty($g['buyagain_sale'])) {
                    $buyagain_sale = false;
                }
            }
        }
        $price           = $gprice;
        $price1          = $gprice;
        $price2          = $gprice;
        $discountprice   = 0;
        $isdiscountprice = 0;
        $isd             = false;
        @$isdiscount_discounts = json_decode($g['isdiscount_discounts'], true);
        $discounttype = 0;
        $isCdiscount  = 0;
        $isHdiscount  = 0;
        if ($g['isdiscount'] && (time() <= $g['isdiscount_time']) && $buyagain_sale) {
            //促销优惠
            if (is_array($isdiscount_discounts)) {
                $key = ((!empty($level['id']) ? 'level' . $level['id'] : 'default'));
                if (!isset($isdiscount_discounts['type']) || empty($isdiscount_discounts['type'])) {
                    if (empty($g['merchsale'])) {
                        $isd = trim($isdiscount_discounts[$key]['option0']);
                        if (!empty($isd)) {
                            $price1 = $this->getFormartDiscountPrice($isd, $gprice, $total);
                        }
                    } else {
                        $isd = trim($isdiscount_discounts['merch']['option0']);
                        if (!empty($isd)) {
                            $price1 = $this->getFormartDiscountPrice($isd, $gprice, $total);
                        }
                    }
                } else if (empty($g['merchsale'])) {
                    $isd = trim($isdiscount_discounts[$key]['option' . $g['optionid']]);
                    if (!empty($isd)) {
                        $price1 = $this->getFormartDiscountPrice($isd, $gprice, $total);
                    }
                } else {
                    $isd = trim($isdiscount_discounts['merch']['option' . $g['optionid']]);
                    if (!empty($isd)) {
                        $price1 = $this->getFormartDiscountPrice($isd, $gprice, $total);
                    }
                }
            }
            if ($gprice < $price1) {
                $isdiscountprice = 0;
            } else {
                $isdiscountprice = abs($price1 - $gprice);
            }
            $isCdiscount = 1;
        }
        if (empty($g['isnodiscount']) && $buyagain_sale) {
            //会员折扣优惠
            $discounts = json_decode($g['discounts'], true);
            if (is_array($discounts)) {
                $key = ((!empty($level['id']) ? 'level' . $level['id'] : 'default'));
                if (!isset($discounts['type']) || empty($discounts['type'])) {
                    if (!empty($discounts[$key])) {
                        $dd = floatval($discounts[$key]);
                        if ((0 < $dd) && ($dd < 10)) {
                            $price2 = round(($dd / 10) * $gprice, 2);
                        }
                    } else {
                        $dd = floatval($discounts[$key . '_pay'] * $total);
                        $md = floatval($level['discount']);
                        if (!empty($dd)) {
                            $price2 = round($dd, 2);
                        } else if (0 < $md) {
                            $price2 = round(($md / 10) * $gprice, 2);
                        }
                    }
                } else {
                    $isd = trim($discounts[$key]['option' . $g['optionid']]);
                    if (!empty($isd)) {
                        $price2 = $this->getFormartDiscountPrice($isd, $gprice, $total);
                    }
                }
            }
            $discountprice = abs($price2 - $gprice);
            $isHdiscount   = 1;
        }
        if ($isCdiscount == 1) {
            $price        = $price1;
            $discounttype = 1;
        } else if ($isHdiscount == 1) {
            $price        = $price2;
            $discounttype = 2;
        }
        $unitprice           = round($price / $total, 2);
        $isdiscountunitprice = round($isdiscountprice / $total, 2);
        $discountunitprice   = round($discountprice / $total, 2);
        if ($canbuyagain) {
            //重复购买优惠
            if (empty($g['buyagain_islong'])) {
                $buyagainprice = ($unitprice * (10 - $g['buyagain'])) / 10;
            } else {
                $buyagainprice = ($price * (10 - $g['buyagain'])) / 10;
            }
        }
        $price = $price - $buyagainprice;
        return array('unitprice' => $unitprice, 'price' => $price, 'discounttype' => $discounttype, 'isdiscountprice' => $isdiscountprice, 'discountprice' => $discountprice, 'isdiscountunitprice' => $isdiscountunitprice, 'discountunitprice' => $discountunitprice, 'price0' => $gprice, 'price1' => $price1, 'price2' => $price2, 'buyagainprice' => $buyagainprice);
    }

    public function getGoodsCategoryEnterpriseDiscount($g, $prices, $type = 0){
        global $_W;

        if ($type == 0) {
            $total = $g['total'];
        } else {
            $total = 1;
        }
        $gprice = $g['marketprice'] * $total;
        $prices['CEDiscountPrice'] = 0;

        //2019年5月28日 17:27:19 zjh 价套
        $category_enterprise_discount = m('goods')->getCategoryEnterpriseDiscountRedis($_W['core_enterprise'],$g['merchid'],$g['tcate']);
        $cediscount = $category_enterprise_discount['discount'];

//        if($_W['core_user'] == 16205){
//            var_dump($g['total']);
//            var_dump($prices);
//            var_dump($category_enterprise_discount);
//            die;
//        }

        if($category_enterprise_discount['type'] == 2){
            $prices['CEDiscountPrice'] = round($prices['price0'] - ($g['costprice'] * $total), 2);
            $prices['price'] = round($prices['price'] - $prices['CEDiscountPrice'], 2);
            $prices['unitprice'] = round($prices['price'] / $total, 2);
        }else{
            if($cediscount > 0){
                $prices['price'] = round($prices['price'] * $cediscount, 2);
                $prices['CEDiscountPrice'] = round($prices['price0'] - $prices['price'], 2);
                $prices['unitprice'] = round($prices['price'] / $total, 2);
            }
        }


        return $prices;
    }

    public function getChildOrderPrice($order, $goods, $dispatch_array, $merch_array, $sale_plugin, $discountprice_array)
    {
        global $_GPC;
        $totalprice    = $order['price'];
        $goodsprice    = $order['goodsprice'];
        $grprice       = $order['grprice'];
        $deductprice   = $order['deductprice'];
        $deductcredit  = $order['deductcredit'];
        $deductcredit2 = $order['deductcredit2'];
        $deductenough  = $order['deductenough'];
        $is_deduct     = 0;
        $is_deduct2    = 0;
        $deduct_total  = 0;
        $deduct2_total = 0;
        $ch_order      = array();
        if ($sale_plugin) {
            if (!empty($_GPC['deduct'])) {
                $is_deduct = 1;
            }
            if (!empty($_GPC['deduct2'])) {
                $is_deduct2 = 1;
            }
        }
        foreach ($goods as &$g) {
            $merchid                           = $g['merchid'];
            $ch_order[$merchid]['goods'][]     = $g['goodsid'];
            $ch_order[$merchid]['grprice']     += $g['ggprice'];
            $ch_order[$merchid]['goodsprice']  += $g['marketprice'] * $g['total'];
            $ch_order[$merchid]['couponprice'] = $discountprice_array[$merchid]['deduct'];
            if ($is_deduct == 1) {
                if ($g['manydeduct']) {
                    $deduct = $g['deduct'] * $g['total'];
                } else {
                    $deduct = $g['deduct'];
                }
                $deduct_total                      += $deduct;
                $ch_order[$merchid]['deducttotal'] += $deduct;
            }
            if ($is_deduct2 == 1) {
                if ($g['deduct2'] == 0) {
                    $deduct2 = $g['ggprice'];
                } else if (0 < $g['deduct2']) {
                    if ($g['ggprice'] < $g['deduct2']) {
                        $deduct2 = $g['ggprice'];
                    } else {
                        $deduct2 = $g['deduct2'];
                    }
                }
                $deduct2_total                      += $deduct2;
                $ch_order[$merchid]['deduct2total'] += $deduct2;
            }
        }
        unset($g);
        foreach ($ch_order as $k => $v) {
            if ($is_deduct == 1) {
                if (0 < $deduct_total) {
                    $n                            = $v['deducttotal'] / $deduct_total;
                    $deduct_credit                = ceil(round($deductcredit * $n, 2));
                    $deduct_money                 = round($deductprice * $n, 2);
                    $ch_order[$k]['deductcredit'] = $deduct_credit;
                    $ch_order[$k]['deductprice']  = $deduct_money;
                }
            }
            if ($is_deduct2 == 1) {
                if (0 < $deduct2_total) {
                    $n                             = $v['deduct2total'] / $deduct2_total;
                    $deduct_credit2                = round($deductcredit2 * $n, 2);
                    $ch_order[$k]['deductcredit2'] = $deduct_credit2;
                }
            }
            $op                 = round($v['grprice'] / $grprice, 2);
            $ch_order[$k]['op'] = $op;
            if (0 < $deductenough) {
                $deduct_enough                = round($deductenough * $op, 2);
                $ch_order[$k]['deductenough'] = $deduct_enough;
            }
        }
        foreach ($ch_order as $k => $v) {
            $merchid = $k;
            $price   = ($v['grprice'] - $v['deductprice'] - $v['deductcredit2'] - $v['deductenough'] - $v['couponprice']) + $dispatch_array['dispatch_merch'][$merchid];
            if (0 < $merchid) {
                $merchdeductenough = $merch_array[$merchid]['enoughdeduct'];
                if (0 < $merchdeductenough) {
                    $price                                   -= $merchdeductenough;
                    $ch_order[$merchid]['merchdeductenough'] = $merchdeductenough;
                }
            }
            $ch_order[$merchid]['price'] = $price;
        }
        return $ch_order;
    }

    public function getMerchEnough($merch_array)
    {
        $merch_enough_total = 0;
        $merch_saleset      = array();
        foreach ($merch_array as $key => $value) {
            $merchid = $key;
            if (0 < $merchid) {
                $enoughs = $value['enoughs'];
                if (!empty($enoughs)) {
                    $ggprice = $value['ggprice'];
                    foreach ($enoughs as $e) {
                        if ((floatval($e['enough']) <= $ggprice) && (0 < floatval($e['money']))) {
                            $merch_array[$merchid]['showenough']   = 1;
                            $merch_array[$merchid]['enoughmoney']  = $e['enough'];
                            $merch_array[$merchid]['enoughdeduct'] = $e['money'];
                            $merch_saleset['merch_showenough']     = 1;
                            $merch_saleset['merch_enoughmoney']    += $e['enough'];
                            $merch_saleset['merch_enoughdeduct']   += $e['money'];
                            $merch_enough_total                    += floatval($e['money']);
                            break;
                        }
                    }
                }
            }
        }
        $data                       = array();
        $data['merch_array']        = $merch_array;
        $data['merch_enough_total'] = $merch_enough_total;
        $data['merch_saleset']      = $merch_saleset;
        return $data;
    }

    /**
     * 运费
     *
     * @param      $goods
     * @param      $member
     * @param      $address
     * @param bool $saleset
     * @param      $merch_array
     * @param      $t
     * @param int  $loop
     *
     * @return array
     */
    public function getOrderDispatchPrice($goods, $member, $address, $saleset = false, $merch_array, $t, $loop = 0)
    {

//        socket_log('goods:'.json_encode($goods,JSON_UNESCAPED_UNICODE));
//        socket_log('address:'.json_encode($address,JSON_UNESCAPED_UNICODE));
//        socket_log('member:'.json_encode($member,JSON_UNESCAPED_UNICODE));
        socket_log('saleset:' . json_encode($saleset, JSON_UNESCAPED_UNICODE));


        // 单商户下数据记录
//        [{"goodsid":"1231113","total":"1","maxbuy":"0","type":"1","issendfree":"0","isnodiscount":"0","weight":"100.00","optionweight":null,"title":"[标准] 得力(deli)φ6mm可打35页两孔打孔器打孔机(带标尺) 大号混色0104","thumb":"https:\/\/wxm.avic-s.com\/attachment\/images\/16\/merch\/36\/t5OFXVhqtxhtqc8x5tHk8LvwwXXZte.png","marketprice":"26.25","optiontitle":null,"optionid":"0","storeids":"","isverify":"1","deduct":"0.00","manydeduct":"0","virtual":"0","optionvirtual":null,"discounts":null,"deduct2":"-1.00","ednum":"0","edmoney":"0.00","edareas":"","diyformtype":"0","diyformid":"0","diymode":"0","dispatchtype":"0","dispatchid":"4","dispatchprice":"0.00","minbuy":"0","isdiscount":"0","isdiscount_time":"1527663360","isdiscount_discounts":"{\"type\":0,\"merch\":{\"option0\":\"\"}}","cates":"729","virtualsend":"0","invoice":"1","specs":null,"merchid":"36","checked":"0","merchsale":"1","buyagain":"0.00","buyagain_islong":"0","buyagain_condition":"0","buyagain_sale":"0","jd_vop_sku":"0","jd_vop_page_num":"0","is_task_goods":0,"totalmaxbuy":null,"ggprice":26.25,"unitprice":26.25,"dflag":0,"taskdiscountprice":null,"discountprice":0,"isdiscountprice":0,"discounttype":2,"isdiscountunitprice":0,"discountunitprice":0}]
//        {"id":"621","uniacid":"16","openid":"oX8KYwkxwNW6qzHF4cF-tGxYTcPg","realname":"林进雨","mobile":"13422832499","province":"广东","city":"珠海市","area":"斗门区","town":"乾务镇","address":"ddddd","isdefault":"1","zipcode":"","deleted":"0","jd_vop_province_code":"19","jd_vop_city_code":"1609","jd_vop_county_code":"41653","jd_vop_town_code":"41768","jd_vop_area":"19_1609_41653_41768"}
//        {"id":"1944","uniacid":"16","uid":"0","groupid":"0","level":"0","agentid":"0","openid":"oX8KYwirBIYB8l0WRQyiLqRX2Ecw","realname":"张娜妮","mobile":"18018124646","pwd":"d4ad7db633d22bd29b2e3d04e2ecdb18","weixin":"","content":null,"agenttime":"0","status":"0","isagent":"0","clickcount":"0","agentlevel":"0","noticeset":null,"nickname":"珍妮","credit1":"0.00","credit2":"0.00","birthyear":"","birthmonth":"","birthday":"","gender":"2","avatar":"http:\/\/thirdwx.qlogo.cn\/mmopen\/vi_32\/l2uibWia3thrcygxQLiaVBiaUEqfWGcnWPHibbJfhNMc8qaMrpibI2SeufW4W4Xxcpd9OjicBrj5GdziaQmT5mCmIwkakw\/132","province":"江苏","city":"苏州","area":"","childtime":"0","agentnotupgrade":"0","inviter":"0","agentselectgoods":"0","agentblack":"0","username":"","fixagentid":"0","diymemberid":"0","diymemberdataid":"0","diymemberdata":null,"diycommissionid":"0","diycommissiondataid":"0","diycommissiondata":null,"isblack":"0","diymemberfields":null,"diycommissionfields":null,"commission_total":"0.00","endtime2":"0","ispartner":"0","partnertime":"0","partnerstatus":"0","partnerblack":"0","partnerlevel":"0","partnernotupgrade":"0","diyglobonusid":"0","diyglobonusdata":null,"diyglobonusfields":null,"isaagent":"0","aagentlevel":"0","aagenttime":"0","aagentstatus":"0","aagentblack":"0","aagentnotupgrade":"0","aagenttype":"0","aagentprovinces":null,"aagentcitys":null,"aagentareas":null,"diyaagentid":"0","diyaagentdata":null,"diyaagentfields":null,"salt":"TmnyLomdnnuaM5zl","mobileverify":"1","mobileuser":"0","carrier_mobile":"0","isauthor":"0","authortime":"0","authorstatus":"0","authorblack":"0","authorlevel":"0","authornotupgrade":"0","diyauthorid":"0","diyauthordata":null,"diyauthorfields":null,"authorid":"0","comefrom":null,"openid_qq":null,"openid_wx":null,"core_enterprise":"1575","createtime":"1527659391","updatetime":"1527659391","logintime":"1528099298","cash_role_id":"0"}
//        {"enoughmoney":0,"enoughdeduct":0,"enoughs":[],"enoughfree":0,"enoughorder":100,"enoughareas":"","goodsids":null,"recharges":"a:0:{}","creditdeduct":0,"credit":1,"money":1,"moneydeduct":0,"dispatchnodeduct":0}

        $realprice        = 0;           //实际金额
        $dispatch_price   = 0;           //运费
        $dispatch_array   = array();    //运费模板数组
        $dispatch_merch   = array();    //运费商户
        $total_array      = array();    //总数
        $totalprice_array = array();    //总金额
        $nodispatch_array = array();    //不配送区域数组
        $user_city        = '';         //用户所在地区


        if (!empty($address)) {     //假如有收货地址
            $user_city = $address['city'];
        } else if (!empty($member['city'])) {   //假如没收货地址,但会员信息中有地址
            $user_city = $member['city'];
        }

        foreach ($goods as $g) {
            $realprice                       += $g['ggprice'];  //累加商品金额
            $dispatch_merch[$g['merchid']]   = 0;               //以商户id为key塞个0进去????
            $total_array[$g['goodsid']]      += $g['total'];    //
            $totalprice_array[$g['goodsid']] += $g['ggprice'];
        }
//        <b>Warning</b>:  Invalid argument supplied for foreach() in <b>/data/wwwroot/wxn.avic-s.com/addons/superdesk_shopv2/core/model/order.php</b> on line <b>932</b><br />

        foreach ($goods as $g) {

            $isnodispatch = 0;
            $sendfree     = false;
            $merchid      = $g['merchid'];

            if (!empty($g['issendfree'])) { // 包邮

                $sendfree = true;

            } else {

                if (($g['ednum'] <= $total_array[$g['goodsid']]) && (0 < $g['ednum'])) { // 单品满件包邮

                    $gareas = explode(';', $g['edareas']);

                    if (empty($gareas)) {

                        $sendfree = true;

                    } else if (!empty($address)) {

                        if (!in_array($address['city'], $gareas)) {

                            $sendfree = true;

                        }

                    } else if (!empty($member['city'])) {

                        if (!in_array($member['city'], $gareas)) {

                            $sendfree = true;

                        }

                    } else {

                        $sendfree = true;

                    }
                }

                if ((floatval($g['edmoney']) <= $totalprice_array[$g['goodsid']]) && (0 < floatval($g['edmoney']))) { // 单品满额包邮

                    $gareas = explode(';', $g['edareas']);

                    if (empty($gareas)) {

                        $sendfree = true;

                    } else if (!empty($address)) {

                        if (!in_array($address['city'], $gareas)) {
                            $sendfree = true;
                        }

                    } else if (!empty($member['city'])) {

                        if (!in_array($member['city'], $gareas)) {
                            $sendfree = true;
                        }

                    } else {
                        $sendfree = true;
                    }
                }
            }

            // 统一邮费
            if ($g['dispatchtype'] == 1) {

                if (!empty($user_city)) {

                    $citys = m('dispatch')->getAllNoDispatchAreas();

                    if (!empty($citys)) {

                        if (in_array($user_city, $citys) && !empty($citys)) {

                            $isnodispatch = 1;
                            $has_goodsid  = 0;

                            if (!empty($nodispatch_array['goodid'])) {

                                if (in_array($g['goodsid'], $nodispatch_array['goodid'])) {
                                    $has_goodsid = 1;
                                }

                            }

                            if ($has_goodsid == 0) {

                                $nodispatch_array['goodid'][] = $g['goodsid'];
                                $nodispatch_array['title'][]  = $g['title'];
                                $nodispatch_array['city']     = $user_city;
                            }
                        }
                    }
                }

                if ((0 < $g['dispatchprice']) && !$sendfree && ($isnodispatch == 0)) {

                    $dispatch_price           += $g['dispatchprice'];
                    $dispatch_merch[$merchid] += $g['dispatchprice'];

                }

            } // 选择运费模板
            else if ($g['dispatchtype'] == 0) { // 运费模板 dispatchtype = 0

                if (empty($g['dispatchid'])) {

                    $dispatch_data = m('dispatch')->getDefaultDispatch($merchid);

                } else {

                    $dispatch_data = m('dispatch')->getOneDispatch($g['dispatchid']);

                }

                if (empty($dispatch_data)) {

                    $dispatch_data = m('dispatch')->getNewDispatch($merchid);

                }

                if (!empty($dispatch_data)) {

                    $dkey = $dispatch_data['id'];

                    if (!empty($user_city)) {

                        $citys = m('dispatch')->getAllNoDispatchAreas($dispatch_data['nodispatchareas']);

                        if (!empty($citys)) {

                            if (in_array($user_city, $citys) && !empty($citys)) {

                                $isnodispatch = 1;
                                $has_goodsid  = 0;

                                if (!empty($nodispatch_array['goodid'])) {
                                    if (in_array($g['goodsid'], $nodispatch_array['goodid'])) {
                                        $has_goodsid = 1;
                                    }
                                }

                                if ($has_goodsid == 0) {
                                    $nodispatch_array['goodid'][] = $g['goodsid'];
                                    $nodispatch_array['title'][]  = $g['title'];
                                    $nodispatch_array['city']     = $user_city;
                                }
                            }
                        }
                    }

                    if (!$sendfree && (0 < $g['dispatchprice']) && !$sendfree && ($isnodispatch == 0)) {

                        $areas = unserialize($dispatch_data['areas']);

                        if ($dispatch_data['calculatetype'] == 1) {
                            $param = $g['total'];
                        } else {
                            $param = $g['weight'] * $g['total'];
                        }

                        if (array_key_exists($dkey, $dispatch_array)) {
                            $dispatch_array[$dkey]['param'] += $param;
                        } else {
                            $dispatch_array[$dkey]['data']  = $dispatch_data;
                            $dispatch_array[$dkey]['param'] = $param;
                        }
                    }
                }
            }
        }
        if (!empty($dispatch_array)) {

            foreach ($dispatch_array as $k => $v) {

                $dispatch_data = $dispatch_array[$k]['data'];
                $param         = $dispatch_array[$k]['param'];
                $areas         = unserialize($dispatch_data['areas']);

                if (!empty($address)) {

                    $dprice = m('dispatch')->getCityDispatchPrice($areas, $address['city'], $param, $dispatch_data);

                } else if (!empty($member['city'])) {

                    $dprice = m('dispatch')->getCityDispatchPrice($areas, $member['city'], $param, $dispatch_data);

                } else {

                    $dprice = m('dispatch')->getDispatchPrice($param, $dispatch_data);

                }

                $dispatch_price           += $dprice;
                $merchid                  = $dispatch_data['merchid'];
                $dispatch_merch[$merchid] += $dprice;
            }

        }

        if (!empty($merch_array)) {

            foreach ($merch_array as $key => $value) {

                $merchid = $key;

                if (0 < $merchid) {

                    $merchset = $value['set'];

                    if (!empty($merchset['enoughfree'])) {

                        if (floatval($merchset['enoughorder']) <= 0) {

                            $dispatch_price           = $dispatch_price - $dispatch_merch[$merchid];
                            $dispatch_merch[$merchid] = 0;

                        } else if (floatval($merchset['enoughorder']) <= $merch_array[$merchid]['ggprice']) {

                            if (empty($merchset['enoughareas'])) {

                                $dispatch_price           = $dispatch_price - $dispatch_merch[$merchid];
                                $dispatch_merch[$merchid] = 0;

                            } else {

                                $areas = explode(';', $merchset['enoughareas']);

                                if (!empty($address)) {

                                    if (!in_array($address['city'], $areas)) {
                                        $dispatch_price           = $dispatch_price - $dispatch_merch[$merchid];
                                        $dispatch_merch[$merchid] = 0;
                                    }

                                } else if (!empty($member['city'])) {

                                    if (!in_array($member['city'], $areas)) {
                                        $dispatch_price           = $dispatch_price - $dispatch_merch[$merchid];
                                        $dispatch_merch[$merchid] = 0;
                                    }

                                } else if (empty($member['city'])) {

                                    $dispatch_price           = $dispatch_price - $dispatch_merch[$merchid];
                                    $dispatch_merch[$merchid] = 0;

                                }
                            }
                        }
                    }
                }
            }
        }

        if ($saleset) {

            if (!empty($saleset['enoughfree'])) {

                $saleset_free = 0;

                if ($loop == 0) {

                    if (floatval($saleset['enoughorder']) <= 0) {
                        $saleset_free = 1;
                    } else if (floatval($saleset['enoughorder']) <= $realprice) {
                        if (empty($saleset['enoughareas'])) {
                            $saleset_free = 1;
                        } else {
                            $areas = explode(';', $saleset['enoughareas']);
                            if (!empty($address)) {
                                if (!in_array($address['city'], $areas)) {
                                    $saleset_free = 1;
                                }
                            } else if (!empty($member['city'])) {
                                if (!in_array($member['city'], $areas)) {
                                    $saleset_free = 1;
                                }
                            } else if (empty($member['city'])) {
                                $saleset_free = 1;
                            }
                        }
                    }
                }
                if ($saleset_free == 1) {
                    $is_nofree = 0;
                    if (!empty($saleset['goodsids'])) {
                        foreach ($goods as $k => $v) {
                            if (!in_array($v['goodsid'], $saleset['goodsids'])) {
                                unset($goods[$k]);
                            } else {
                                $is_nofree = 1;
                            }
                        }
                    }
                    if (($is_nofree == 1) && ($loop == 0)) {
                        $new_data       = $this->getOrderDispatchPrice($goods, $member, $address, $saleset, $merch_array, $t, 1);
                        $dispatch_price = $new_data['dispatch_price'];
                    } else if ($saleset_free == 1) {
                        $dispatch_price = 0;
                    }
                }
            }
        }

        if ($dispatch_price == 0) {

            foreach ($dispatch_merch as &$dm) {
                $dm = 0;
            }

            unset($dm);
        }

        if (!empty($nodispatch_array)) {

            $nodispatch = '商品';

            foreach ($nodispatch_array['title'] as $k => $v) {
                $nodispatch .= $v . ',';
            }

            $nodispatch                       = trim($nodispatch, ',');
            $nodispatch                       .= '不支持配送到' . $nodispatch_array['city'];
            $nodispatch_array['nodispatch']   = $nodispatch;
            $nodispatch_array['isnodispatch'] = 1;

        }


        $data                     = array();
        $data['dispatch_price']   = $dispatch_price;
        $data['dispatch_merch']   = $dispatch_merch;
        $data['nodispatch_array'] = $nodispatch_array;

        return $data;
    }

    public function changeParentOrderPrice($parent_order)
    {
        global $_W;
        $id   = $parent_order['id'];
        $item = pdo_fetch(
            ' SELECT price,ordersn2,dispatchprice,changedispatchprice ' .
            ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE id = :id and uniacid=:uniacid',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );
        if (!empty($item)) {
            $orderupdate                        = array();
            $orderupdate['price']               = $item['price'] + $parent_order['price_change'];
            $orderupdate['ordersn2']            = $item['ordersn2'] + 1;
            $orderupdate['dispatchprice']       = $item['dispatchprice'] + $parent_order['dispatch_change'];
            $orderupdate['changedispatchprice'] = $item['changedispatchprice'] + $parent_order['dispatch_change'];
            if (!empty($orderupdate)) {
                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    $orderupdate,
                    array(
                        'id'      => $id,
                        'uniacid' => $_W['uniacid']
                    )
                );
            }
        }
    }

    public function getOrderCommission($orderid, $agentid = 0)
    {
        global $_W;

        if (empty($agentid)) {
            $item = pdo_fetch(
                'select agentid from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
                ' where id=:id and uniacid=:uniacid Limit 1',
                array(
                    'id'       => $orderid,
                    ':uniacid' => $_W['uniacid']
                )
            );

            if (!empty($item)) {
                $agentid = $item['agentid'];
            }
        }

        $level = 0;

        $pc    = p('commission');
        if ($pc) {
            $pset  = $pc->getSet();
            $level = intval($pset['level']);
        }

        $commission1 = 0;
        $commission2 = 0;
        $commission3 = 0;

        $m1          = false;
        $m2          = false;
        $m3          = false;

        if (!empty($level)) {
            if (!empty($agentid)) {
                $m1 = m('member')->getMemberById($agentid);
                if (!empty($m1['agentid'])) {
                    $m2 = m('member')->getMemberById($m1['agentid']);
                    if (!empty($m2['agentid'])) {
                        $m3 = m('member')->getMemberById($m2['agentid']);
                    }
                }
            }
        }


        $order_goods = pdo_fetchall(
            ' select ' .
            '       g.id,g.title,g.thumb,g.goodssn,og.goodssn as option_goodssn, g.productsn,' .
            '       og.productsn as option_productsn, og.total,og.price,og.optionname as optiontitle, og.realprice,og.changeprice,og.oldprice,og.commission1,og.commission2,og.commission3,og.commissions,og.diyformdata,og.diyformfields ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            '       left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where og.uniacid=:uniacid and og.orderid=:orderid ',
            array(
                ':uniacid' => $_W['uniacid'],
                ':orderid' => $orderid
            )
        );


        foreach ($order_goods as &$og) {
            if (!empty($level) && !empty($agentid)) {
                $commissions = iunserializer($og['commissions']);
                if (!empty($m1)) {
                    if (is_array($commissions)) {
                        $commission1 += ((isset($commissions['level1']) ? floatval($commissions['level1']) : 0));
                    } else {
                        $c1          = iunserializer($og['commission1']);
                        $l1          = $pc->getLevel($m1['openid'], $m1['core_user']);
                        $commission1 += ((isset($c1['level' . $l1['id']]) ? $c1['level' . $l1['id']] : $c1['default']));
                    }
                }
                if (!empty($m2)) {
                    if (is_array($commissions)) {
                        $commission2 += ((isset($commissions['level2']) ? floatval($commissions['level2']) : 0));
                    } else {
                        $c2          = iunserializer($og['commission2']);
                        $l2          = $pc->getLevel($m2['openid'], $m2['core_user']);
                        $commission2 += ((isset($c2['level' . $l2['id']]) ? $c2['level' . $l2['id']] : $c2['default']));
                    }
                }
                if (!empty($m3)) {
                    if (is_array($commissions)) {
                        $commission3 += ((isset($commissions['level3']) ? floatval($commissions['level3']) : 0));
                    } else {
                        $c3          = iunserializer($og['commission3']);
                        $l3          = $pc->getLevel($m3['openid'], $m3['core_user']);
                        $commission3 += ((isset($c3['level' . $l3['id']]) ? $c3['level' . $l3['id']] : $c3['default']));
                    }
                }
            }
        }
        unset($og);
        $commission = $commission1 + $commission2 + $commission3;
        return $commission;
    }

    public function checkOrderGoods($orderid)
    {
        global $_W;
        $flag    = 0;
        $msg     = '订单中的商品' . '<br/>';
        $uniacid = $_W['uniacid'];
        $sql     =
            'select g.id,g.title,g.status,g.deleted' . ' from ' . tablename('superdesk_shop_goods') . ' g ' .
            ' left join  ' . tablename('superdesk_shop_order_goods') . ' og on g.id=og.goodsid and g.uniacid=og.uniacid' . // TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' where og.orderid=:orderid and og.uniacid=:uniacid';

        $list = pdo_fetchall($sql, array(':uniacid' => $uniacid, ':orderid' => $orderid));
        if (!empty($list)) {
            foreach ($list as $k => $v) {
                if (empty($v['status']) || !empty($v['deleted'])) {
                    $flag = 1;
                    $msg  .= $v['title'] . '<br/>';
                }
            }
            if ($flag == 1) {
                $msg .= '已下架,不能付款!';
            }
        }
        $data         = array();
        $data['flag'] = $flag;
        $data['msg']  = $msg;
        return $data;
    }

    /********************************************************************* v3 start *********************************************************************/

    public function checkpeerpay($orderid)
    {

        return false;
//        global $_W;
//        $sql   = 'SELECT p.*,o.openid FROM ' . tablename('superdesk_shop_order_peerpay') . ' AS p JOIN ' . tablename('superdesk_shop_order') . ' AS o ON p.orderid = o.id WHERE p.orderid = :orderid AND p.uniacid = :uniacid AND (p.status = 0 OR p.status=1) AND o.status >= 0 LIMIT 1';
//        $query = pdo_fetch($sql, array(':orderid' => $orderid, ':uniacid' => $_W['uniacid']));
//        return $query;
    }

    public function peerStatus($param)
    {
        global $_W;

        if (!empty($param['tid'])) {
            $sql = 'SELECT id FROM ' . tablename('superdesk_shop_order_peerpay_payinfo') . ' WHERE tid = :tid';
            $id  = pdo_fetchcolumn($sql, array(':tid' => $param['tid']));

            if ($id) {
                return $id;
            }
        }

        return pdo_insert('superdesk_shop_order_peerpay_payinfo', $param);
    }

    public function getVerifyCardNumByOrderid($orderid)
    {
        global $_W;
        $num = pdo_fetchcolumn(
            'select SUM(og.total)  from ' . tablename('superdesk_shop_order_goods') . ' og ' .
            ' inner join ' . tablename('superdesk_shop_goods') . ' g on og.goodsid = g.id ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' where og.uniacid=:uniacid  and og.orderid =:orderid and g.cardid>0',
            array(
                ':uniacid' => $_W['uniacid'],
                ':orderid' => $orderid
            )
        );

        return $num;
    }


    /**
     * 类型 1 实体物品 2 虚拟物品 3 虚拟物品(卡密) 4 批发 10 话费流量充值 20 充值卡
     *
     * @param $order_id
     *
     * @return bool
     */
    public function checkisonlyverifygoods($order_id)
    {
        global $_W;

        $num = pdo_fetchcolumn(
            ' select COUNT(1) ' .
            ' from ' . tablename('superdesk_shop_order_goods') . 'og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            '       inner join ' . tablename('superdesk_shop_goods') . ' g on og.goodsid = g.id ' .
            ' where og.uniacid = :uniacid ' .
            '       and og.orderid = :orderid ' .
            '       and g.`type` <> 5',
            array(
                ':uniacid' => $_W['uniacid'],
                ':orderid' => $order_id
            )
        );
        $num = intval($num);

        if (0 < $num) {
            return false;
        }

        $num2 = pdo_fetchcolumn(
            ' select COUNT(1)  ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            '       inner join ' . tablename('superdesk_shop_goods') . ' g on og.goodsid = g.id ' .
            ' where og.uniacid = :uniacid  ' .
            '       and og.orderid = :orderid ' .
            '       and g.`type` = 5 ',
            array(
                ':uniacid' => $_W['uniacid'],
                ':orderid' => $order_id
            )
        );
        $num2 = intval($num2);

        if (0 < $num2) {
            return true;
        }

        return false;
    }

    public function checkhaveverifygoods($orderid)
    {
        global $_W;
        $num = pdo_fetchcolumn(
            'select COUNT(1)  from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' inner join ' . tablename('superdesk_shop_goods') . ' g on og.goodsid = g.id ' .
            ' where og.uniacid=:uniacid  and og.orderid =:orderid and g.type=5',
            array(
                ':uniacid' => $_W['uniacid'],
                ':orderid' => $orderid
            )
        );

        $num = intval($num);

        if (0 < $num) {
            return true;
        }

        return false;
    }

    public function checkhaveverifygoodlog($orderid)
    {
        global $_W;
        $num = pdo_fetchcolumn('select COUNT(1)  from ' . tablename('superdesk_shop_verifygoods_log') . " vl\r\n\t\t inner join " . tablename('superdesk_shop_verifygoods') . " v on vl.verifygoodsid = v.id\r\n\t\t where v.uniacid=:uniacid  and v.orderid =:orderid ", array(':uniacid' => $_W['uniacid'], ':orderid' => $orderid));
        $num = intval($num);

        if (0 < $num) {
            return true;
        }

        return false;
    }

    public function countOrdersn($ordersn, $str = 'TR')
    {
        global $_W;
        $count = intval(substr_count($ordersn, $str));
        return $count;
    }

    /**
     * 获取订单的虚拟卡密信息
     *
     * @param array $order
     *
     * @return bool
     */
    public function getOrderVirtual($order = array())
    {
        global $_W;

        if (empty($order)) {
            return false;
        }

        if (empty($order['virtual_info'])) {
            return $order['virtual_str'];
        }

        $ordervirtual = array();
        $virtual_type = pdo_fetch('select fields from ' . tablename('superdesk_shop_virtual_type') . ' where id=:id and uniacid=:uniacid and merchid = :merchid limit 1 ', array(':id' => $order['virtual'], ':uniacid' => $_W['uniacid'], ':merchid' => $order['merchid']));

        if (!empty($virtual_type)) {
            $virtual_type = iunserializer($virtual_type['fields']);
            $virtual_info = ltrim($order['virtual_info'], '[');
            $virtual_info = rtrim($virtual_info, ']');
            $virtual_info = explode(',', $virtual_info);

            if (!empty($virtual_info)) {
                foreach ($virtual_info as $k => $v) {
                    $virtual_temp   = iunserializer($v);
                    $vall           = array_values($virtual_temp);
                    $keyl           = array_keys($virtual_temp);
                    $ordervirtual[] = array('key' => $virtual_type[$keyl[0]], 'value' => $vall[0], 'field' => $k);
                }

                unset($k);
                unset($v);
            }
        }

        return $ordervirtual;
    }

    /********************************************************************* v3 end *********************************************************************/
}

