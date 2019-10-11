<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class PluginMobileLoginPage extends PluginMobilePage
{
    public function __construct()
    {
        global $_W;
        global $_GPC;
        parent::__construct();

        $_W['isajax'] = 1;// TODO vuejs 纯ajax 非ajax此要屏蔽

    }
}