<?php

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

class Center_SuperdeskShopV2Page extends PluginMobilePage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        include $this->template();
    }
}