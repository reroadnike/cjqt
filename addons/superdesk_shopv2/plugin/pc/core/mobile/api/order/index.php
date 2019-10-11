<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

class Index_SuperdeskShopV2Page extends PcMobileLoginPage
{
    protected function merchData()
    {
        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }

        return array(
            "is_openmerch" => $is_openmerch,
            'merch_plugin' => $merch_plugin,
            'merch_data'   => $merch_data
        );
    }

    public function main()
    {
        global $_W;
        global $_GPC;

        $trade = m('common')->getSysset('trade');

        $merchdata = $this->merchData();
        extract($merchdata);

        $nav_link_list = array(
            array('link' => mobileUrl('pc'), 'title' => '首页'),
            array('link' => mobileUrl('pc.member'), 'title' => '我的商城'),
            array('title' => '交易订单')
        );

        $ice_menu_array = array(
            array('menu_key' => 'index', 'menu_name' => '订单列表', 'menu_url' => mobileUrl('pc.order')),
            array('menu_key' => 'recycle', 'menu_name' => '回收站', 'menu_url' => mobileUrl('pc.order', array('mk' => 'recycle')))
        );

        $all_list = $this->get_list();
        //$list           = $all_list['list'];

        show_json(1, $all_list);
    }

    /**
     * userdelete = 1 and status=3 or status=-1 显示彻底删除按钮 status=3为已完成 status=-1为已取消
     * userdelete = 1 and status=3 显示恢复按钮
     * userdelete = 0 and status=0 显示取消按钮 status=0为待支付
     * userdelete = 0 and status=0 and paytype!=3 显示支付按钮 paytype!=3 为不是货到付款
     * userdelete = 0 and status!=0 and status!=-1 and canverify=1 and dispatchtype=1 我要取货  dispatchtype!=1 我要使用  dispatchtype为1是自提,否则是线下核销
     * userdelete = 0 and status=3 or status=-1 显示删除按钮
     * userdelete = 0 and status=2 显示确认收货
     * userdelete = 0 and canrefund=1  $order['status']==1 ? 申请退款 : 申请售后 if refundstate>0 中    status=1为已支付
     *
     */
    public function get_list()
    {
        global $_W;
        global $_GPC;

        $uniacid   = $_W['uniacid'];
        $openid    = $_W['openid'];
        $core_user = $_W['core_user'];

        $pindex = max(1, intval($_GPC['page']));
        $psize  = $_GPC['psize'] ? max(1, intval($_GPC['psize'])) : 10;

        $show_status = $_GPC['status'];

        $r_type = array('退货', '换货', '维修', '退货退款');

        $condition =
            ' and uniacid=:uniacid ' .
            ' and openid=:openid ' .
            ' and core_user=:core_user ' .
            ' and ismr=0 ' .
            ' and deleted=0 ';

        $params = array(
            ':uniacid'   => $uniacid,
            ':openid'    => $openid,
            ':core_user' => $core_user
        );

        $merchdata = $this->merchData();
        extract($merchdata);

        $condition .= ' and merchshow=0 ';

        $show_status = intval($show_status);

        switch ($show_status) {
            case -2:
                //待付款且不是货到付款的订单
                $condition .= ' and status=0 and paytype!=3';
                break;
            case 0:
                //所有订单
                if ($_GPC['mk'] == 'recycle') {
                    $condition .= ' ';
                } else {
                    //不是已取消
                    $condition .= ' and status!=-1 ';
                }
                break;
            case 2:
                //待发货或货到付款的
                $condition .= ' and (status=2 or status=0 and paytype=3)';
                break;
            case 4:
                //退换货. 存在退换货状态
                $condition .= ' and refundid>0';
//                $condition .= ' and refundstate>0';
                break;
            case 5:
                //用户删除订单
                $condition .= ' and userdeleted=1 ';
                break;
            default:
                $condition .= ' and status=' . intval($show_status);
        }

        if ($_GPC['mk'] == 'recycle') {
            $condition .= ' and userdeleted=1 ';
        } else if ($show_status != 5) {
            $condition .= ' and userdeleted=0 ';
        }

        $order_sn_search = $_GPC['order_sn'];
        if (!(empty($order_sn_search))) {
            $condition .= ' and ordersn LIKE \'%' . $order_sn_search . '%\' ';
        }

        $query_start_date = $_GPC['start_date'];
        $query_end_date   = $_GPC['end_date'];

        if (!(empty($query_start_date))) {
            $query_start_date = strtotime($query_start_date);
            $condition        .= ' AND createtime >= ' . $query_start_date;
        }

        if (!(empty($query_end_date))) {
            $query_end_date = strtotime($query_end_date);
            $condition      .= ' AND createtime <=  ' . $query_end_date;
        }

        if($_GPC['order_sn'] == 'test'){
            var_dump('select id,addressid,ordersn,price,dispatchprice,status,iscomment,isverify,' .
                '       verified,verifycode,verifytype,iscomment,refundid,expresscom,express,expresssn,finishtime,`virtual`,' .
                '       paytype,expresssn,refundstate,dispatchtype,verifyinfo,merchid,isparent,userdeleted' .
                ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where 1 ' .
                $condition .
                ' order by createtime desc ' .
                ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize);
            var_dump($params);
            die;
        }

        $com_verify = com('verify');
        $list       = pdo_fetchall(
            'select id,addressid,ordersn,price,dispatchprice,status,iscomment,isverify,' .
            '       verified,verifycode,verifytype,iscomment,refundid,expresscom,express,expresssn,finishtime,`virtual`,' .
            '       paytype,expresssn,refundstate,dispatchtype,verifyinfo,merchid,isparent,userdeleted,cancel_status,cancelid' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where 1 ' .
            $condition .
            ' order by createtime desc ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize,
            $params
        );

        $total = pdo_fetchcolumn(
            'select count(1) ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where 1 ' .
            $condition,
            $params
        );

        $refunddays = intval($_W['shopset']['trade']['refunddays']);

        if ($is_openmerch == 1) {

            $merch_user = $merch_plugin->getListUser($list, 'merch_user');
        }

        foreach ($list as &$row) {

            $param = array();

            if ($row['isparent'] == 1) {
                $scondition              = ' og.parentorderid=:parentorderid';
                $param[':parentorderid'] = $row['id'];
            } else {
                $scondition        = ' og.orderid=:orderid';
                $param[':orderid'] = $row['id'];
            }

            $sql   =
                ' SELECT og.goodsid,og.total,g.title,g.thumb,og.price,og.optionname as optiontitle,og.optionid,op.specs,og.realprice ' .
                ' FROM ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                '       left join ' . tablename('superdesk_shop_goods') . ' g on og.goodsid = g.id ' .
                '       left join ' . tablename('superdesk_shop_goods_option') . ' op on og.optionid = op.id ' .
                ' where ' .
                $scondition .
                ' order by og.id asc';
            $goods = pdo_fetchall($sql, $param);

            $goods_total = 0;
            foreach ($goods as &$r) {
                if (!empty($r['specs'])) {
                    $thumb = m('goods')->getSpecThumb($r['specs']);
                    if (!empty($thumb)) {
                        $r['thumb'] = $thumb;
                    }
                }
                $goods_total += $r['total'];
            }

            $row['goods_total'] = $goods_total;
            unset($r);

            $row['goods'] = set_medias($goods, 'thumb');

            foreach ($row['goods'] as &$r) {
                $r['thumb'] .= '?t=' . random(50);
            }

            unset($r);

            $statuscss = 'text-cancel';
            /**
             *-1：已取消，
             * 0： & paytype=3 ：待发货，paytype为3是货到付款
             * 0 ： 待付款
             * 1： &isverify=1 ： 使用中，isverify支持线下核销
             * 1： &addressid为空 待取货，代表自提
             * 1： &addressid不为空也不是线下核销 待发货，走快递
             * 2： 待收货
             * 3：&iscomment=empty showstatus=5 ？ 已完成 ： （closecomment ？ 待评价 ： 已完成。）    showstatus=5为回收站  closecomment为是否关闭评价
             * */
            switch ($row['status']) {
                case '-1':
                    $status = '已取消';
                    break;
                case '0':
                    if ($row['paytype'] == 3) {
                        $status = '待发货';
                    } else {
                        $status = '待付款';
                    }
                    $statuscss = 'text-cancel';
                    break;
                case '1':
                    if ($row['isverify'] == 1) {
                        $status = '使用中';
                    } else if (empty($row['addressid'])) {
                        $status = '待取货';
                    } else {
                        $status = '待发货';
                    }
                    $statuscss = 'text-warning';
                    break;
                case '2':
                    $status    = '待收货';
                    $statuscss = 'text-danger';
                    break;
                case '3':
                    if (empty($row['iscomment'])) {
                        if ($show_status == 5) {
                            $status = '已完成';
                        } else {
                            $status = ((empty($_W['shopset']['trade']['closecomment']) ? '待评价' : '已完成'));
                        }
                    } else {
                        $status = '交易完成';
                    }
                    $statuscss = 'text-success';
                    break;
            }

            $row['statusstr'] = $status;
            $row['statuscss'] = $statuscss;

            //假如退货状态存在并且有退货订单id
            if ((0 < $row['refundstate']) && !(empty($row['refundid']))) {

                $refund = pdo_fetch(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_order_refund') .
                    ' where id=:id ' .
                    '       and uniacid=:uniacid ' .
                    '       and orderid=:orderid ' .
                    ' limit 1',
                    array(
                        ':id'      => $row['refundid'],
                        ':uniacid' => $uniacid,
                        ':orderid' => $row['id']
                    )
                );

                if (!empty($refund)) {
                    $row['statusstr'] = '待' . $r_type[$refund['rtype']];
                }
            }

            $canrefund = false;

            //订单商品退款流程 zjh 2019年7月29日 11:19:44 整改后只有已收货可以申请售后
            //if (($row['status'] == 1) || ($row['status'] == 2)) {
//            $canrefund = true;
//            if (($row['status'] == 2) && ($row['price'] == $row['dispatchprice'])) {
//                if (0 < $row['refundstate']) {
//                    $canrefund = true;
//                } else {
//                    $canrefund = false;
//                }
//            }
//        } else

        if ($row['status'] == 3) {

                if (($row['isverify'] != 1) && empty($row['virtual'])) {

                    if (0 < $row['refundstate']) {

                        $canrefund = true;
                    } else {

                        $tradeset   = m('common')->getSysset('trade');
                        $refunddays = intval($tradeset['refunddays']);

                        if (0 < $refunddays) {
                            $days = intval((time() - $row['finishtime']) / 3600 / 24);
                            if ($days <= $refunddays) {
                                $canrefund = true;
                            }
                        }
                    }
                }
            }
            $row['canrefund'] = $canrefund;

            $row['canverify'] = false;
            $canverify        = false;

            if ($com_verify) {

                $showverify = $row['dispatchtype'] || $row['isverify'];

                if ($row['isverify']) {

                    if (($row['verifytype'] == 0) || ($row['verifytype'] == 1)) {

                        $vs = iunserializer($row['verifyinfo']);

                        $verifyinfo = array(
                            array(
                                'verifycode' => $row['verifycode'],
                                'verified'   => ($row['verifytype'] == 0 ? $row['verified'] : $row['goods'][0]['total'] <= count($vs))
                            )
                        );

                        if ($row['verifytype'] == 0) {

                            $canverify = empty($row['verified']) && $showverify;

                        } else if ($row['verifytype'] == 1) {

                            $canverify = (count($vs) < $row['goods'][0]['total']) && $showverify;

                        }
                    } else {

                        $verifyinfo = iunserializer($row['verifyinfo']);

                        $last = 0;

                        foreach ($verifyinfo as $v) {
                            if (!$v['verified']) {
                                ++$last;
                            }
                        }

                        $canverify = (0 < $last) && $showverify;
                    }
                } else if (!empty($row['dispatchtype'])) {
                    $canverify = ($row['status'] == 1) && $showverify;
                }
            }

            $row['canverify'] = $canverify;
            if ($is_openmerch == 1) {
                $row['merchname'] = ($merch_user[$row['merchid']]['merchname'] ? $merch_user[$row['merchid']]['merchname'] : $_W['shopset']['shop']['name']);
            }
        }

        unset($row);

        return array(
            "list"  => $list,
            'total' => $total,
            'psize' => $psize
        );
    }

    public function detail()
    {
        global $_W;
        global $_GPC;

        $uniacid = $_W['uniacid'];

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);// TODO 标志 m('member')->getMember true

        $orderid = intval($_GPC['id']);

        if (empty($orderid)) {
            show_json(0, '参数异常:' . '订单ID is NULL');
        }


        $order = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':id'        => $orderid,
                ':uniacid'   => $uniacid,
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        if (empty($order)) {
            show_json(0, '未找到订单');
        }

        if ($order['merchshow'] == 1) {
            show_json(0);// 这里是个坑位
        }

        if ($order['userdeleted'] == 2) {
            show_json(0, '订单已经被删除!');
        }

        $merchdata = $this->merchData();
        extract($merchdata);

        $merchid = $order['merchid'];

        $diyform_plugin = p('diyform');

        $diyformfields = '';
        if ($diyform_plugin) {
            $diyformfields = ',og.diyformfields,og.diyformdata';
        }

        $param             = array();
        $param[':uniacid'] = $_W['uniacid'];

        if ($order['isparent'] == 1) {
            $scondition              = ' og.parentorderid=:parentorderid';
            $param[':parentorderid'] = $orderid;
        } else {
            $scondition        = ' og.orderid=:orderid';
            $param[':orderid'] = $orderid;
        }


        //订单商品退款流程.添加了返回字段id,refund_status,rstate zjh 2018年10月9日 10:55:23
        $goods = pdo_fetchall(
            ' select ' .
            '       og.id,og.goodsid,og.price,og.realprice,og.total,og.optionid,og.optionname as optiontitle,og.refund_status, og.rstate, og.refundid, ' .
            '       g.title,g.thumb,g.status, g.cannotrefund,g.credit,g.isverify,g.storeids, ' .
            '       orf.rtype ' . $diyformfields .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' left join ' . tablename('superdesk_shop_order_refund') . ' orf on orf.id=og.refundid ' .
            ' where ' . $scondition .
            '       and og.uniacid=:uniacid ',
            $param
        );

//        socket_log(json_encode($goods,JSON_UNESCAPED_UNICODE));

        $goodsrefund = true;


        if (!empty($goods)) {
            //start 订单商品退款流程.  zjh 2018年10月9日 10:55:23
            $r_type = array('退货', '换货', '维修', '退货退款');
            //end
            foreach ($goods as &$g) {
                if (!empty($g['optionid'])) {
                    $thumb = m('goods')->getOptionThumb($g['goodsid'], $g['optionid']);
                    if (!empty($thumb)) {
                        $g['thumb'] = $thumb;
                    }
                }
                if (!empty($g['cannotrefund']) && ($order['status'] > 2)) {
                    $goodsrefund = false;
                }

                //start订单商品退款流程.  zjh 2018年10月9日 10:55:23
                if ($g['refundid'] > 0) {
                    $g['refund'] = pdo_fetch(
                        ' select * ' .
                        ' from ' . tablename('superdesk_shop_order_refund') .
                        ' where id=:id ' .
                        '       and uniacid=:uniacid ' .
                        ' limit 1',
                        array(
                            ':id'      => $g['refundid'],
                            ':uniacid' => $uniacid
                        )
                    );

                    $g['refund']['createtime'] = date('Y-m-d H:i:s', $g['refund']['createtime']);
                }

                $g['rtype_str'] = $r_type[$g['rtype']];
                //end
            }
            unset($g);
        }

        $diyform_flag = 0;
        if ($diyform_plugin) {
            foreach ($goods as &$g) {
                $g['diyformfields'] = iunserializer($g['diyformfields']);
                $g['diyformdata']   = iunserializer($g['diyformdata']);
                unset($g);
            }
            if (!empty($order['diyformfields']) && !empty($order['diyformdata'])) {
                $order_fields = iunserializer($order['diyformfields']);
                $order_data   = iunserializer($order['diyformdata']);
            }
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

            $user_invoice['invoiceType']          = $view_invoiceType[$user_invoice['invoiceType']];
            $user_invoice['selectedInvoiceTitle'] = $view_selectedInvoiceTitle[$user_invoice['selectedInvoiceTitle']];
            $user_invoice['invoiceContent']       = $view_invoiceContent[$user_invoice['invoiceContent']];
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
        }
        // 发票信息 end


        $carrier = @iunserializer($order['carrier']);

        if (!(is_array($carrier)) || empty($carrier)) {
            $carrier = false;
        }

        $store = false;

        if (!(empty($order['storeid']))) {

            if (0 < $merchid) {

                $store = pdo_fetch(
                    'select * ' .
                    ' from  ' . tablename('superdesk_shop_merch_store') .
                    ' where ' .
                    '       id=:id ' .
                    ' limit 1',
                    array(
                        ':id' => $order['storeid']
                    )
                );
            } else {

                $store = pdo_fetch(
                    ' select * ' .
                    ' from  ' . tablename('superdesk_shop_store') .
                    ' where id=:id ' .
                    ' limit 1',
                    array(
                        ':id' => $order['storeid']
                    )
                );
            }
        }


        $stores     = false;
        $showverify = false;
        $canverify  = false;
        $verifyinfo = false;


        if (com('verify')) {
            $showverify = $order['dispatchtype'] || $order['isverify'];

            if ($order['isverify']) {

                $storeids = array();

                foreach ($goods as $g) {
                    if (!empty($g['storeids'])) {
                        $storeids = array_merge(explode(',', $g['storeids']), $storeids);
                    }
                }

                if (empty($storeids)) {

                    if (0 < $merchid) {

                        $stores = pdo_fetchall(
                            ' select * ' .
                            ' from ' . tablename('superdesk_shop_merch_store') .
                            ' where uniacid=:uniacid ' .
                            '       and merchid=:merchid ' .
                            '       and status=1 ' .
                            '       and type in(2,3)',
                            array(
                                ':uniacid' => $_W['uniacid'],
                                ':merchid' => $merchid
                            )
                        );
                    } else {
                        $stores = pdo_fetchall(
                            ' select * ' .
                            ' from ' . tablename('superdesk_shop_store') .
                            ' where uniacid=:uniacid ' .
                            '       and status=1 ' .
                            '       and type in(2,3)',
                            array(
                                ':uniacid' => $_W['uniacid']
                            )
                        );
                    }

                } else if (0 < $merchid) {
                    $stores = pdo_fetchall(
                        ' select * ' .
                        ' from ' . tablename('superdesk_shop_merch_store') .
                        ' where id in (' . implode(',', $storeids) . ') ' .
                        '       and uniacid=:uniacid ' .
                        '       and merchid=:merchid ' .
                        '       and status=1 ' .
                        '       and type in(2,3)',
                        array(
                            ':uniacid' => $_W['uniacid'],
                            ':merchid' => $merchid
                        )
                    );
                } else {
                    $stores = pdo_fetchall(
                        ' select * ' .
                        ' from ' . tablename('superdesk_shop_store') .
                        ' where id in (' . implode(',', $storeids) . ') ' .
                        '       and uniacid=:uniacid ' .
                        '       and status=1 ' .
                        '       and type in(2,3)',
                        array(
                            ':uniacid' => $_W['uniacid']
                        )
                    );
                }

                if (($order['verifytype'] == 0) || ($order['verifytype'] == 1)) {

                    $vs         = iunserializer($order['verifyinfo']);
                    $verifyinfo = array(
                        array(
                            'verifycode' => $order['verifycode'],
                            'verified'   => ($order['verifytype'] == 0 ? $order['verified'] : $goods[0]['total'] <= count($vs))
                        )
                    );

                    if ($order['verifytype'] == 0) {
                        $canverify = empty($order['verified']) && $showverify;
                    } else if ($order['verifytype'] == 1) {
                        $canverify = (count($vs) < $goods[0]['total']) && $showverify;
                    }
                } else {

                    $verifyinfo = iunserializer($order['verifyinfo']);
                    $last       = 0;

                    foreach ($verifyinfo as $v) {
                        if (!$v['verified']) {
                            ++$last;
                        }
                    }

                    $canverify = (0 < $last) && $showverify;
                }

            } else if (!empty($order['dispatchtype'])) {

                $verifyinfo = array(
                    array(
                        'verifycode' => $order['verifycode'],
                        'verified'   => $order['status'] == 3
                    )
                );

                $canverify = ($order['status'] == 1) && $showverify;
            }
        }

        $order['canverify']   = $canverify;
        $order['showverify']  = $showverify;
        $order['virtual_str'] = str_replace("\n", '<br/>', $order['virtual_str']);

        //订单商品退款流程 zjh 2019年7月29日 11:19:44 整改后只有已收货可以申请售后
//        if (($order['status'] == 1) || ($order['status'] == 2)) {
//
//            $canrefund = true;
//
//            if (($order['status'] == 2) && ($order['price'] == $order['dispatchprice'])) {
//                if (0 < $order['refundstate']) {
//                    $canrefund = true;
//                } else {
//                    $canrefund = false;
//                }
//            }
//
//        } else

        if ($order['status'] == 3) {

            if (($order['isverify'] != 1) && empty($order['virtual'])) {

                if (0 < $order['refundstate']) {

                    $canrefund = true;

                } else {

                    $tradeset = m('common')->getSysset('trade');

                    $refunddays = intval($tradeset['refunddays']);

                    if (0 < $refunddays) {
                        $days = intval((time() - $order['finishtime']) / 3600 / 24);
                        if ($days <= $refunddays) {
                            $canrefund = true;
                        }
                    }
                }
            }
        }

        if (!$goodsrefund && $canrefund) {
            $canrefund = false;
        }

        $order['canrefund'] = $canrefund;

        $express = false;

        if ((2 <= $order['status']) && empty($order['isvirtual']) && empty($order['isverify'])) {

            $expresslist = m('util')->getExpressList($order['express'], $order['expresssn']);

            if (0 < count($expresslist)) {
                $express = $expresslist[0];
            }

        }

        $shopname = $_W['shopset']['shop']['name'];
        if (!empty($order['merchid']) && ($is_openmerch == 1)) {
            $merch_user = $merch_plugin->getListUser($order['merchid']);
            $shopname   = $merch_user['merchname'];
            $shoplogo   = tomedia($merch_user['logo']);
        }

        // jd_vop   京东单..
        $jd_order_submit_order = false;
        $jd_order_track        = false;

        include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
        $_orderService = new OrderService();

        $jd_order_submit_order = $_orderService->getJDOrderSubmitByShopOrderId($orderid);

//        socket_log(json_encode($jd_order_submit_order));

        $jd_vop_result_jdOrderId = 0;
        if ($jd_order_submit_order) {
            //$jd_order_track = $_orderService->orderTrackByShopOrderId($orderid);
            $jd_vop_result_jdOrderId = $jd_order_submit_order['jd_vop_result_jdOrderId'];
        }

        // jd_vop

        $examinestatus = null;

        if ($order['paytype'] == 3) {

            $examinestatus = pdo_fetchcolumn(
                ' select status ' .
                ' from' . tablename('superdesk_shop_order_examine') .// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 不处理
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and orderid = :orderid',
                array(
                    ':uniacid' => $uniacid,
                    ':orderid' => $orderid
                )
            );
        }

        $cancelData = false;
        if($order['cancelid'] > 0){
            $cancelData = pdo_fetch(
                ' SELECT * FROM ' . tablename('superdesk_shop_order_cancel') .
                ' WHERE id=:id ',
                array(
                    ':id' => $order['cancelid']
                )
            );
        }

        $order['deductpricetype']   = $_W['shopset']['trade']['credittext'];
        $order['deductcredit2type'] = $_W['shopset']['trade']['moneytext'];
        $order['createtime']        = date('Y-m-d H:i:s', $order['createtime']);
        $order['paytime']           = date('Y-m-d H:i:s', $order['paytime']);
        $order['sendtime']          = date('Y-m-d H:i:s', $order['sendtime']);
        $order['finishtime']        = date('Y-m-d H:i:s', $order['finishtime']);

        $vs = count($vs);

        $result = compact(
            'order', 'goods', 'address', 'express', 'carrier',
            'store', // store是一条门店记录
            'stores', // stores是一堆门店记录
            'shopname', 'order_fields', 'order_data', 'vs',
            'verifyinfo', 'isverify', 'isvirtual', 'refund', 'user_invoice', 'examinestatus', 'jd_vop_result_jdOrderId', 'jd_order_track', 'cancelData');

        $result = array_merge($result, [
            'moneytext'  => $_W['shopset']['trade']['moneytext'],
            'credittext' => $_W['shopset']['trade']['credittext'],
        ]);

        show_json(1, $result);
    }

    public function express()
    {
        global $_W;
        global $_GPC;

        $openid    = $_W['openid'];
        $core_user = $_W['core_user'];
        $uniacid   = $_W['uniacid'];

        $orderid = intval($_GPC['id']);

        if (empty($orderid)) {
            show_json(0);
        }

        $order = pdo_fetch(
            'select addressid,status,express,expresssn ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':id'        => $orderid,
                ':uniacid'   => $uniacid,
                ':openid'    => $openid,
                ':core_user' => $core_user
            )
        );

        if (empty($order)) {
            show_json(0, '未找到该订单');
        }

        if (empty($order['addressid'])) {
            show_json(0, '订单未选择收货地址，可能不需要快递，无法查看物流信息!');
        }

        if ($order['status'] < 2) {
            show_json(0, '订单未发货，无法查看物流信息!');
        }

        $goods = pdo_fetchall(
            'select og.goodsid,og.price,g.title,g.thumb,og.total,g.credit,og.optionid,og.optionname as optiontitle,g.isverify,g.storeids' .
            $diyformfields .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' . // TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where ' .
            '       og.orderid=:orderid ' .
            '       and og.uniacid=:uniacid ',
            array(
                ':uniacid' => $uniacid,
                ':orderid' => $orderid
            )
        );

        $expresslist = m('util')->getExpressList($order['express'], $order['expresssn']);

        show_json(1, compact('goods', 'expresslist'));
    }

    /**
     * 再次购买接口
     * 2018年11月26日 16:11:34
     * zjh
     * 企业购#1987
     */
    public function againBuyByOrder()
    {
        global $_W, $_GPC;

        $orderid = $_GPC['orderid'];

        if (empty($orderid)) {
            show_json(0, '订单ID不能为空');
        }

        $order_goods = pdo_fetchall(
            ' SELECT ' .
            '       og.goodsid,og.total,g.marketprice,g.merchid ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' as og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
            '       LEFT JOIN ' . tablename('superdesk_shop_goods') . ' as g on g.id = og.goodsid ' .
            ' WHERE ' .
            '       og.orderid=:orderid ' .
            '       AND og.openid=:openid ' .
            '       AND og.core_user=:core_user ' .
            '       AND og.uniacid=:uniacid',
            array(
                ':orderid'   => $orderid,
                ':core_user' => $_W['openid'],
                ':openid'    => $_W['core_user'],
                ':uniacid'   => $_W['uniacid'],
            )
        );

        if (empty($order_goods)) {
            show_json(0, '未找到数据');
        }

        pdo_update(
            'superdesk_shop_member_cart', // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
            array(
                'selected' => 0
            ),
            array(
                'openid'    => $_W['openid'],
                'core_user' => $_W['core_user'],
                'uniacid'   => $_W['uniacid']
            )
        );

        foreach ($order_goods as $v) {
            $cart_goods = pdo_fetch(
                ' SELECT ' .
                '       id ' .
                ' from ' . tablename('superdesk_shop_member_cart') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
                ' WHERE ' .
                '       uniacid=:uniacid ' .
                '       AND openid=:openid ' .
                '       AND core_user=:core_user ' .
                '       AND goodsid=:goodsid ' .
                '       AND deleted=0',
                array(
                    'uniacid'   => $_W['uniacid'],
                    'openid'    => $_W['openid'],
                    'core_user' => $_W['core_user'],
                    'goodsid'   => $v['goodsid']
                )
            );

            $times = time();

            if (!empty($cart_goods)) {

                pdo_update('superdesk_shop_member_cart', // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
                    array('selected' => 1),
                    array(
                        'uniacid'   => $_W['uniacid'],
                        'openid'    => $_W['openid'],
                        'core_user' => $_W['core_user'],
                        'goodsid'   => $v['goodsid']
                    )
                );
            } else {

                pdo_insert('superdesk_shop_member_cart', // TODO 标志 楼宇之窗 openid superdesk_shop_member_cart 已处理
                    array(
                        'uniacid'     => $_W['uniacid'],
                        'openid'      => $_W['openid'],
                        'core_user'   => $_W['core_user'],
                        'goodsid'     => $v['goodsid'],
                        'total'       => $v['total'],
                        'marketprice' => $v['marketprice'],
                        'selected'    => 1,
                        'merchid'     => $v['merchid'],
                        'createtime'  => $times
                    )
                );
            }
        }

        show_json(1);
    }

}