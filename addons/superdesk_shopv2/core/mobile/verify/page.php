<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');

class Page_SuperdeskShopV2Page extends MobilePage
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

        $merchid      = 0;
        $merch_plugin = p('merch');

        $saler = pdo_fetch(
            'select * ' .
            ' from ' . tablename('superdesk_shop_saler') .
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            ' limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        if (empty($saler) && $merch_plugin) {

            $saler = pdo_fetch(
                'select * ' .
                ' from ' . tablename('superdesk_shop_merch_saler') .
                ' where ' .
                '       uniacid=:uniacid ' .
                '       and openid=:openid ' .
                '       and core_user=:core_user ' .
                ' limit 1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $_W['openid'],
                    ':core_user' => $_W['core_user'],
                )
            );
        }

        if (empty($saler)) {
            $this->message('您无核销权限!');
        } else {
            $merchid = $saler['merchid'];
        }

        $member = m('member')->getMember($saler['openid'], $saler['core_user']);

        $store = false;

        if (!empty($saler['storeid'])) {

            if (0 < $merchid) {

                $store = pdo_fetch(
                    'select * from ' . tablename('superdesk_shop_merch_store') .
                    ' where id=:id and uniacid=:uniacid and merchid = :merchid limit 1',
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

        include $this->template();
    }

    public function search()
    {
        global $_W;
        global $_GPC;

        $verifycode = trim($_GPC['verifycode']);
        if (empty($verifycode)) {
            show_json(0, '请填写消费码或自提码');
        }

        $orderid = pdo_fetchcolumn(
            'select id ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and ( verifycode=:verifycode or verifycodes like :verifycodes ) ' .
            ' limit 1 ',
            array(
                ':uniacid'     => $_W['uniacid'],
                ':verifycode'  => $verifycode,
                ':verifycodes' => '%|' . $verifycode . '|%'
            )
        );
        if (empty($orderid)) {
            show_json(0, '未查询到订单,请核对');
        }

        $allow = com('verify')->allow($orderid);
        if (is_error($allow)) {
            show_json(0, $allow['message']);
        }
        extract($allow);

        $verifyinfo = iunserializer($order['verifyinfo']);

        if ($order['verifytype'] == 2) {

            foreach ($verifyinfo as &$v) {
                unset($v['select']);
                if ($v['verifycode'] == $verifycode) {
                    if ($v['verified']) {
                        show_json(0, '此消费码已经使用!');
                    }
                    $v['select'] = 1;
                }
            }

            unset($v);
            //mark kafka 为了kafka转成了model执行
            $this->_orderModel->updateByColumn(
                array(
                    'verifyinfo' => iserializer($verifyinfo)
                ),
                array(
                    'id' => $orderid
                )
            );
        }
        show_json(1, array('orderid' => $orderid));
    }

    public function complete()
    {
        global $_W;
        global $_GPC;

        $orderid = intval($_GPC['id']);
        $times   = intval($_GPC['times']);

        com('verify')->verify($orderid, $times);

        show_json(1);
    }
}