<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'globonus/core/page_login_mobile.php';

class Bonus_SuperdeskShopV2Page extends GlobonusMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $status = intval($_GPC['status']);

        $bonus = $this->model->getBonus($_W['openid'], $_W['core_user'], array('ok', 'lock', 'total'));// TODO 标志 楼宇之窗 openid shop_globonus_billp 已处理

        include $this->template();
    }

    public function get_list()
    {
        global $_W;
        global $_GPC;

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $condition =
            ' and openid=:openid ' .
            ' and core_user=:core_user ' .
            ' and uniacid=:uniacid ';

        $params = array(
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user'],
            ':uniacid'   => $_W['uniacid']
        );

        $status = trim($_GPC['status']);

        if ($status == 1) {
            $condition .= ' and status=1';
        } else if ($status == 2) {
            $condition .= ' and (status=-1 or status=0)';
        }


        $list = pdo_fetchall(
            'select * ' .
            ' from ' . tablename('superdesk_shop_globonus_billp') . // TODO 标志 楼宇之窗 openid shop_globonus_billp 已处理
            ' where 1 ' .
            $condition .
            ' order by id desc ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize,
            $params
        );

        $total = pdo_fetchcolumn(
            'select count(*) ' .
            ' from ' . tablename('superdesk_shop_globonus_billp') . // TODO 标志 楼宇之窗 openid shop_globonus_billp 已处理
            ' where 1 ' .
            $condition,
            $params
        );
        show_json(1, array(
            'total'    => $total,
            'list'     => $list,
            'pagesize' => $psize
        ));
    }
}