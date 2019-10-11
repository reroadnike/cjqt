<?php

class Map_SuperdeskShopV2Page extends PluginMobilePage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $merchid = intval($_GPC['merchid']);

        $store = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_merch_user') .
            ' where ' .
            '       id=:merchid ' .
            '       and uniacid=:uniacid ' .
            ' Limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':merchid' => $merchid
            )
        );

        include $this->template();
    }
}