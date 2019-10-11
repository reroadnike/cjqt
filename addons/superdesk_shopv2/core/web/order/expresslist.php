<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Expresslist_SuperdeskShopV2Page extends WebPage
{
    public function main()
    {
        global $_W;

        $list = pdo_fetchall('select * from '. tablename('superdesk_shop_express') . ' order by displayorder asc,id asc');

        include $this->template();
    }

    public function change(){
        global $_GPC;
        pdo_update('superdesk_shop_express',['displayorder'=>$_GPC['value']],['id'=>$_GPC['id']]);

        show_json(1);
    }
}


