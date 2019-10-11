<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');

class Transfer_member_SuperdeskShopV2Page extends MobilePage
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

    /**
     * 推送详情,显示订单信息,选择审核
     * http://localhost/superdesk/app/index.php?i=16&c=entry&m=superdesk_shopv2&do=mobile&r=transfer_member&userMobile=13510109273&orderid=424
     */
    public function main()
    {
        global $_GPC, $_W;

        $id = $_GPC['id'];

        $order = pdo_fetch(
            ' SELECT o.createtime,o.ordersn,o.price,o.openid,o.core_user,o.status,o.paytype,o.refundstate, ' .
            '        o.isparent,o.remark,o.address,o.addressid,o.invoice,o.invoiceid, ' .
            '        tm.id,tm.orderid,tm.status as tmstatus ' .
            ' FROM ' . tablename('superdesk_shop_order_transfer_member') . ' tm ' .
            ' LEFT JOIN ' . tablename('superdesk_shop_order') . ' o on o.id = tm.orderid' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' WHERE ' .
            '       tm.id = :id ' .
            '       and tm.uniacid=:uniacid',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($order)) {
            $this->message('无订单');
            exit();
        }

        $address = false;
        if (!empty($order['addressid'])) {

            $address = iunserializer($order['address']);

            if (!is_array($address)) {
                $address = pdo_fetch(
                    ' select * ' .
                    ' from  ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
                    ' where ' .
                    '       id=:id ' .
                    ' limit 1',
                    array(
                        ':id' => $order['addressid']
                    )
                );
            }
        }

        // 发票信息 start
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

        if (empty($order['invoiceid'])) {
            $user_invoice = array(
                'companyName'     => '不开发票',
                'taxpayersIDcode' => '',
            );

        } else {
            $user_invoice = iunserializer($order['invoice']);
            if (!is_array($user_invoice)) {
                $user_invoice = pdo_fetch(
                    ' SELECT * ' .
                    ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 不处理
                    ' WHERE id = :id ' .
                    '       and uniacid=:uniacid',
                    array(
                        ':id'      => $order['invoiceid'],
                        ':uniacid' => $_W['uniacid']
                    )
                );
            }
        }

        $orderid = $order['orderid'];
        $where   = ' og.orderid=' . $orderid . ' or og.parent_order_id=' . $orderid;

        $order_child_list = array();

        if ($order['isparent'] == 1) {

            $order_child_list = pdo_fetchall(
                ' SELECT id,ordersn ' .
                ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
                ' WHERE ' .
                '       parentid=:id ' .
                '       and uniacid=:uniacid',
                array(
                    ':id'      => $orderid,
                    ':uniacid' => $_W['uniacid']
                )
            );

            $id_arr = array_column($order_child_list, 'id');

            $where = ' ( og.orderid in (' . implode(",", $id_arr) . ') or og.parent_order_id in (' . implode(",", $id_arr) . ')) ';
        }

        $goods = pdo_fetchall(
            ' select ' .
            '       og.goodsid,og.price,og.total,g.credit,og.optionid,og.optionname as optiontitle,og.orderid,og.parent_order_id, ' .
            '       g.title,g.thumb,g.status, g.cannotrefund,g.isverify,g.storeids' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where og.uniacid=:uniacid AND ' .
            $where,
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        include $this->template();

        exit();
    }


    /**
     * 订单转介状态改变(通过|驳回)
     * 目前用于代客下单.当然其他情况也能用..
     */
    public function transferChange()
    {
        global $_GPC, $_W;

        $openid    = $_W['openid'];
        $core_user = $_W['core_user'];

        $id     = $_GPC['id'];
        $status = $_GPC['status'];

        $transfer = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_order_transfer_member') .
            ' where id=:id',
            array(
                ':id' => $id
            )
        );

        if (empty($transfer)) {
            show_json(0, '转介申请不存在');
        }

        if ($transfer['status'] != 0) {
            show_json(0, '订单已确认');
        }

        if ($transfer['new_openid'] != $openid) {
            show_json(0, '该订单不是转给你的');
        }

        pdo_update(
            'superdesk_shop_order_transfer_member',
            array(
                'status'     => $status,
                'updatetime' => time()
            ),
            array(
                'id' => $id
            )
        );

        // TODO 未完成 推送 暂时屏蔽免得报错
        if ($status == 2) {
            // 审核不通过
//            m('notice')->sendExamineResultNotice(
//                $examine['openid'],
//                2/* reject */,
//                $member['realname'],
//                $examine['price'],
//                $examine['createtime'],
//                $examine['mobile'],
//                $orderid
//            );
        } else {
            // 审核通过
            $update_data = array(
                'openid'                 => $transfer['new_openid'],
                'member_enterprise_id'   => $transfer['new_enterprise_id'],
                'member_enterprise_name' => $transfer['new_enterprise_name'],
                'member_organization_id' => $transfer['new_organization_id'],
                'address'                => $transfer['new_address'],
                'addressid'              => $transfer['new_address_id'],
                'invoice'                => $transfer['new_invoice'],
                'invoiceid'              => $transfer['new_invoice_id']
            );
            //mark kafka 为了kafka转成了model执行
            $this->_orderModel->updateByColumn(
                $update_data,
                array(
                    'id' => $transfer['orderid']
                )
            );

            //mark kafka 为了kafka转成了model执行
            $this->_order_goodsModel->updateByColumn(
                array(
                    'openid' => $transfer['new_openid']
                ),
                array(
                    'orderid' => $transfer['orderid']
                )
            );

//            m('notice')->sendExamineResultNotice(
//                $examine['openid'],
//                1/* approve*/,
//                $member['realname'],
//                $examine['price'],
//                $examine['createtime'],
//                $examine['mobile'],
//                $orderid
//            );
        }

        show_json(1, '审核成功');
    }
}