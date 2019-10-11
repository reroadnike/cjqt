<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');

class Index_SuperdeskShopV2Page extends MobilePage
{
    private $_orderModel;


    public function __construct()
    {

        global $_W;
        global $_GPC;
        parent::__construct();

        $this->_orderModel = new orderModel();
    }

    public function qrcode()
    {
        global $_W;
        global $_GPC;

        $orderid    = intval($_GPC['id']);
        $verifycode = $_GPC['verifycode'];

        $query = array(
            'id'         => $orderid,
            'verifycode' => $verifycode
        );

        $url = mobileUrl('verify/detail', $query, true);

        show_json(1, array(
            'url' => m('qrcode')->createQrcode($url)
        ));
    }

    public function select()
    {
        global $_W;
        global $_GPC;

        $orderid    = intval($_GPC['id']);
        $verifycode = trim($_GPC['verifycode']);

        if (empty($verifycode) || empty($orderid)) {
            show_json(0);
        }

        $order = pdo_fetch(
            'select id,verifyinfo ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where ' .
            '       id=:id ' .
            '       and uniacid=:uniacid ' .
            ' limit 1',
            array(
                ':id'      => $orderid,
                ':uniacid' => $_W['uniacid']
            )
        );

        if (empty($order)) {
            show_json(0);
        }

        $verifyinfo = iunserializer($order['verifyinfo']);

        foreach ($verifyinfo as &$v) {
            if ($v['verifycode'] == $verifycode) {
                if (!empty($v['select'])) {
                    $v['select'] = 0;
                } else {
                    $v['select'] = 1;
                }
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

        show_json(1);
    }

    public function check()
    {
        global $_W;
        global $_GPC;

        $orderid = intval($_GPC['id']);

        $order = pdo_fetch(
            ' select ' .
            '       id,status,isverify,verified ' .
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
            show_json(0);
        }

        if (empty($order['isverify'])) {
            show_json(0);
        }

        if ($order['verifytype'] == 0) {
            if (empty($order['verified'])) {
                show_json(0);
            }
        }

        show_json(1);
    }

    public function detail()
    {
        global $_W;
        global $_GPC;

        $orderid = intval($_GPC['id']);

        $data = com('verify')->allow($orderid);

        if (is_error($data)) {
            $this->message($data['message']);
        }

        extract($data);

        include $this->template();
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

    public function success()
    {
        global $_W;
        global $_GPC;

        $id    = intval($_GPC['orderid']);
        $times = intval($_GPC['times']);

        $this->message(
            array(
                'title'   => '操作完成',
                'message' => '您可以退出浏览器了'
            ),
            'javascript:WeixinJSBridge.call("closeWindow");',
            'success'
        );
    }
}

