<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

class List_SuperdeskShopV2Page extends MerchWebPage
{
    public function detail()
    {
        global $_W;
        global $_GPC;

        $id     = intval($_GPC['id']);
        $member = m('member')->getMember($id);

        $member['self_ordercount'] = pdo_fetchcolumn(
            'select count(*) ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and status=3',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $member['openid'],
                ':core_user' => $member['core_user']
            )
        );

        $member['self_ordermoney'] = pdo_fetchcolumn(
            'select sum(goodsprice) ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and status=3',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $member['openid'],
                ':core_user' => $member['core_user']
            )
        );

        $order = pdo_fetch(
            'select finishtime ' .
            ' from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where ' .
            '       uniacid=:uniacid ' .
            '       and openid=:openid ' .
            '       and core_user=:core_user ' .
            '       and status>=1 ' .
            ' Limit 1',
            array(
                ':uniacid'   => $_W['uniacid'],
                ':openid'    => $member['openid'],
                ':core_user' => $member['core_user']
            )
        );

        // TODO
        $member['last_ordertime'] = $order['finishtime'];

        $diyform_flag             = 0;
        $diyform_flag_commission  = 0;
        $diyform_plugin           = p('diyform');

        if ($diyform_plugin) {
            if (!empty($member['diymemberdata'])) {
                $diyform_flag = 1;
                $fields       = iunserializer($member['diymemberfields']);
            }


            if (!empty($member['diycommissiondata'])) {
                $diyform_flag_commission = 1;
                $cfields                 = iunserializer($member['diycommissionfields']);
            }

        }

        $groups = m('member')->getGroups();
        $levels = m('member')->getLevels();

        include $this->template();
    }
}