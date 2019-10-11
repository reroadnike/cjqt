<?php

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/accessControl.php";

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/page_login_mobile.php";

class Util_SuperdeskShopV2Page extends PcMobileLoginPage
{
    public function query()
    {
        global $_W;
        global $_GPC;

        $type   = intval($_GPC['type']);
        $money  = floatval($_GPC['money']);
        $merchs = $_GPC['merchs'];
        $goods  = $_GPC['goods'];

        if ($type == 0) {   //订单的优惠券
            $list = com_run('coupon::getAvailableCoupons', $type, 0, $merchs, $goods);
        } else if ($type == 1) {    //充值的优惠券
            $list = com_run('coupon::getAvailableCoupons', $type, $money, $merchs);
        }


        show_json(1, array(
            'coupons' => $list
        ));
    }
}