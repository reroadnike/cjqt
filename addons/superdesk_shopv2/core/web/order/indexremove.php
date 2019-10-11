<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

//mark kafka 为了kafka转成了model执行
include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');

class Indexremove_SuperdeskShopV2Page extends WebPage
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

        if ($_W['ispost']) {

            $orderid  = trim($_GPC['data']['orderid']);
            $recharge = trim($_GPC['data']['recharge']);
            $params   = array('uniacid' => $_W['uniacid']);

            if (!empty($orderid)) {
                $params['ordersn'] = $orderid;
                //mark kafka 为了kafka转成了model执行
                $this->_orderModel->updateByColumn(
                    array(
                        'deleted' => 1
                    ),
                    $params
                );

                show_json(1);
            }


            if (!empty($recharge)) {
                $params['logno'] = $recharge;
                pdo_delete('superdesk_shop_member_log', $params); // TODO 标志 楼宇之窗 openid superdesk_shop_member_log 不处理
                show_json(1);
            }

        }


        include $this->template();
    }
}


