<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
require_once IA_ROOT . '/addons/superdesk_shopv2/version.php';
require_once IA_ROOT . '/addons/superdesk_shopv2/defines.php';
require_once SUPERDESK_SHOPV2_INC . 'functions.php';

class Superdesk_ShopV2ModuleSite extends WeModuleSite
{

    public function getMenus()
    {
        global $_W;
        return array(
            array('title' => '管理后台', 'icon' => 'fa fa-shopping-cart', 'url' => webUrl())
        );
    }

    public function doWebWeb()
    {
        m('route')->run();
    }

    public function doMobileMobile()
    {
        m('route')->run(false);
    }

    public function payResult($params)
    {
        return m('order')->payResult($params);
    }

    public function createMyJsUrl($do, $query = array(), $m = '', $noredirect = true, $addhost = true)
    {
        global $_W;

        if (empty($m)) {
            $m = strtolower($this->modulename);
        }

        $query['do'] = $do;
        $query['m']  = $m;

        return murl('entry', $query, $noredirect, $addhost);
    }

    public function createMyWebUrl($do, $query = array(), $m = '')
    {
        global $_W;

        if (empty($m)) {
            $m = strtolower($this->modulename);
        }

        $query['do'] = $do;
        $query['m']  = $m;
        return wurl('site/entry', $query);

    }
}