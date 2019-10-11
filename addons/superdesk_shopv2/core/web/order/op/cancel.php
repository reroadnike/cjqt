<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_cancel.class.php');

class Cancel_SuperdeskShopV2Page extends WebPage
{
    private $_orderModel;
    private $_order_cancelModel;


    public function __construct()
    {

        global $_W;
        global $_GPC;
        parent::__construct();

        $this->_orderModel = new orderModel();
        $this->_order_cancelModel = new order_cancelModel();
    }

    protected function opData()
    {
        global $_W;
        global $_GPC;

        $id       = intval($_GPC['id']);

        $item = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE id = :id and uniacid=:uniacid Limit 1',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($item)) {
            if ($_W['isajax']) {
                show_json(0, '未找到订单!');
            }

            $this->message('未找到订单!', '', 'error');
        }

        if (empty($item['cancelid'])) {
            if ($_W['isajax']) {
                show_json(0, '该订单未申请取消!');
            }

            $this->message('该订单未申请取消!', '', 'error');
        }

        $cancelData = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_order_cancel') .
            ' where id=:id limit 1',
            array(
                ':id' => $item['cancelid']
            )
        );

        return array(
            'id'     => $id,
            'item'   => $item,
            'cancelData' => $cancelData
        );
    }

    /**
     * cancel_status
     * -1 撤销
     * 0 未申请取消
     * 1 审核中
     * 2 无需审核
     * 3 同意退款
     * 4 驳回申请
     *
     * 只有已发货的需要审核 并且由于京东和严选都不支持取消已发货.所以忽略告知京东和严选.采用人工方式告知
     */
    public function submit()
    {
        global $_W;
        global $_GPC;
        global $_S;

        $opdata = $this->opData();

        extract($opdata);

        if ($_W['ispost']) {

            if($_GPC['status'] == 3){

                m('order')->setStocksAndCredits($id, 2);

                if (0 < $item['deductprice']) {
                    m('member')->setCredit($item['openid'], $item['core_user'],
                        'credit1',
                        $item['deductcredit'],
                        array('0', $_W['shopset']['shop']['name'] . '购物返还抵扣积分 积分: ' . $item['deductcredit'] . ' 抵扣金额: ' . $item['deductprice'] . ' 订单号: ' . $item['ordersn']));
                }


                m('order')->setDeductCredit2($item);
                if (com('coupon') && !empty($item['couponid'])) {
                    com('coupon')->returnConsumeCoupon($id);
                }

                $this->_order_cancelModel->update(
                    array(
                        'status' => $_GPC['status'],
                        'message' => $_GPC['message'],
                        'canceltime' => time()
                    ),
                    $item['cancelid']
                );

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    array(
                        'status'      => -1,
                        'canceltime'  => time(),
                        'closereason' => $cancelData['reason'] . ',' . $cancelData['content'],
                        'cancel_status' => $_GPC['status']
                    ),
                    array(
                        'id'      => $item['id'],
                        'uniacid' => $_W['uniacid']
                    )
                );

//            m('notice')->sendOrderMessage($id);

            }elseif ($_GPC['status'] == 4) {

                $this->_order_cancelModel->update(
                    array(
                        'status' => $_GPC['status'],
                        'message' => $_GPC['message'],
                        'canceltime' => time()
                    ),
                    $item['cancelid']
                );

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    array(
                        'cancel_status' => $_GPC['status']
                    ),
                    array(
                        'id'      => $item['id'],
                        'uniacid' => $_W['uniacid']
                    )
                );


            }


            show_json(1);
        }

        include $this->template();
    }

    public function main()
    {
        global $_W;
        global $_GPC;
        global $_S;

        $opdata = $this->opData();
        extract($opdata);

        include $this->template();
    }
}