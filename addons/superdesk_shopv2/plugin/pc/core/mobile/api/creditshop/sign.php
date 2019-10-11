<?php

if (!(defined("IN_IA"))) {
    exit("Access Denied");
}

class Sign_SuperdeskShopV2Page extends PluginMobilePage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        include $this->template();
    }
}