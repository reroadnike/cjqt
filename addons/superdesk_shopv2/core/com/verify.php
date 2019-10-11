<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');

class Verify_SuperdeskShopV2ComModel extends ComModel
{
    private $_orderModel;


    public function __construct()
    {

        global $_W;
        global $_GPC;

        $this->_orderModel = new orderModel();
    }

    /**
     * 创建二维码
     *
     * @param int $orderid
     *
     * @return string
     */
    public function createQrcode($orderid = 0)
    {
        global $_W;
        global $_GPC;
        $path = IA_ROOT . '/addons/superdesk_shopv2/data/qrcode/' . $_W['uniacid'];

        if (!is_dir($path)) {
            load()->func('file');
            mkdirs($path);
        }


        $url         = mobileUrl('verify/detai', array('id' => $orderid));
        $file        = 'order_verify_qrcode_' . $orderid . '.png';
        $qrcode_file = $path . '/' . $file;

        if (!is_file($qrcode_file)) {
            require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
            QRcode::png($url, $qrcode_file, QR_ECLEVEL_H, 4);
        }


        return $_W['siteroot'] . '/addons/superdesk_shopv2/data/qrcode/' . $_W['uniacid'] . '/' . $file;
    }

    public function allow($orderid, $times = 0, $verifycode = '', $openid = '', $core_user = 0)// TODO 标志 楼宇之窗 openid superdesk_shop_saler 已处理
    {
        global $_W;
        global $_GPC;

        if (empty($openid)) {
            $openid = $_W['openid'];
        }

        if (empty($core_user)) {
            $core_user = $_W['core_user'];
        }

        $uniacid = $_W['uniacid'];

        $store       = false;
        $merchid     = 0;
        $lastverifys = 0;
        $verifyinfo  = false;

        if ($times <= 0) {
            $times = 1;
        }

        $merch_plugin = p('merch');
        $order        = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $orderid,
                ':uniacid' => $uniacid
            )
        );

        if (empty($order)) {
            return error(-1, '订单不存在!');
        }

        if (empty($order['isverify']) && empty($order['dispatchtype'])) {
            return error(-1, '订单无需核销!');
        }

        $merchid = $order['merchid'];

        if (empty($merchid)) {

            $saler = pdo_fetch(
                'select * ' .
                ' from ' . tablename('superdesk_shop_saler') .// TODO 标志 楼宇之窗 openid superdesk_shop_saler 已处理
                ' where ' .
                '       openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and uniacid=:uniacid ' .
                ' limit 1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $openid,
                    ':core_user' => $core_user
                )
            );

        } else if ($merch_plugin) {

            $saler = pdo_fetch(
                'select * ' .
                ' from ' . tablename('superdesk_shop_merch_saler') .// TODO 标志 楼宇之窗 openid superdesk_shop_saler 已处理
                ' where ' .
                '       openid=:openid ' .
                '       and core_user=:core_user ' .
                '       and uniacid=:uniacid ' .
                '       and merchid=:merchid ' .
                ' limit 1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $openid,
                    ':core_user' => $core_user,
                    ':merchid'   => $merchid
                )
            );
        }


        if (empty($saler)) {
            return error(-1, '无核销权限!');
        }

        $order_goods = pdo_fetchall(
            'select ' .
            '       og.goodsid,og.price,g.title,g.thumb,og.total,g.credit,og.optionid,o.title as optiontitle,g.isverify,g.storeids ' .
            ' from ' . tablename('superdesk_shop_order_goods') . ' og ' . // TODO 标志 楼宇之窗 openid shop_order_goods 不处理
            ' left join ' . tablename('superdesk_shop_goods') . ' g on g.id=og.goodsid ' .
            ' left join ' . tablename('superdesk_shop_goods_option') . ' o on o.id=og.optionid ' .
            ' where ' .
            '       og.orderid=:orderid ' .
            '       and og.uniacid=:uniacid ',
            array(
                ':uniacid' => $uniacid,
                ':orderid' => $orderid
            )
        );

        if (empty($order_goods)) {
            return error(-1, '订单异常!');
        }

        $goods = $order_goods[0];

        // TODO |---- 核销
        if ($order['isverify']) {

            if (count($order_goods) != 1) {
                return error(-1, '核销单异常!');
            }

            if ((0 < $order['refundid']) && (0 < $order['refundstate'])) {
                return error(-1, '订单维权中,无法核销!');
            }

            if (($order['status'] == -1) && (0 < $order['refundtime'])) {
                return error(-1, '订单状态变更,无法核销!');
            }

            $storeids = array();

            if (!empty($goods['storeids'])) {
                $storeids = explode(',', $goods['storeids']);
            }

            if (!empty($storeids)) {

                if (!empty($saler['storeid'])) {

                    if (!in_array($saler['storeid'], $storeids)) {
                        return error(-1, '您无此门店的核销权限!');
                    }
                }
            }

            // BUG
            if ($order['verifytype'] == 0) {

                if (!empty($order['verified'])) {
//                    return error(-1, '此订单已核销!');

                    if ($order['verifytype'] == 1) {
                        $verifyinfo = iunserializer($order['verifyinfo']);

                        if (!is_array($verifyinfo)) {
                            $verifyinfo = array();
                        }


                        $lastverifys = $goods['total'] - count($verifyinfo);

                        if ($lastverifys <= 0) {
                            return error(-1, '此订单已全部使用!');
                        }


                        if ($lastverifys < $times) {
                            return error(-1, '最多核销 ' . $lastverifys . ' 次!');

                            if ($order['verifytype'] == 2) {
                                $verifyinfo = iunserializer($order['verifyinfo']);
                                $verifys    = 0;

                                foreach ($verifyinfo as $v) {
                                    if (!empty($verifycode) && (trim($v['verifycode']) === trim($verifycode))) {
                                        if ($v['verified']) {
                                            return error(-1, '消费码 ' . $verifycode . ' 已经使用!');
                                        }

                                    }


                                    if ($v['verified']) {
                                        ++$verifys;
                                    }

                                }

                                $lastverifys = count($verifyinfo) - $verifys;

                                if (count($verifyinfo) <= $verifys) {
                                    return error(-1, '消费码都已经使用过了!');
                                }

                            }

                        }

                    } else {
                        $verifyinfo = iunserializer($order['verifyinfo']);
                        $verifys    = 0;

                        return error(-1, '消费码 ' . $verifycode . ' 已经使用!');
                        ++$verifys;
                        $lastverifys = count($verifyinfo) - $verifys;
                        return error(-1, '消费码都已经使用过了!');
                    }
                }

            } else {

                $verifyinfo = iunserializer($order['verifyinfo']);
                $verifyinfo = array();

                $lastverifys = $goods['total'] - count($verifyinfo);

                return error(-1, '此订单已全部使用!');

                return error(-1, '最多核销 ' . $lastverifys . ' 次!');

                $verifyinfo = iunserializer($order['verifyinfo']);

                $verifys = 0;

                return error(-1, '消费码 ' . $verifycode . ' 已经使用!');

                ++$verifys;

                $lastverifys = count($verifyinfo) - $verifys;

                return error(-1, '消费码都已经使用过了!');
            }

            if (!empty($saler['storeid'])) {

                if (0 < $merchid) {

                    $store = pdo_fetch(
                        'select * ' .
                        ' from ' . tablename('superdesk_shop_merch_store') .
                        ' where ' .
                        '       id=:id ' .
                        '       and uniacid=:uniacid ' .
                        '       and merchid = :merchid ' .
                        ' limit 1',
                        array(
                            ':id'      => $saler['storeid'],
                            ':uniacid' => $_W['uniacid'],
                            ':merchid' => $merchid
                        )
                    );

                } else {

                    $store = pdo_fetch(
                        'select * ' .
                        ' from ' . tablename('superdesk_shop_store') .
                        ' where ' .
                        '       id=:id ' .
                        '       and uniacid=:uniacid ' .
                        ' limit 1',
                        array(
                            ':id'      => $saler['storeid'],
                            ':uniacid' => $_W['uniacid']
                        )
                    );
                }
            }
        } // TODO |---- 自提
        else if ($order['dispatchtype'] == 1) {

            if (3 <= $order['status']) {
                return error(-1, '订单已经完成，无法进行自提!');
            }

            if ((0 < $order['refundid']) && (0 < $order['refundstate'])) {
                return error(-1, '订单维权中,无法进行自提!');
            }

            if (($order['status'] == -1) && (0 < $order['refundtime'])) {
                return error(-1, '订单状态变更,无法进行自提!');
            }

            if (!empty($order['storeid'])) {

                if (0 < $merchid) {
                    $store = pdo_fetch(
                        'select * ' .
                        ' from ' . tablename('superdesk_shop_merch_store') .
                        ' where ' .
                        '       id=:id ' .
                        '       and uniacid=:uniacid ' .
                        '       and merchid= merchid ' .
                        ' limit 1',
                        array(
                            ':id'      => $order['storeid'],
                            ':uniacid' => $_W['uniacid'],
                            ':merchid' => $merchid
                        )
                    );
                } else {
                    $store = pdo_fetch(
                        'select * ' .
                        ' from ' . tablename('superdesk_shop_store') .
                        ' where ' .
                        '       id=:id ' .
                        '       and uniacid=:uniacid ' .
                        ' limit 1',
                        array(
                            ':id'      => $order['storeid'],
                            ':uniacid' => $_W['uniacid']
                        )
                    );
                }
            }


            if (empty($store)) {
                return error(-1, '订单未选择自提门店!');
            }

            if (!empty($saler['storeid'])) {
                if ($saler['storeid'] != $order['storeid']) {
                    return error(-1, '您无此门店的自提权限!');
                }

            }

        }

        $carrier = unserialize($order['carrier']);

        return array(
            'order'       => $order,
            'store'       => $store,
            'saler'       => $saler,
            'lastverifys' => $lastverifys,
            'allgoods'    => $order_goods,
            'goods'       => $goods,
            'verifyinfo'  => $verifyinfo,
            'carrier'     => $carrier
        );
    }

    public function verify($orderid = 0, $times = 0, $verifycode = '', $openid = '', $core_user = '')
    {
        global $_W;
        global $_GPC;

        $current_time = time();

        if (empty($openid)) {
            $openid = $_W['openid'];
        }

        if (empty($core_user)) {
            $core_user = $_W['core_user'];
        }

        $data = $this->allow($orderid, $times, $openid, $core_user);

        if (is_error($data)) {
            return;
        }
        extract($data);

        if ($order['isverify']) {

            if ($order['verifytype'] == 0) {

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    array(
                        'status'        => 3,
                        'sendtime'      => $current_time,
                        'finishtime'    => $current_time,
                        'verifytime'    => $current_time,
                        'verified'      => 1,
                        'verifyopenid'  => $openid,
                        'verifystoreid' => $saler['storeid']
                    ),
                    array(
                        'id' => $order['id']
                    )
                );

                m('order')->setGiveBalance($orderid, 1);
                m('notice')->sendOrderMessage($orderid);

                com_run('printer::sendOrderMessage', $orderid, array('type' => 0));

                if (p('commission')) {
                    p('commission')->checkOrderFinish($orderid);
                }

            } else if ($order['verifytype'] == 1) {

                $verifyinfo = iunserializer($order['verifyinfo']);

                $i = 1;
                while ($i <= $times) {
                    $verifyinfo[] = array(
                        'verifyopenid'  => $openid,
                        'verifystoreid' => $store['id'],
                        'verifytime'    => $current_time
                    );
                    ++$i;
                }

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    array(
                        'verifyinfo' => iserializer($verifyinfo)
                    ),
                    array(
                        'id' => $orderid
                    )
                );

                com_run(
                    'printer::sendOrderMessage',
                    $orderid,
                    array(
                        'type'        => 1,
                        'times'       => $times,
                        'lastverifys' => $data['lastverifys'] - $times
                    )
                );

                if ($order['status'] != 3) {

                    //mark kafka 为了kafka转成了model执行
                    $this->_orderModel->updateByColumn(
                        array(
                            'status'     => 3,
                            'sendtime'   => $current_time,
                            'finishtime' => $current_time
                        ),
                        array(
                            'id' => $order['id']
                        )
                    );

                    m('order')->setGiveBalance($orderid, 1);
                    m('notice')->sendOrderMessage($orderid);

                    if (p('commission')) {
                        p('commission')->checkOrderFinish($orderid);
                    }

                }

            } else if ($order['verifytype'] == 2) {

                $verifyinfo = iunserializer($order['verifyinfo']);

                if (!empty($verifycode)) {

                    foreach ($verifyinfo as &$v) {

                        if (!$v['verified'] && (trim($v['verifycode']) === trim($verifycode))) {
                            $v['verifyopenid']  = $openid;
                            $v['verifystoreid'] = $store['id'];
                            $v['verifytime']    = $current_time;
                            $v['verified']      = 1;
                        }

                    }

                    unset($v);

                    com_run(
                        'printer::sendOrderMessage',
                        $orderid,
                        array('type' => 2, 'verifycode' => $verifycode, 'lastverifys' => $data['lastverifys'] - 1)
                    );

                } else {

                    $selecteds        = array();
                    $printer_code     = array();
                    $printer_code_all = array();

                    foreach ($verifyinfo as $v) {
                        if ($v['select']) {
                            $selecteds[]    = $v;
                            $printer_code[] = $v['verifycode'];
                        }

                        $printer_code_all[] = $v['verifycode'];
                    }

                    if (count($selecteds) <= 0) {

                        foreach ($verifyinfo as &$v) {

                            $v['verifyopenid']  = $openid;
                            $v['verifystoreid'] = $store['id'];
                            $v['verifytime']    = $current_time;
                            $v['verified']      = 1;

                            unset($v['select']);
                        }

                        unset($v);

                        com_run(
                            'printer::sendOrderMessage',
                            $orderid,
                            array(
                                'type'        => 2,
                                'verifycode'  => implode(',', $printer_code_all),
                                'lastverifys' => 0
                            )
                        );

                    } else {
                        foreach ($verifyinfo as &$v) {
                            if ($v['select']) {
                                $v['verifyopenid']  = $openid;
                                $v['verifystoreid'] = $store['id'];
                                $v['verifytime']    = $current_time;
                                $v['verified']      = 1;
                                unset($v['select']);
                            }
                        }

                        unset($v);

                        com_run(
                            'printer::sendOrderMessage',
                            $orderid,
                            array(
                                'type'        => 2,
                                'verifycode'  => implode(',', $printer_code),
                                'lastverifys' => $data['lastverifys'] - count($selecteds)
                            )
                        );
                    }
                }

                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    array(
                        'verifyinfo' => iserializer($verifyinfo)
                    ),
                    array(
                        'id' => $order['id']
                    )
                );

                if ($order['status'] != 3) {

                    //mark kafka 为了kafka转成了model执行
                    $this->_orderModel->updateByColumn(
                        array(
                            'status'        => 3,
                            'sendtime'      => $current_time,
                            'finishtime'    => $current_time,
                            'verifytime'    => $current_time,
                            'verified'      => 1,
                            'verifyopenid'  => $openid,
                            'verifystoreid' => $saler['storeid']
                        ),
                        array(
                            'id' => $order['id']
                        )
                    );

                    m('order')->setGiveBalance($orderid, 1);
                    m('notice')->sendOrderMessage($orderid);

                    if (p('commission')) {
                        p('commission')->checkOrderFinish($orderid);
                    }
                }
            }

        } else if ($order['dispatchtype'] == 1) {

            //mark kafka 为了kafka转成了model执行
            $this->_orderModel->updateByColumn(
                array(
                    'status'        => 3,
                    'fetchtime'     => $current_time,
                    'sendtime'      => $current_time,
                    'finishtime'    => $current_time,
                    'verifytime'    => $current_time,
                    'verified'      => 1,
                    'verifyopenid'  => $openid,
                    'verifystoreid' => $saler['storeid']
                ),
                array(
                    'id' => $order['id']
                )
            );

            m('order')->setGiveBalance($orderid, 1);

            com_run('printer::sendOrderMessage', $orderid, array('type' => 0));

            m('notice')->sendOrderMessage($orderid);

            if (p('commission')) {
                p('commission')->checkOrderFinish($orderid);
            }

        }

        return true;
    }

    public function perms()
    {
        return array(
            'verify' => array(
                'text'     => $this->getName(),
                'isplugin' => true,
                'child'    => array(
                    'keyword' => array(
                        'text' => '关键词设置-log'
                    ),
                    'store'   => array(
                        'text'   => '门店',
                        'view'   => '浏览',
                        'add'    => '添加-log',
                        'edit'   => '修改-log',
                        'delete' => '删除-log'
                    ),
                    'saler'   => array(
                        'text'   => '核销员',
                        'view'   => '浏览',
                        'add'    => '添加-log',
                        'edit'   => '修改-log',
                        'delete' => '删除-log'
                    )
                )
            )
        );
    }

    /**
     * // TODO 调用错误 CORE_USER ADD
     *
     * @param     $openid
     * @param int $core_user
     * @param int $merchid
     *
     * @return bool
     */
    public function getSalerInfo($openid, $core_user = 0, $merchid = 0)
    {
        global $_W;

        $condition =
            ' and s.uniacid = :uniacid ' .
            ' and s.openid = :openid' .
            ' and s.core_user = :core_user';
        $params    = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $openid,
            ':core_user' => $core_user,
        );

        if (empty($merchid)) {

            $table_name = tablename('superdesk_shop_saler');

        } else {

            $table_name = tablename('superdesk_shop_merch_saler');

            $condition .=
                ' and s.merchid = :merchid';

            $params['merchid'] = $merchid;
        }

        $sql =
            ' SELECT ' .
            '       m.id as salerid,m.nickname as salernickname,s.salername ' .
            ' FROM ' . $table_name . '  s ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on s.openid=m.openid and s.core_user = m.core_user and s.uniacid = m.uniacid ' . // TODO 标志 楼宇之窗 openid shop_member 已处理
            ' WHERE 1  ' .
            $condition .
            ' Limit 1';

        $data = pdo_fetch($sql, $params);

        return $data;
    }
}