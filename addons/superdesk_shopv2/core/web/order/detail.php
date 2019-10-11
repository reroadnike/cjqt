<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');

/**
 * Class Detail_SuperdeskShopV2Page
 * 开启多商户以后不在这了
 */
class Detail_SuperdeskShopV2Page extends WebPage
{
    private $_orderModel;

    public function __construct()
    {
        global $_W;
        global $_GPC;

        parent::__construct();

        $this->_orderModel = new orderModel();
    }

    public function main()
    {

        global $_W;
        global $_GPC;

        $id = intval($_GPC['id']);

        $p  = p('commission');

        $item = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE id = :id and uniacid=:uniacid',
            array(
                ':id'      => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        $item['statusvalue']  = $item['status'];
        $item['paytypevalue'] = $item['paytype'];

        $shopset = m('common')->getSysset('shop');

        if (empty($item)) {
            $this->message('抱歉，订单不存在!', referer(), 'error');
        }


        if ($_W['ispost']) {
            //mark kafka 为了kafka转成了model执行
            $this->_orderModel->updateByColumn(
                array(
                    'remark' => trim($_GPC['remark'])
                ),
                array(
                    'id'      => $item['id'],
                    'uniacid' => $_W['uniacid']
                )
            );

            plog('order.op.remarksaler', '订单保存备注  ID: ' . $item['id'] . ' 订单号: ' . $item['ordersn']);

            $this->message('订单备注保存成功！', webUrl('order', array('op' => 'detail', 'id' => $item['id'])), 'success');
        }


        $member = m('member')->getMemberByCoreUser($item['core_user']);
//        $member = m('member')->getMember($item['openid'],$item['core_user']);//出现了换了openid的情况.这个情况还没处理故而先屏蔽

        $dispatch = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_dispatch') .
            ' WHERE ' .
            '       id = :id ' .
            '       and uniacid=:uniacid ' .
            '       and merchid=0',
            array(
                ':id'      => $item['dispatchid'],
                ':uniacid' => $_W['uniacid']
            )
        );


        if (empty($item['addressid'])) {

            $user = unserialize($item['carrier']);

        } else {

            $user = iunserializer($item['address']);

            if (!is_array($user)) {
                $user = pdo_fetch(
                    ' SELECT * ' .
                    ' FROM ' . tablename('superdesk_shop_member_address') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_address 不处理
                    ' WHERE ' .
                    '       id = :id ' .
                    '       and uniacid=:uniacid',
                    array(
                        ':id'      => $item['addressid'],
                        ':uniacid' => $_W['uniacid']
                    )
                );
            }


            $address_info    = $user['address'];
            $user['address'] = $user['province'] . ' ' . $user['city'] . ' ' . $user['area'] . ' ' . $user['town'] . ' ' . $user['address'];

            $item['addressdata'] = array(
                'realname' => $user['realname'],
                'mobile'   => $user['mobile'],
                'address'  => $user['address']
            );
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


        if (empty($item['invoiceid'])) {
            $user_invoice = array(
                'companyName'     => '不开发票',
                'taxpayersIDcode' => '',
            );

        } else {

            $user_invoice = iunserializer($item['invoice']);

            if (!is_array($user_invoice)) {

                $user_invoice = pdo_fetch(
                    ' SELECT * ' .
                    ' FROM ' . tablename('superdesk_shop_member_invoice') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_invoice 不处理
                    ' WHERE id = :id ' .
                    '       and uniacid=:uniacid',
                    array(
                        ':id'      => $item['invoiceid'],
                        ':uniacid' => $_W['uniacid']
                    )
                );

                if (!$user_invoice) {

                }
            }
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


//            $invoice_info = $user_invoice['invoice'];


//            echo json_encode($user_invoice);
            $user_invoice['invoice'] = $user_invoice['companyName'] . ' '
                . $user_invoice['taxpayersIDcode'] . ' ['
                . $view_invoiceType[$user_invoice['invoiceType']] . '] ['
                . $view_selectedInvoiceTitle[$user_invoice['selectedInvoiceTitle']] . '] ['
                . $view_invoiceContent[$user_invoice['invoiceContent']] . ']';

//            $item['invoice_data'] = array(
//                'realname' => $user_address['realname'],
//                'mobile'   => $user_address['mobile'],
//                'address'  => $user_address['address']
//            );
        }

        // 发票信息 end

        $refund = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_order_refund') .
            ' WHERE orderid = :orderid ' .
            '       and uniacid=:uniacid ' .
            ' order by id desc',
            array(
                ':orderid' => $item['id'],
                ':uniacid' => $_W['uniacid']
            )
        );

        $diyformfields = '';

        if (p('diyform')) {
            $diyformfields = ',o.diyformfields,o.diyformdata';
        }


        $goods = pdo_fetchall(
            ' SELECT ' .
            '       g.*, ' .
            '       o.goodssn as option_goodssn, o.productsn as option_productsn,o.total,' .
            '       g.type,' .
            '       o.optionname,o.optionid,o.price as orderprice,o.realprice,o.changeprice,o.oldprice,' .
            '       o.commission1,o.commission2,o.commission3,o.commissions,o.return_goods_nun' .
            $diyformfields .
            ' FROM ' . tablename('superdesk_shop_order_goods') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            '   left join ' . tablename('superdesk_shop_goods') . ' g on o.goodsid=g.id ' .
            ' WHERE o.orderid=:orderid ' .
            '       and o.uniacid=:uniacid',
            array(
                ':orderid' => $id,
                ':uniacid' => $_W['uniacid']
            )
        );

        $is_merch = false;

        foreach ($goods as &$r) {
            if (!empty($r['option_goodssn'])) {
                $r['goodssn'] = $r['option_goodssn'];
            }


            if (!empty($r['option_productsn'])) {
                $r['productsn'] = $r['option_productsn'];
            }


            $r['marketprice'] = $r['orderprice'] / $r['total'];

            if (p('diyform')) {
                $r['diyformfields'] = iunserializer($r['diyformfields']);
                $r['diyformdata']   = iunserializer($r['diyformdata']);
            }


            if (!empty($r['merchid'])) {
                $is_merch = true;
            }

        }

        unset($r);

        $item['goods'] = $goods;

        $agents = array();

        if ($p) {

            $agents = $p->getAgents($id);

            $m1 = ((isset($agents[0]) ? $agents[0] : false));
            $m2 = ((isset($agents[1]) ? $agents[1] : false));
            $m3 = ((isset($agents[2]) ? $agents[2] : false));

            $commission1 = 0;
            $commission2 = 0;
            $commission3 = 0;

            foreach ($goods as &$og) {

                $oc1 = 0;
                $oc2 = 0;
                $oc3 = 0;

                $commissions = iunserializer($og['commissions']);

                if (!empty($m1)) {
                    if (is_array($commissions)) {
                        $oc1 = ((isset($commissions['level1']) ? floatval($commissions['level1']) : 0));
                    } else {
                        $c1  = iunserializer($og['commission1']);
                        $l1  = $p->getLevel($m1['openid'], $m1['core_user']);
                        $oc1 = ((isset($c1['level' . $l1['id']]) ? $c1['level' . $l1['id']] : $c1['default']));
                    }

                    $og['oc1']   = $oc1;
                    $commission1 += $oc1;
                }


                if (!empty($m2)) {
                    if (is_array($commissions)) {
                        $oc2 = ((isset($commissions['level2']) ? floatval($commissions['level2']) : 0));
                    } else {
                        $c2  = iunserializer($og['commission2']);
                        $l2  = $p->getLevel($m2['openid'], $m2['core_user']);
                        $oc2 = ((isset($c2['level' . $l2['id']]) ? $c2['level' . $l2['id']] : $c2['default']));
                    }

                    $og['oc2']   = $oc2;
                    $commission2 += $oc2;
                }


                if (!empty($m3)) {
                    if (is_array($commissions)) {
                        $oc3 = ((isset($commissions['level3']) ? floatval($commissions['level3']) : 0));
                    } else {
                        $c3  = iunserializer($og['commission3']);
                        $l3  = $p->getLevel($m3['openid'], $m3['core_user']);
                        $oc3 = ((isset($c3['level' . $l3['id']]) ? $c3['level' . $l3['id']] : $c3['default']));
                    }

                    $og['oc3']   = $oc3;
                    $commission3 += $oc3;
                }

            }

            unset($og);
            $commission_array = array($commission1, $commission2, $commission3);

            foreach ($agents as $key => $value) {
                $agents[$key]['commission'] = $commission_array[$key];

                if (2 < $key) {
                    unset($agents[$key]);
                }

            }
        }


        $condition =
            ' o.uniacid=:uniacid '.
            ' and o.deleted=0 ';

        $paras     = array(
            ':uniacid' => $_W['uniacid']
        );

        $totals = array();
        $coupon = false;

        if (com('coupon') && !empty($item['couponid'])) {
            $coupon = com('coupon')->getCouponByDataID($item['couponid']);
        }

        $order_fields = false;
        $order_data   = false;

        if (p('diyform')) {
            $diyform_set = p('diyform')->getSet();

            foreach ($goods as $g) {
                if (empty($g['diyformdata'])) {
                    continue;
                }
                break;
            }

            if (!empty($item['diyformid'])) {
                $orderdiyformid = $item['diyformid'];

                if (!empty($orderdiyformid)) {
                    $order_fields = iunserializer($item['diyformfields']);
                    $order_data   = iunserializer($item['diyformdata']);
                }

            }

        }


        if (com('verify')) {

            $verifyinfo = iunserializer($item['verifyinfo']);

            if (!empty($item['verifyopenid'])) {

                $saler = m('member')->getMember($item['verifyopenid'],$item['verifycore_user']);

                $saler['salername'] = pdo_fetchcolumn(
                    ' select salername ' .
                    ' from ' . tablename('superdesk_shop_saler') .
                    ' where openid=:openid ' .
                    '       and uniacid=:uniacid ' .
                    ' limit 1 ',
                    array(
                        ':uniacid' => $_W['uniacid'],
                        ':openid'  => $item['verifyopenid']
                    )
                );
            }


            if (!empty($item['verifystoreid'])) {
                $store = pdo_fetch(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_store') .
                    ' where id=:storeid ' .
                    ' limit 1 ',
                    array(
                        ':storeid' => $item['verifystoreid']
                    )
                );
            }


            if ($item['isverify']) {
                if (is_array($verifyinfo)) {
                    if (empty($item['dispatchtype'])) {
                        foreach ($verifyinfo as &$v) {
                            if ($v['verified'] || ($item['verifytype'] == 1)) {

                                $v['storename'] = pdo_fetchcolumn(
                                    ' select storename ' .
                                    ' from ' . tablename('superdesk_shop_store') .
                                    ' where id=:id ' .
                                    ' limit 1',
                                    array(
                                        ':id' => $v['verifystoreid']
                                    )
                                );

                                if (empty($v['storename'])) {
                                    $v['storename'] = '总店';
                                }


                                $v['nickname'] = pdo_fetchcolumn(
                                    ' select nickname ' .
                                    ' from ' . tablename('superdesk_shop_member') . // TODO 标志 楼宇之窗 openid shop_member 待处理
                                    ' where openid=:openid ' .
                                    '       and uniacid=:uniacid ' .
                                    ' limit 1',
                                    array(
                                        ':openid'  => $v['verifyopenid'],
                                        ':uniacid' => $_W['uniacid']
                                    )
                                );

                                $v['salername'] = pdo_fetchcolumn(
                                    ' select salername ' .
                                    ' from ' . tablename('superdesk_shop_saler') .
                                    ' where openid=:openid ' .
                                    '       and uniacid=:uniacid ' .
                                    ' limit 1',
                                    array(
                                        ':openid'  => $v['verifyopenid'],
                                        ':uniacid' => $_W['uniacid']
                                    )
                                );
                            }

                        }

                        unset($v);
                    }

                }

            }

        }


        // jd_vop
        $jd_order_submit_order = false;
        $jd_order_track        = false;

        include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
        $_orderService = new OrderService();

        $jd_order_submit_order = $_orderService->getJDOrderSubmitByShopOrderId($id);

//        socket_log(json_encode($jd_order_submit_order));

        if ($jd_order_submit_order) {
            $jd_order_track = $_orderService->orderTrackByShopOrderId($id);
        }


        // jd_vop

        $balanceDetail = array();
        if ($item['express'] == 'jd') {

            include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/BalanceDetailService.class.php');
            $_BalanceDetailService = new BalanceDetailService();

            $balanceDetail = $_BalanceDetailService->getbalanceDetailByOrderId($item['expresssn']);
        }


        //企业名称,项目名称
        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台
                $enterprise_array = pdo_fetch(
                    ' SELECT core_enterprise.name as enterprise_name,organization.name as organization_name ' .
                    ' FROM ' . tablename('superdesk_core_virtualarchitecture') . ' as core_enterprise ' .
                    ' LEFT JOIN ' . tablename('superdesk_core_organization') . ' as organization on organization.id = core_enterprise.organizationId ' .
                    ' WHERE core_enterprise.id=:core_enterprise',
                    array(':core_enterprise' => $item['member_enterprise_id'])
                );

                break;
            case 2:// 2 福利商城
                $enterprise_array = pdo_fetch(
                    ' SELECT enterprise_name,"" as organization_name ' .
                    ' FROM ' . tablename('superdesk_shop_enterprise_user') .
                    ' WHERE id=:core_enterprise',
                    array(':core_enterprise' => $item['member_enterprise_id'])
                );

                break;
        }


        load()->func('tpl');

        include $this->template();

        exit();
    }
}


