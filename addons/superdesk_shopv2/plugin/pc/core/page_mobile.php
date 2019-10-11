<?php

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

require SUPERDESK_SHOPV2_PLUGIN . "pc/core/common.php";

class PcMobilePage extends PluginMobilePage
{

    public function __construct()
    {
        global $_W;
//        global $_GPC;

        $member = check_login();

        if (!empty($member)) {
            $_W['mopenid']   = $member['openid'];
            $_W['openid']    = $member['openid'];
            $_W['core_user'] = $member['core_user'];
            $_W['core_enterprise'] = $member['core_enterprise'];
        }

        m('account')->setFilterMerch($member);
    }

}