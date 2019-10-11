<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

error_reporting(0);

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');

class Op_SuperdeskShopV2Page extends PcMobileLoginPage
{
    private $_orderModel;

    public function __construct()
    {

        global $_W;
        global $_GPC;
        parent::__construct();

        $this->_orderModel = new orderModel();
    }

    /**
     * 取消订单
     *
     * @global type $_W
     * @global type $_GPC
     */
    public function cancel()
    {
        global $_W;
        global $_GPC;
        $orderid = intval($_GPC['id']);

//        url:./index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=order.op.cancel
//        type:post
//        data:{"id":71,"remark":"我不想买了"}


        $order = pdo_fetch(
            ' select id,ordersn,openid,status,deductcredit,deductcredit2,deductprice,couponid,isparent ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':id'        => $orderid,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user']
            )
        );

        if (empty($order)) {
            show_json(0, '订单未找到');
        }


        if (0 < $order['status']) {
            show_json(0, '订单已支付，不能取消!');
        }


        if ($order['status'] < 0) {
            show_json(0, '订单已经取消!');
        }


        m('order')->setStocksAndCredits($orderid, 2);

        if (0 < $order['deductprice']) {
            m('member')->setCredit($order['openid'], $order['core_user'],
                'credit1',
                $order['deductcredit'],
                array('0', $_W['shopset']['shop']['name'] . '购物返还抵扣积分 积分: ' . $order['deductcredit'] . ' 抵扣金额: ' . $order['deductprice'] . ' 订单号: ' . $order['ordersn']));
        }


        m('order')->setDeductCredit2($order);
        if (com('coupon') && !empty($order['couponid'])) {
            com('coupon')->returnConsumeCoupon($orderid);
        }

        $this->_orderModel->updateByColumn(
            array(
                'status'      => -1,
                'canceltime'  => time(),
                'closereason' => trim($_GPC['remark'])
            ),
            array(
                'id'      => $order['id'],
                'uniacid' => $_W['uniacid']
            )
        );


        //2018年12月28日 12:31:55 zjh
        // 发现了.由merchshow 引起的.
        // 未付款时已经拆了单.但是因为merchshow的问题.前端显示的还是主单.提交取消的时候也只取消了主单.而后台显示的是子单.并没有显示被子取消
        // 因为只是内部拆分单的问题.所以判断一下该单是不是内部拆分的主单.
        if ($order['isparent'] == 1) {

            $this->_orderModel->updateByColumn(
                array(
                    'status'      => -1,
                    'canceltime'  => time(),
                    'closereason' => trim($_GPC['remark'])
                ),
                array(
                    'parentid' => $order['id'],
                    'uniacid'  => $_W['uniacid']
                )
            );
        }

        //m('notice')->sendOrderMessage($orderid);  //推送


        // jd_vop order start

        $jd_vop_order = pdo_fetch(
            ' select id,order_id,thirdOrder,sku,name,' .
            '       province,city,county,town,address,zip,' .
            '       phone,mobile,email,remark,invoiceState,invoiceType,selectedInvoiceTitle,companyName,invoiceContent,paymentType,isUseBalance,submitState,invoiceName,invoicePhone,invoiceProvice,invoiceCity,invoiceCounty,invoiceAddress,doOrderPriceMode,orderPriceSnap,reservingDate,installDate,needInstall,promiseDate,promiseTimeRange,promiseTimeRangeCode,createtime,updatetime,response,' .
            '       jd_vop_success,jd_vop_resultMessage,jd_vop_resultCode,jd_vop_code,jd_vop_result_jdOrderId,jd_vop_result_freight,jd_vop_result_orderPrice,jd_vop_result_orderNakedPrice,jd_vop_result_orderTaxPrice ' .
            ' from ' . tablename('superdesk_jd_vop_order_submit_order') .
            ' where order_id=:order_id ' .
            ' limit 1',
            array(
                ':order_id' => $orderid
            )
        );

//        var_dump($jd_vop_order);

//        echo $jd_vop_order['jd_vop_result_jdOrderId'];

//        if (false) {    //测试阶段.暂时完全屏蔽掉京东的
        if ($jd_vop_order) {

            include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
            $_orderService = new OrderService();

            $result = $_orderService->cancel($jd_vop_order['jd_vop_result_jdOrderId']);

            // TODO 有失败未处理

//            {
//                "success": true,
//                "resultMessage": "取消订单成功",
//                "resultCode": "0002",
//                "result": true,
//                "code": 200
//            }

//            {
//                "success": false,
//                "resultMessage": "该订单已经被取消",
//                "resultCode": "3203",
//                "result": false,
//                "code": 200
//            }

//            die(json_encode($result,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

        }

        // jd_vop order end


        show_json(1);
    }

    /**
     * 确认收货
     *
     * @global type $_W
     * @global type $_GPC
     */
    public function finish()
    {
        global $_W;
        global $_GPC;

        $orderid = intval($_GPC['id']);

        $order = pdo_fetch(
            ' select id,status,openid,couponid,refundstate,refundid,merchid ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':id'        => $orderid,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user']
            )
        );

        if (empty($order)) {
            show_json(0, '订单未找到');
        }

        if ($order['status'] != 2) {
            show_json(0, '订单不能确认收货');
        }

        if ((0 < $order['refundstate']) && !empty($order['refundid'])) {

            $change_refund = array();

            $change_refund['status']     = -2;
            $change_refund['refundtime'] = time();

            pdo_update(
                'superdesk_shop_order_refund',
                $change_refund,
                array(
                    'id'      => $order['refundid'],
                    'uniacid' => $_W['uniacid']
                )
            );
        }

        $this->_orderModel->updateByColumn(
            array(
                'status'      => 3,
                'finishtime'  => time(),
                'refundstate' => 0
            ),
            array(
                'id'      => $order['id'],
                'uniacid' => $_W['uniacid']
            )
        );

        m('member')->upgradeLevel($order['openid'], $order['core_user']);
        m('order')->setGiveBalance($orderid, 1);

        if (com('coupon') && !empty($order['couponid'])) {
            com('coupon')->backConsumeCoupon($orderid);
        }

        m('notice')->sendOrderMessage($orderid);
        com_run('printer::sendOrderMessage', $orderid);

        if (p('commission')) {
            p('commission')->checkOrderFinish($orderid);
        }

        show_json(1);
    }

    /**
     * 删除或恢复订单
     *
     * @global type $_W
     * @global type $_GPC
     */
    public function delete()
    {
        global $_W;
        global $_GPC;

        $orderid     = intval($_GPC['id']);
        $userdeleted = intval($_GPC['userdeleted']);

        $order = pdo_fetch(
            ' select id,status,refundstate,refundid ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':id'        => $orderid,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user']
            )
        );

        if (empty($order)) {
            show_json(0, '订单未找到!');
        }

        if ($userdeleted == 0) {
            if ($order['status'] != 3) {
                show_json(0, '无法恢复');
            }

        } else {
            if (($order['status'] != 3) && ($order['status'] != -1)) {
                show_json(0, '无法删除');
            }

            if ((0 < $order['refundstate']) && !empty($order['refundid'])) {

                $change_refund               = array();
                $change_refund['status']     = -2;
                $change_refund['refundtime'] = time();

                pdo_update(
                    'superdesk_shop_order_refund',
                    $change_refund,
                    array(
                        'id'      => $order['refundid'],
                        'uniacid' => $_W['uniacid']
                    )
                );
            }

        }

        $this->_orderModel->updateByColumn(
            array(
                'userdeleted' => $userdeleted,
                'refundstate' => 0
            ),
            array(
                'id'      => $order['id'],
                'uniacid' => $_W['uniacid']
            )
        );

        show_json(1);
    }
}