<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');

/**
 * 订单 退换货
 * Class Refund_SuperdeskShopV2Page
 */
class Refund_goods_SuperdeskShopV2Page extends PcMobileLoginPage
{

    private $_order_goodsModel;


    public function __construct()
    {

        global $_W;
        global $_GPC;
        parent::__construct();

        $this->_order_goodsModel = new order_goodsModel();
    }

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
            'is_openmerch' => $is_openmerch,
            'merch_plugin' => $merch_plugin,
            'merch_data'   => $merch_data
        );
    }

    /*
     *
     * 售后列表跳转
     *
     * */
    public function main()
    {
        global $_W;
        global $_GPC;

        $trade     = m('common')->getSysset('trade');
        $merchdata = $this->merchData();

        extract($merchdata);

        if ($is_openmerch == 1) {
            include $this->template('merch/order/refund_goods/index');
            return;
        }

        include $this->template();
    }

    /*
     *
     * 售后列表获取
     *
     */
    public function get_list()
    {
        global $_W;
        global $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 50;

        $r_type = array('退货', '换货', '维修', '退货退款');

        $status_array = array(
            0 => '等待商家处理售后申请',
            3 => '需填写快递单号',
            4 => '等待商家确认',
            5 => '商家已经发货',

        );

        $condition =
            ' and o.openid=:openid ' .
            ' and o.core_user=:core_user ' .
            ' and o.ismr=0 ' .
            ' and o.deleted=0 ' .
            ' and o.uniacid=:uniacid ' .
            ' and o.userdeleted=0 ' .
            ' and og.rstate>0 ';

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user']
        );

        $merchdata = $this->merchData();
        extract($merchdata);
        $condition .= ' and o.merchshow=0 ';

        $list = pdo_fetchall(
            ' select og.id as order_goods_id,og.goodsid,og.total,og.realprice,og.optionname as optiontitle,og.optionid,og.orderid,og.rstate, ' .
            '        g.title,g.thumb, ' .
            '        gop.specs, ' .
            '        o.status,o.paytype,o.isverify,o.addressid,o.iscomment,o.merchid,o.ordersn, ' .
            '        orf.refund_total,orf.rtype ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' as og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
            ' left join ' . tablename('superdesk_shop_goods') . 'as g on g.id=og.goodsid ' .
            ' left join ' . tablename('superdesk_shop_goods_option') . ' gop on og.optionid = gop.id ' .
            ' left join ' . tablename('superdesk_shop_order') . ' o on o.id = og.orderid ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' left join ' . tablename('superdesk_shop_order_refund') . ' orf on orf.id = og.refundid ' .
            ' where 1 ' . $condition .
            ' order by orf.createtime desc ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize,
            $params
        );

        $total = pdo_fetchcolumn(
            ' select count(*) ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' as og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
            ' left join ' . tablename('superdesk_shop_order') . ' o on o.id = og.orderid ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where 1 ' .
            $condition,
            $params
        );

        if ($is_openmerch == 1) {
            $merch_user = $merch_plugin->getListUser($list, 'merch_user');
        }

        foreach ($list as &$row) {
            if (!empty($row['specs'])) {
                $thumb = m('goods')->getSpecThumb($row['specs']);
                if (!empty($thumb)) {
                    $row['thumb'] = $thumb;
                }
            }

            $row['thumb'] .= '?t=' . random(50);

            $row['statuscss'] = 'text-cancel';
            $row['statusstr'] = '待' . $r_type[$row['rtype']];

            if ($is_openmerch == 1) {
                $row['merchname'] = ($merch_user[$row['merchid']]['merchname'] ? $merch_user[$row['merchid']]['merchname'] : $_W['shopset']['shop']['name']);
            }
        }

        $list = set_medias($list, 'thumb');

        unset($row);

        show_json(1, array(
            'list'     => $list,
            'pagesize' => $psize,
            'total'    => $total
        ));
    }

    /*
     * 售后详情 从列表过去
     */
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

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        $orderid = intval($_GPC['id']);
        if (empty($orderid)) {
            show_json(0,'请求参数不足');
        }

        $order = pdo_fetch(
            ' select * ' .
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
            show_json(0,'订单未找到');
        }

        if ($order['merchshow'] == 1) {
            show_json(0,'订单数据异常');
        }

        if ($order['userdeleted'] == 2) {
            show_json(0,'订单已经被删除!');
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


        $goods = pdo_fetchall(
            ' select ' .
            '       og.goodsid,og.price,g.title,g.thumb,g.status, g.cannotrefund, og.total,g.credit,' .
            '       og.optionid,og.optionname as optiontitle,g.isverify,g.storeids' .
            $diyformfields .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where ' .
            $scondition .
            '       and og.uniacid=:uniacid ',
            $param
        );

//        socket_log(json_encode($goods,JSON_UNESCAPED_UNICODE));

        $goodsrefund = true;


        if (!empty($goods)) {
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

            $expresslist = m('util')->getExpressList($order['express'], $order['expresssn'], $order['yxPackageId']);

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
                ' where uniacid=:uniacid and orderid = :orderid', array(':uniacid' => $_W['uniacid'], ':orderid' => $orderid));
        }


        $result = compact(
            'order', 'goods', 'address', 'express', 'carrier',
            'store', // store是一条门店记录
            'stores', // stores是一堆门店记录
            'shopname', 'order_fields', 'order_data', 'vs',
            'verifyinfo', 'isverify', 'isvirtual', 'refund', 'user_invoice', 'examinestatus', 'jd_vop_result_jdOrderId', 'jd_order_track');

        $result = array_merge($result, [
            'moneytext'  => $_W['shopset']['trade']['moneytext'],
            'credittext' => $_W['shopset']['trade']['credittext'],
        ]);

        show_json(1, $result);
    }

    protected function globalData()
    {
        global $_W;
        global $_GPC;

        $orderid = intval($_GPC['orderid']);
        $order   = pdo_fetch(
            ' select ' .
            '       id,status,price,refundid,goodsprice,dispatchprice,deductprice,deductcredit2,finishtime,isverify,`virtual`,refundstate,merchid,paytype ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '   id = :id ' .
            '   and uniacid = :uniacid ' .
            '   and openid = :openid ' .
            '   and core_user = :core_user ' .
            ' limit 1',
            array(
                ':id'        => $orderid,
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
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

            }
        }

        if (!empty($_err)) {
            show_json(0, $_err);
        }

        $order_goods_id = intval($_GPC['order_goods_id']);
        $order_goods    = pdo_fetch(
            ' select og.goodsid, og.price, og.total, og.optionname, og.return_goods_nun, og.rstate, og.realprice, og.refundid, og.refund_status, ' .
            '        g.cannotrefund, g.thumb, g.title ' .
            ' from' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            '   left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' where og.id=:order_goods_id and og.orderid=:orderid',
            array(
                ':order_goods_id' => $order_goods_id,
                ':orderid'        => $orderid
            )
        );

        if (empty($order_goods)) {
            show_json(0, '订单商品不存在');
        }

        if ($order_goods['cannotrefund'] == 1) {
            show_json(0, '该订单商品不允许退货');
        }

        if ($order['status'] == 3) {

            if ($order_goods['rstate'] == 0) {

                $tradeset   = m('common')->getSysset('trade');
                $refunddays = intval($tradeset['refunddays']);

                if (0 < $refunddays) {

                    $days = intval((time() - $order['finishtime']) / 3600 / 24);

                    if ($refunddays < $days) {
                        show_json(0, '订单完成已超过 ' . $refunddays . ' 天, 无法发起退款申请!');
                    }

                } else {
                    show_json(0, '订单完成, 无法申请退款!');
                }
            }

        }

        //TODO 这里运费退不退还要确定一下
        //假如退的话,要怎么计算.
        //假如判断有没有退款完成的,可能他同时多单未完成退款的,那就会重复退
        //假如判断有没有退款,那么同时申请多单.只有第一单有算运费,假如第一单被拒绝了,后面成了也一样没运费退
        //假如只退部分商品的话,那不应该退运费.
        //想法:判断一下是不是把订单里的商品都退了,然后只有最后一条退款单才有退运费
        //以上也包括了其他费用..如余额抵扣之类的..
        //原先的做法就是假如已发货就不退运费,假如没发货就退运费

        //暂时以没有任何优惠的情况处理 故而屏蔽
//        $order_goods['refundprice'] = $order_goods['realprice'] + $order['deductcredit2'];
        $order_goods['refundprice'] = $order_goods['realprice'];

        $order_goods['refundprice'] = round($order_goods['refundprice'], 2);

        return array(
            'uniacid'        => $_W['uniacid'],
            'openid'         => $_W['openid'],
            'core_user'      => $_W['core_user'],
            'orderid'        => $orderid,
            'order'          => $order,
            'refundid'       => $order_goods['refundid'],
            'order_goods'    => $order_goods,
            'order_goods_id' => $order_goods_id
        );
    }

    /*
     * 售后处理页面
     */
    public function refund_index()
    {
        global $_W;
        global $_GPC;

        extract($this->globalData());

        if ($order_goods['refund_status'] == 1) {
            show_json(0, '订单商品退款已经处理完毕!');
        }

        $refund = false;
        $imgnum = 0;

        if (0 < $order_goods['rstate'] || $order_goods['refundid'] > 0) {

            if (!empty($refundid)) {

                $refund = pdo_fetch(
                    ' select * ' .
                    ' from ' . tablename('superdesk_shop_order_refund') .
                    ' where id=:id ' .
                    '       and uniacid=:uniacid ' .
                    ' limit 1',
                    array(
                        ':id'      => $refundid,
                        ':uniacid' => $_W['uniacid'],
                    )
                );

//                if ($order_goods['return_goods_nun'] >= $order_goods['total'] && $refund['status'] != 0) {
//                    show_json(0, '该订单商品已全数申请退款');
//                }

                if (!empty($refund['refundaddress'])) {
                    $refund['refundaddress'] = iunserializer($refund['refundaddress']);
                }

                if (!empty($refund['imgs'])) {
                    $refund['imgs'] = iunserializer($refund['imgs']);
                }

                if($refund['status'] == -1){
                    $refund = false;
                }
            }

        }


        if (empty($refund)) {

            $show_price = round($order_goods['refundprice'], 2);
            $show_total = $order_goods['total'];
        } else {

            $show_price = round($refund['applyprice'], 2);
            $show_total = $refund['refund_total'];
        }

        $express_list = m('express')->getExpressList();

        $r_type = array(
            0 => '退货',
            1 => '换货',
            2 => '维修',
            3 => '退货退款'
        );

        $rtype_value = empty($refund) ? ($order['paytype'] == 3 ? '退货' : '退货退款') : $r_type[$refund['rtype']];


        $result = compact('order', 'refund', 'show_price', 'show_total', 'order_goods', 'express_list', 'rtype_value');

        show_json(1, $result);
    }

    /*
     * 售后处理提交
     */
    public function submit()
    {
        global $_W;
        global $_GPC;

        extract($this->globalData());

        if ($order_goods['refund_status'] == 1) {
            show_json(0, '订单商品退款已经处理完毕!');
        }

        $r_type = array('退货', '换货', '维修', '退货退款');
        $rtype = intval($_GPC['rtype']);// 处理方式 0 退货 1 换货 2 维修 3 退货退款

//        $price = trim($_GPC['price']);
        $total = trim($_GPC['total']);
        $unitprice = $order_goods['refundprice'] / $order_goods['total'];   //单价
        $price = $rtype == 3 ? $unitprice * $total : 0;
//        $price = round($order_goods['refundprice'], 2);
//        $total = $order_goods['total'];

        //因为一退就是退所有该商品,完全可以由后台计算,不需要用户输入,所以这一段可以删除??
//        if ($rtype != 2) {
//
//            if (empty($price)) {
//                show_json(0, '退款金额不能为0元');
//            }
//
//
//            if ($order_goods['refundprice'] < $price) {
//                show_json(0, '退款金额不能超过' . $order_goods['refundprice'] . '元');
//            }
//
//        }

        if (empty($total)) {
            show_json(0, $r_type[$rtype] . '数量必须大于0');
        }

        if ($order_goods['total'] < $total) {
            show_json(0, $r_type[$rtype] . '数量不能超过' . $order_goods['total']);
        }


        //TODO 可能出现的问题
        //假定一笔订单1000元 各种减免后实际支付300元
        //共10个商品,不同价格,最便宜的10元
        //退款如何计算实际应退价格?

        $refund = array(
            'uniacid'      => $_W['uniacid'],
            'merchid'      => $order['merchid'],
            'applyprice'   => $price,
            'refund_total' => $total,
            'rtype'        => $rtype,
            'reason'       => trim($_GPC['reason']),//退款原因 不想要了 | 卖家缺货 | 拍错了/订单信息错误 | 其它
            'content'      => trim($_GPC['content']),//退款说明(选填)
            'imgs'         => iserializer($_GPC['images'])
        );

        if ($refund['rtype'] == 3) {
            $refundstate = 1;   //涉及金额  退货
        } else {
            $refundstate = 2;   //不涉及金额 换货,维修
        }

        if ($order_goods['rstate'] == 0) {

            $refund['createtime']     = time();
            $refund['orderid']        = $orderid;
            $refund['order_goods_id'] = $order_goods_id;
            $refund['orderprice']     = $order_goods['realprice'];
            $refund['refundno']       = m('common')->createNO('order_refund', 'refundno', 'SR');
            pdo_insert('superdesk_shop_order_refund', $refund);
            $refundid = pdo_insertid();

            //mark kafka 为了kafka转成了model执行
            $this->_order_goodsModel->updateByColumn(
                array(
                    'refundid'         => $refundid,
                    'return_goods_nun' => $total,
                    'rstate'           => $refundstate
                ),
                array(
                    'id'      => $order_goods_id,
                    'uniacid' => $_W['uniacid']
                )
            );

        } else {

            pdo_update('superdesk_shop_order_refund',
                $refund,
                array(
                    'id'      => $refundid,
                    'uniacid' => $_W['uniacid']
                )
            );

            //mark kafka 为了kafka转成了model执行
            $this->_order_goodsModel->updateByColumn(
                array(
                    'rstate' => $refundstate
                ),
                array(
                    'id'      => $order_goods_id,
                    'uniacid' => $_W['uniacid']
                )
            );

        }

//        m('notice')->sendOrderMessage($orderid, true);    //整改期间暂时屏蔽
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

        pdo_update(
            'superdesk_shop_order_refund',
            $change_refund,
            array(
                'id'      => $refundid,
                'uniacid' => $_W['uniacid']
            )
        );

        //mark kafka 为了kafka转成了model执行
        $this->_order_goodsModel->updateByColumn(
            array(
                'refundid'          => 0,
                'return_goods_nun' => 0,
                'rstate'            => 0
            ),
            array(
                'id'      => $order_goods_id,
                'uniacid' => $_W['uniacid']
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

        pdo_update(
            'superdesk_shop_order_refund',
            $refund,
            array(
                'id'      => $refundid,
                'uniacid' => $_W['uniacid']
            )
        );

        show_json(1);
    }

    public function receive()
    {
        global $_W;
        global $_GPC;

        extract($this->globalData());

        $refund = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_order_refund') .
            ' where id=:id ' .
            '       and uniacid=:uniacid ' .
            '       and order_goods_id=:order_goods_id ' .
            ' limit 1',
            array(
                ':id'             => $refundid,
                ':uniacid'        => $_W['uniacid'],
                ':order_goods_id' => $order_goods_id
            )
        );

        if (empty($refund)) {
            show_json(0, '申请未找到!');
        }


        $time = time();

        $refund_data               = array();
        $refund_data['status']     = 1;
        $refund_data['refundtime'] = $time;

        pdo_update(
            'superdesk_shop_order_refund',
            $refund_data,
            array(
                'id'      => $refundid,
                'uniacid' => $_W['uniacid']
            )
        );

        $order_data                  = array();
        $order_data['rstate']        = 0;
        $order_data['refund_status'] = 1;
        $order_data['refundtime']    = $time;
        //mark kafka 为了kafka转成了model执行
        $this->_order_goodsModel->updateByColumn(
            $order_data,
            array(
                'id'      => $order_goods_id,
                'uniacid' => $_W['uniacid']
            )
        );

        show_json(1);
    }

    public function refundexpress()
    {
        global $_W;
        global $_GPC;

//        extract($this->globalData());

        $express    = trim($_GPC['express']);
        $expresssn  = trim($_GPC['expresssn']);
        $expresscom = trim($_GPC['expresscom']);

        $expresslist = m('util')->getExpressList($express, $expresssn);

        if(isset($expresslist['err'])){
            $expresslist = array();
        }

        show_json(1, compact('express', 'expresssn', 'expresscom', 'expresslist'));
    }
}