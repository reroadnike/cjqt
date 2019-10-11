<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 5/14/18
 * Time: 11:29 AM
 */
class Index_SuperdeskShopV2Page extends MobilePage
{

    public function main()
    {
        global $_W;
        global $_GPC;
        include $this->template();
    }
}