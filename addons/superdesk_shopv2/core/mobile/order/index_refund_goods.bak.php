<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Index_SuperdeskShopV2Page extends MobileLoginPage
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
        return array('is_openmerch' => $is_openmerch, 'merch_plugin' => $merch_plugin, 'merch_data' => $merch_data);
    }

    public function main()
    {
        global $_W;
        global $_GPC;
        $trade     = m('common')->getSysset('trade');
        $merchdata = $this->merchData();
        extract($merchdata);
        if ($is_openmerch == 1) {
            include $this->template('merch/order/index');
            return;
        }
        include $this->template();
    }

    public function get_list()
    {
        global $_W;
        global $_GPC;

        $uniacid = $_W['uniacid'];
        $openid  = $_W['openid'];

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 50;

        $show_status = $_GPC['status'];

        $r_type    = array('退款', '退货退款', '换货');
        $condition = ' and openid=:openid and ismr=0 and deleted=0 and uniacid=:uniacid ';
        $params    = array(':uniacid' => $uniacid, ':openid' => $openid);

        $merchdata = $this->merchData();
        extract($merchdata);
        $condition .= ' and merchshow=0 ';
        if ($show_status == "") {
            $show_status = 6;
        } else {
            $show_status = intval($show_status);
        }


        switch ($show_status) {
            case 0:
                $condition .= ' and status=0 and paytype!=3';
                break;
            case 2:
                $condition .= ' and (status=2 or status=0 and paytype=3)';
                break;
            case 4:
                $condition .= ' and refundstate>0';
                break;
            case 5:
                $condition .= ' and userdeleted=1 ';
                break;
            case 6:
                $condition .= ' and userdeleted=0 ';
                break;
            default:
                $condition .= ' and status=' . intval($show_status);

        }

        if ($show_status != 5) {
            $condition .= ' and userdeleted=0 ';
        }

        $com_verify = com('verify');

        $list = pdo_fetchall(
            'select id,addressid,ordersn,price,dispatchprice,status,iscomment,isverify,' . "\n" .
            '       verified,verifycode,verifytype,iscomment,refundid,expresscom,express,expresssn,finishtime,`virtual`,' . "\n" .
            '       paytype,expresssn,refundstate,dispatchtype,verifyinfo,merchid,isparent,userdeleted' . "\n" .
            ' from ' . tablename('superdesk_shop_order') .
            ' where 1 ' . $condition .
            ' order by createtime desc LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

        $total = pdo_fetchcolumn('select count(*) from ' . tablename('superdesk_shop_order') . ' where 1 ' . $condition, $params);

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
                ' SELECT og.goodsid,og.total,g.title,g.thumb,og.price,og.optionname as optiontitle,og.optionid,op.specs ' .
                ' FROM ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                '       left join ' . tablename('superdesk_shop_goods') . ' g on og.goodsid = g.id ' .
                '       left join ' . tablename('superdesk_shop_goods_option') . ' op on og.optionid = op.id ' .
                ' where ' . $scondition .
                ' order by og.id asc';
            $goods = pdo_fetchall($sql, $param);


            // Fix is not array
            is_array($goods) ? null : $goods = array();


            // for debug
//            if ($row['isparent'] == 1) {
////                show_json(0,$goods);
//            } else {
////                show_json(0,$sql);
//                show_json(0,$param);
//
//            }


            foreach ($goods as &$r) {
                if (!empty($r['specs'])) {
                    $thumb = m('goods')->getSpecThumb($r['specs']);
                    if (!empty($thumb)) {
                        $r['thumb'] = $thumb;
                    }
                }
            }
            unset($r);

            $row['goods'] = set_medias($goods, 'thumb');

            // Fix is not array
            is_array($row['goods']) ? null : $row['goods'] = array();

            foreach ($row['goods'] as &$r) {
                $r['thumb'] .= '?t=' . random(50);
            }

            unset($r);

            $statuscss = 'text-cancel';

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

            if ((0 < $row['refundstate']) && !empty($row['refundid'])) {

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

            $canrefund        = false;
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

        show_json(1, array('list' => $list, 'pagesize' => $psize, 'total' => $total));
    }

    public function alipay()
    {
        global $_W;
        global $_GPC;

        $url = urldecode($_GPC['url']);
        if (!is_weixin()) {
            header('location: ' . $url);
            exit();
        }
        include $this->template();
    }

    public function detail()
    {
        global $_W;
        global $_GPC;


        // jd_vop
        $paytype = array(
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
            array('css' => 'danger', 'name' => '待付款'),
            array('css' => 'info', 'name' => '待发货'),
            array('css' => 'warning', 'name' => '待收货'),
            array('css' => 'success', 'name' => '已完成')
        );
        // jd_vop

        $uniacid = $_W['uniacid'];
        $member  = m('member')->getMember($_W['openid'], $_W['core_user']);


        $orderid = intval($_GPC['id']);
        if (empty($orderid)) {
            header('location: ' . mobileUrl('order'));
            exit();
        }


        $order = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_order') .
            ' where id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and openid=:openid limit 1',
            array(
                ':id'      => $orderid,
                ':uniacid' => $uniacid,
                ':openid'  => $_W['openid']
            )
        );


        if (empty($order)) {
            header('location: ' . mobileUrl('order'));
            exit();
        }
        if ($order['merchshow'] == 1) {
            header('location: ' . mobileUrl('order'));
            exit();
        }
        if ($order['userdeleted'] == 2) {
            $this->message('订单已经被删除!', '', 'error');
        }


        $merchdata = $this->merchData();
        extract($merchdata);

        $merchid        = $order['merchid'];
        $diyform_plugin = p('diyform');
        $diyformfields  = '';

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
            '       og.id,og.goodsid,og.price,g.title,g.thumb,g.status, g.cannotrefund, og.total,g.credit, og.refund_status, og.rstate, og.refundid, orf.rtype, ' .
            '       og.optionid,og.optionname as optiontitle,g.isverify,g.storeids' . $diyformfields .
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
            $r_type = array('退款', '退货退款', '换货');
            //end
            foreach ($goods as &$g) {
                if (!empty($g['optionid'])) {
                    $thumb = m('goods')->getOptionThumb($g['goodsid'], $g['optionid']);
                    if (!empty($thumb)) {
                        $g['thumb'] = $thumb;
                    }
                }
                if (!empty($g['cannotrefund']) && ($order['status'] == 2)) {
                    $goodsrefund = false;
                }

                //start订单商品退款流程.  zjh 2018年10月9日 10:55:23
                if ($g['refundid'] > 0) {
                    $goodsrefund = false;
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
                    ' from  ' . tablename('superdesk_shop_member_address') .
                    ' where id=:id limit 1',
                    array(':id' => $order['addressid']));
            }
        }

        // 发票信息 start
        $view_invoiceType          = array(
            1 => '增值税普票',
            2 => '增值税专票'
        );
        $view_selectedInvoiceTitle = array(
            4 => '个人',
            5 => '单位'
        );
        $view_invoiceContent       = array(
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
                    ' FROM ' . tablename('superdesk_shop_member_invoice') .
                    ' WHERE id = :id ' .
                    '       and uniacid=:uniacid',
                    array(
                        ':id'      => $order['invoiceid'],
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
            $user_invoice['invoice'] = $user_invoice['companyName'] . ' '
                . $user_invoice['taxpayersIDcode'] . ' ['
                . $view_invoiceType[$user_invoice['invoiceType']] . '] ['
                . $view_selectedInvoiceTitle[$user_invoice['selectedInvoiceTitle']] . '] ['
                . $view_invoiceContent[$user_invoice['invoiceContent']] . ']';

        }
        // 发票信息 end


        $carrier = @iunserializer($order['carrier']);
        if (!is_array($carrier) || empty($carrier)) {
            $carrier = false;
        }

        $store = false;
        if (!empty($order['storeid'])) {

            if (0 < $merchid) {

                $store = pdo_fetch(
                    ' select * ' .
                    ' from  ' . tablename('superdesk_shop_merch_store') .
                    ' where id=:id ' .
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


        if (($order['status'] == 1) || ($order['status'] == 2)) {

            $canrefund = true;

            if (($order['status'] == 2) && ($order['price'] == $order['dispatchprice'])) {
                if (0 < $order['refundstate']) {
                    $canrefund = true;
                } else {
                    $canrefund = false;
                }
            }

        } else if ($order['status'] == 3) {

            if (($order['isverify'] != 1) && empty($order['virtual'])) {

                if (0 < $order['refundstate']) {
                    $canrefund = true;
                } else {
                    $tradeset   = m('common')->getSysset('trade');
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

        // jd_vop
        $jd_order_submit_order = false;
        $jd_order_track        = false;

        include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/OrderService.class.php');
        $_orderService = new OrderService();

        $jd_order_submit_order = $_orderService->getJDOrderSubmitByShopOrderId($orderid);

//        socket_log(json_encode($jd_order_submit_order));

        if ($jd_order_submit_order) {
            $jd_order_track = $_orderService->orderTrackByShopOrderId($orderid);
        }

        // jd_vop

        $examinestatus = null;
        if ($order['paytype'] == 3) {
            $examinestatus = pdo_fetchcolumn(
                ' select status ' .
                ' from' . tablename('superdesk_shop_order_examine') .// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 不处理
                ' where uniacid=:uniacid and orderid = :orderid', array(':uniacid' => $uniacid, ':orderid' => $orderid));
        }

        include $this->template();
    }

    public function express()
    {
        global $_W;
        global $_GPC;
        global $_GPC;


        $openid  = $_W['openid'];
        $uniacid = $_W['uniacid'];
        $orderid = intval($_GPC['id']);
        if (empty($orderid)) {
            header('location: ' . mobileUrl('order'));
            exit();
        }

        $order = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_order') .
            ' where id=:id and uniacid=:uniacid and openid=:openid limit 1',
            array(
                ':id'      => $orderid,
                ':uniacid' => $uniacid,
                ':openid'  => $openid
            )
        );

        if (empty($order)) {
            header('location: ' . mobileUrl('order'));
            exit();
        }

        if (empty($order['addressid'])) {
            $this->message('订单非快递单，无法查看物流信息!');
        }

        if ($order['status'] < 2) {
            $this->message('订单未发货，无法查看物流信息!');
        }

        $goods = pdo_fetchall(
            ' select ' .
            '       g.title,g.thumb,g.credit,g.isverify,g.storeids,' .
            '       og.goodsid,og.price,og.total,og.optionid,og.optionname as optiontitle' . $diyformfields .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            '       left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where og.orderid=:orderid ' .
            '       and og.uniacid=:uniacid ',
            array(':uniacid' => $uniacid, ':orderid' => $orderid));

        $expresslist = m('util')->getExpressList($order['express'], $order['expresssn']);

        include $this->template();
    }
}

