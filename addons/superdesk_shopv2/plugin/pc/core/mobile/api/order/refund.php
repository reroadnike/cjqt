<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');

/**
 * 订单 退换货
 * Class Refund_SuperdeskShopV2Page
 */
class Refund_SuperdeskShopV2Page extends PcMobileLoginPage
{
    private $_orderModel;


    public function __construct()
    {

        global $_W;
        global $_GPC;
        parent::__construct();

        $this->_orderModel = new orderModel();
    }

    protected function globalData()
    {
        global $_W;
        global $_GPC;

        $uniacid   = $_W['uniacid'];
        $openid    = $_W['openid'];
        $core_user = $_W['core_user'];

        $orderid = intval($_GPC['id']);
        $order   = pdo_fetch(
            ' select ' .
            '       id,status,price,refundid,goodsprice,dispatchprice,deductprice,deductcredit2,finishtime,isverify,`virtual`,refundstate,merchid ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where id=:id and uniacid=:uniacid and openid=:openid and core_user=:core_user limit 1',
            array(
                ':id'        => $orderid,
                ':uniacid'   => $uniacid,
                ':openid'    => $openid,
                ':core_user' => $core_user
            )
        );

        if (empty($order)) {
            show_json(0, '订单未找到');
        }

        $_err = '';

        if ($order['status'] == 0) {

            $_err = '订单未付款，不能申请退款!';

        } else if ($order['status'] == 3) {

            if (!empty($order['virtual']) || ($order['isverify'] == 1)) {

                $_err = '此订单不允许退款!';

            } else if ($order['refundstate'] == 0) {

                $tradeset   = m('common')->getSysset('trade');
                $refunddays = intval($tradeset['refunddays']);

                if (0 < $refunddays) {
                    $days = intval((time() - $order['finishtime']) / 3600 / 24);

                    if ($refunddays < $days) {
                        $_err = '订单完成已超过 ' . $refunddays . ' 天, 无法发起退款申请!';
                    }

                } else {
                    $_err = '订单完成, 无法申请退款!';
                }
            }
        }


        if (!empty($_err)) {
            show_json(0, $_err);
        }


        $order['cannotrefund'] = false;

        if ($order['status'] == 2) {
            $goods = pdo_fetchall(
                ' select og.goodsid, og.price, og.total, og.optionname, g.cannotrefund, g.thumb, g.title ' .
                ' from' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                '   left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
                ' where og.orderid=' . $order['id']);

            if (!empty($goods)) {
                foreach ($goods as $g) {
                    if (!($g['cannotrefund'] == 1)) {
                        continue;
                    }
                    $order['cannotrefund'] = true;
                    break;
                }
            }

        }

        if ($order['cannotrefund']) {
            show_json(0, '此订单不可退换货');
        }

        $order['refundprice'] = $order['price'] + $order['deductcredit2'];

        if (2 <= $order['status']) {
            $order['refundprice'] -= $order['dispatchprice'];
        }

        $order['refundprice'] = round($order['refundprice'], 2);

        return array(
            'uniacid'  => $uniacid,
            'openid'   => $_W['openid'],
            'orderid'  => $orderid,
            'order'    => $order,
            'refundid' => $order['refundid']
        );
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        extract($this->globalData());

        if ($order['status'] == '-1') {
            show_json(0, '请不要重复提交!');
        }


        $refund = false;
        $imgnum = 0;

        if (0 < $order['refundstate']) {

            if (!empty($refundid)) {

                $refund = pdo_fetch(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_order_refund') .
                    ' where id=:id ' .
                    '       and uniacid=:uniacid ' .
//                    '       and orderid=:orderid ' .//2018年9月18日 11:39:45 zjh 可能会是拆分单退款.然后这边就会被影响而无法查找到
                    ' limit 1',
                    array(
                        ':id'      => $refundid,
                        ':uniacid' => $uniacid,
//                        ':orderid' => $orderid    //2018年9月18日 11:39:45 zjh 可能会是拆分单退款.然后这边就会被影响而无法查找到
                    )
                );

                if (!empty($refund['refundaddress'])) {
                    $refund['refundaddress'] = iunserializer($refund['refundaddress']);
                }
            }

            if (!empty($refund['imgs'])) {
                $refund['imgs'] = iunserializer($refund['imgs']);
            }

        }

        if (empty($refund)) {
            $show_price = round($order['refundprice'], 2);
        } else {
            $show_price = round($refund['applyprice'], 2);
        }

        $express_list = m('express')->getExpressList();

        $result = compact('order', 'refund', 'show_price', 'express_list');

        show_json(1, $result);
    }

    public function submit()
    {
        global $_W;
        global $_GPC;

        extract($this->globalData());

        if ($order['status'] == '-1') {
            show_json(0, '订单已经处理完毕!');
        }


        $price = trim($_GPC['price']);
        $rtype = intval($_GPC['rtype']);// 处理方式 1 退货退款 2 换货 0 退款(仅退款不退货)

        if ($rtype != 2) {

            if (empty($price) && ($order['deductprice'] == 0)) {
                show_json(0, '退款金额不能为0元');
            }


            if ($order['refundprice'] < $price) {
                show_json(0, '退款金额不能超过' . $order['refundprice'] . '元');
            }

        }

        $refund = array(
            'uniacid'    => $uniacid,
            'merchid'    => $order['merchid'],
            'applyprice' => $price,
            'rtype'      => $rtype,
            'reason'     => trim($_GPC['reason']),//退款原因 不想要了 | 卖家缺货 | 拍错了/订单信息错误 | 其它
            'content'    => trim($_GPC['content']),//退款说明(选填)
            'imgs'       => iserializer($_GPC['images'])
        );

        if ($refund['rtype'] == 2) {
            $refundstate = 2;
        } else {
            $refundstate = 1;
        }

        if ($order['refundstate'] == 0) {

            $refund['createtime'] = time();
            $refund['orderid']    = $orderid;
            $refund['orderprice'] = $order['refundprice'];
            $refund['refundno']   = m('common')->createNO('order_refund', 'refundno', 'SR');
            pdo_insert('superdesk_shop_order_refund', $refund);
            $refundid = pdo_insertid();

            $this->_orderModel->updateByColumn(
                array(
                    'refundid'    => $refundid,
                    'refundstate' => $refundstate
                ),
                array(
                    'id'      => $orderid,
                    'uniacid' => $uniacid
                )
            );
        } else {

            $this->_orderModel->updateByColumn(
                array(
                    'refundstate' => $refundstate
                ),
                array(
                    'id'      => $orderid,
                    'uniacid' => $uniacid
                )
            );
            pdo_update('superdesk_shop_order_refund', $refund, array('id' => $refundid, 'uniacid' => $uniacid));
        }

        m('notice')->sendOrderMessage($orderid, true);

        show_json(1);
    }


    /**
     * 取消
     */
    public function cancel()
    {
        global $_W;
        global $_GPC;

        extract($this->globalData());

        $change_refund               = array();
        $change_refund['status']     = -2;
        $change_refund['refundtime'] = time();

        pdo_update('superdesk_shop_order_refund', $change_refund, array('id' => $refundid, 'uniacid' => $uniacid));
        //mark kafka 为了kafka转成了model执行
        $this->_orderModel->updateByColumn(
            array(
                'refundstate' => 0
            ),
            array(
                'id'      => $orderid,
                'uniacid' => $uniacid
            )
        );

        show_json(1);
    }

    public function express()
    {
        global $_W;
        global $_GPC;
        extract($this->globalData());

        if (empty($refundid)) {
            show_json(0, '参数错误!');
        }


        if (empty($_GPC['expresssn'])) {
            show_json(0, '请填写快递单号');
        }


        $refund = array(
            'status'     => 4,
            'express'    => trim($_GPC['express']),
            'expresscom' => trim($_GPC['expresscom']),
            'expresssn'  => trim($_GPC['expresssn']),
            'sendtime'   => time()
        );

        pdo_update('superdesk_shop_order_refund', $refund, array('id' => $refundid, 'uniacid' => $uniacid));

        show_json(1);
    }

    public function receive()
    {
        global $_W;
        global $_GPC;

        extract($this->globalData());

        $refundid = intval($_GPC['refundid']);
        $refund   = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_order_refund') .
            ' where id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and orderid=:orderid ' .
            ' limit 1',
            array(
                ':id'      => $refundid,
                ':uniacid' => $uniacid,
                ':orderid' => $orderid
            )
        );

        if (empty($refund)) {
            show_json(0, '换货申请未找到!');
        }

        $time = time();

        $refund_data = array();

        $refund_data['status']     = 1;
        $refund_data['refundtime'] = $time;
        pdo_update('superdesk_shop_order_refund', $refund_data, array('id' => $refundid, 'uniacid' => $uniacid));

        $order_data                = array();
        $order_data['refundstate'] = 0;
        $order_data['status']      = -1;
        $order_data['refundtime']  = $time;

        $this->_orderModel->updateByColumn(
            $order_data,
            array(
                'id'      => $orderid,
                'uniacid' => $uniacid
            )
        );

        show_json(1);
    }

    public function refundexpress()
    {
        global $_W;
        global $_GPC;

        extract($this->globalData());

        $express    = trim($_GPC['express']);
        $expresssn  = trim($_GPC['expresssn']);
        $expresscom = trim($_GPC['expresscom']);

        $expresslist = m('util')->getExpressList($express, $expresssn);

        show_json(1, compact('express', 'expresssn', 'expresscom', 'expresslist'));
    }
}