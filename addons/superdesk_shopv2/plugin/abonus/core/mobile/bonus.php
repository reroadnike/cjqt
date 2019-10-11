<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'abonus/core/page_login_mobile.php';

class Bonus_SuperdeskShopV2Page extends AbonusMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $status = intval($_GPC['status']);

        $bonus = $this->model->getBonus($_W['openid'], array('ok', 'lock', 'total'));

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
            ' and b.uniacid=:uniacid' .
            ' and b.openid=:openid ' .
            ' and b.core_user=:core_user ';


        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user'],
        );

        $status = trim($_GPC['status']);

        if ($status == 1) {
            $condition .= ' and b.status=1';
        } else if ($status == 2) {
            $condition .= ' and (b.status=-1 or b.status=0)';
        }

        $sql = 'select ' .
            '       b.*,m.aagenttype ' .
            ' from ' . tablename('superdesk_shop_abonus_billp') . ' b ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on b.uniacid=m.uniacid and m.openid = b.openid ' .
            ' where 1 ' .
            $condition .
            ' order by id desc ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;

        $list = pdo_fetchall($sql, $params);

        foreach ($list as &$row) {
            $row['paymoney'] = round($row['paymoney1'] + $row['paymoney2'] + $row['paymoney3'], 2);
        }

        unset($row);

        $total = pdo_fetchcolumn(
            'select count(*)  ' .
            ' from ' . tablename('superdesk_shop_abonus_billp') . ' b ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on b.uniacid=m.uniacid and m.openid = b.openid ' .
            ' where 1 ' .
            $condition .
            ' limit 1',
            $params
        );

        show_json(1, array(
            'total'    => $total,
            'list'     => $list,
            'pagesize' => $psize
        ));
    }
}