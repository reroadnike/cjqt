<?php
defined('IN_IA') or exit('Access Denied');


class Superdesk_wechat_scannerModuleSite extends WeModuleSite
{


    public function __construct()
    {
        global $_W;
//        $_W['openid'] = "oyDjJvyKtvYYbiOLFRamOLKe9bdM";

    }


    public function getMenus()
    {
        global $_W;
        return array(
            array('title' => '管理后台', 'icon' => 'fa fa-shopping-cart', 'url' => webUrl())
        );
    }


    public function createAngularJsUrl($do, $query = array(), $noredirect = true)
    {
        global $_W;

        $addhost     = true;
        $query['do'] = $do;
//        $query['m'] = 'business_dongyuantang';
        $query['m'] = strtolower($this->modulename);
        return murl('entry', $query, $noredirect, $addhost);
    }

    public function doMobileCreateUrl()
    {

        $ApiGetAllCaseCate = $this->createAngularJsUrl('h5_step_01.inc');

        echo $ApiGetAllCaseCate;
        echo '<br/>';


    }


}
