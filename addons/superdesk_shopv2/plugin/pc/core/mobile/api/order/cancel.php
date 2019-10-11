<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_cancel.class.php');

class Cancel_SuperdeskShopV2Page extends PcMobileLoginPage
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

    public function main()
    {
        global $_W;
        global $_GPC;
        $orderid = intval($_GPC['id']);

        $order = pdo_fetch(
            ' select ' .
            '       * ' .
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
            show_json(0,'订单未找到!');
        }

        if (2 < $order['status']) {
            show_json(0,'订单已发货,无法取消!');
        }

        if ($order['status'] < 0) {
            show_json(0,'订单已取消!');
        }

        $cancelData = false;
        if ($order['cancelid'] > 0) {
            $cancelData = $this->_order_cancelModel->getOne($order['cancelid']);
        }

        show_json(1,compact('order','cancelData'));
    }

    /**
     * 取消订单
     *
     * 2019年7月9日 16:53:22 zjh 原逻辑为未支付可以取消.现在是未收货前都可以取消
     * 严选的必须在发货之前.发货后只能等收货了再申请售后.
     *
     * @global type $_W
     * @global type $_GPC
     */
    public function submit()
    {
        global $_W;
        global $_GPC;
        $orderid = intval($_GPC['id']);
        $times = time();

//        url:./index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=order.op.cancel
//        type:post
//        data:{"id":71,"remark":"我不想买了"}


        $order = pdo_fetch(
            ' select ' .
            '       * ' .
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

        if (2 < $order['status']) {
            show_json(0, '订单已收货，不能取消!');
        }

        if ($order['status'] < 0) {
            show_json(0, '订单已经取消!');
        }

        if ($order['cancelid'] > 0) {
            show_json(0, '请不要重复提交!');
        }

        $cancelno = m('common')->createNO('order_cancel', 'cancelno', 'CC');

        $cancelInfo = array(
            'uniacid'     => $_W['uniacid'],
            'orderid'     => $order['id'],
            'cancelno'    => $cancelno,
            'reason'       => $_GPC['reason'],
            'content'      => $_GPC['content'],
            'createtime'   => $times,
            'status'        => 1,
            'merchid'       => $order['merchid'],
            'third_status'  => 0,
            'third_message' => '',
            'message'        => ''
        );

        //未付款,待发货
        if(2 > $order['status']){

            $cancelInfo['status'] = 2;

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

            $cancelInfo['canceltime'] = $times;

            $cancelid = $this->_order_cancelModel->insert($cancelInfo);

            //mark kafka 为了kafka转成了model执行
            $this->_orderModel->updateByColumn(
                array(
                    'status'      => -1,
                    'canceltime'  => $times,
                    'closereason' => trim($_GPC['reason']) . ',' . trim($_GPC['content']),
                    'cancel_status' => $cancelInfo['status'],
                    'cancelid' => $cancelid
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

                //把子订单也加入订单取消表
                $child = $this->_orderModel->queryAllByColumn(array('parentid' => $order['id']));
                foreach($child as $k => $v){

                    $childCancelInfo = $cancelInfo;
                    $childCancelInfo['orderid'] = $v['id'];
                    $childCancelInfo['cancelno'] = m('common')->createNO('order_cancel', 'cancelno', 'CC');
                    $childCancelInfo['merchid'] = $v['merchid'];

                    $cancelid = $this->_order_cancelModel->insert($cancelInfo);

                    $this->_orderModel->update(
                        array(
                            'cancelid' => $cancelid
                        ),
                        $v['id']
                    );
                }

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    array(
                        'status'      => -1,
                        'canceltime'  => $times,
                        'closereason' => trim($_GPC['reason']) . ',' . trim($_GPC['content']),
                        'cancel_status' => $cancelInfo['status']
                    ),
                    array(
                        'parentid' => $order['id'],
                        'uniacid'  => $_W['uniacid']
                    )
                );
            }

//            m('notice')->sendOrderMessage($orderid);


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

            if ($jd_vop_order) {

                include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
                $_orderService = new OrderService();

                $result = $_orderService->cancel($jd_vop_order['jd_vop_result_jdOrderId']);

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

        } else {
            //已发货 无视京东和严选.这双方都只能取消未发货

            $cancelid = $this->_order_cancelModel->insert($cancelInfo);

            $orderCancelInfo = array(
                'cancel_status' => $cancelInfo['status'],
                'cancelid' => $cancelid
            );

            $this->_orderModel->update($orderCancelInfo, $order['id']);

        }

        show_json(1);
    }


    /**
     * 撤回取消申请
     */
    public function cancel()
    {
        global $_W;
        global $_GPC;
        $orderid = intval($_GPC['id']);

        $order = pdo_fetch(
            ' select ' .
            '       * ' .
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

        if ($order['status'] < 0) {
            show_json(0, '订单已经取消!');
        }

        if ($order['cancelid'] == 0) {
            show_json(0, '订单未申请取消,无法撤回!');
        }

        $change_cancel               = array();
        $change_cancel['status']     = -1;
        $change_cancel['canceltime'] = time();

        pdo_update(
            'superdesk_shop_order_cancel',
            $change_cancel,
            array(
                'id'      => $order['cancelid'],
                'uniacid' => $_W['uniacid']
            )
        );

        //mark kafka 为了kafka转成了model执行
        $this->_orderModel->updateByColumn(
            array(
                'cancel_status' => -1
            ),
            array(
                'id'      => $orderid,
                'uniacid' => $_W['uniacid']
            )
        );

        show_json(1);
    }
}


