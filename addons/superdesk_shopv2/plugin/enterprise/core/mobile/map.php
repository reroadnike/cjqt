<?php

class Map_SuperdeskShopV2Page extends PluginMobilePage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $enterprise_id = intval($_GPC['enterprise_id']);
        $store   = pdo_fetch(
            ' select * ' .
            ' from ' . tablename('superdesk_shop_enterprise_user') .
            ' where id=:enterprise_id and uniacid=:uniacid Limit 1',
            array(
                ':uniacid' => $_W['uniacid'],
                ':enterprise_id' => $enterprise_id
            )
        );
        include $this->template();
    }
}