<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Index_SuperdeskShopV2Page extends WebPage
{
    public function main()
    {
        global $_W;

        $shop_data    = m('common')->getSysset('shop');
        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }

        $order_sql =
            ' select ' .
            '       id,ordersn,createtime,price,address,addressid,invoice,invoiceid ' .
            ' from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where uniacid = :uniacid ' .
            '		and merchid=0 ' .
            '		and isparent=0 ' .
            '		and deleted=0 ' .
            '       AND ( status = 1 or (status=0 and paytype=3) ) ' .
            ' ORDER BY createtime ASC ' .
            ' LIMIT 20';

        $order = pdo_fetchall(
            $order_sql,
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        foreach ($order as &$value) {
            $value['address'] = iunserializer($value['address']);
            $value['invoice'] = iunserializer($value['invoice']);
        }

        unset($value);

        $order_ok = $order;
        $notice   = pdo_fetchall(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_system_copyright_notice') .
            ' ORDER BY displayorder ASC,createtime DESC LIMIT 10');

        $hascommission = false;
        if (p('commission')) {
            $hascommission = 0 < intval($_W['shopset']['commission']['level']);
        }


        include $this->template();
    }

    public function view()
    {
        global $_GPC;

        $id = intval($_GPC['id']);

        $item = pdo_fetch(
            ' SELECT * ' .
            ' FROM ' . tablename('superdesk_shop_system_copyright_notice') .
            ' WHERE ' .
            '       id = ' . $id .
            ' ORDER BY displayorder ASC,createtime DESC');

        $item['content'] = htmlspecialchars_decode($item['content']);

        include $this->template('shop/view');
    }

    public function ajax()
    {
        global $_W;

        $paras = array(
            ':uniacid' => $_W['uniacid']
        );

        $goods_totals = pdo_fetchcolumn(
            ' SELECT COUNT(1) ' .
            ' FROM ' . tablename('superdesk_shop_goods') .
            ' WHERE uniacid = :uniacid ' .
            '       and status=1 ' .
            '       and deleted=0 ' .
            '       and total<=0 ' .
            '       and total<>-1  ',
            $paras
        );

        $finance_total = pdo_fetchcolumn(
            ' select count(1) ' .
            ' from ' . tablename('superdesk_shop_member_log') . ' log ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_log 待处理
            ' left join ' . tablename('superdesk_shop_member') . ' m on m.openid=log.openid and m.core_user=log.core_user and m.uniacid= log.uniacid' . // TODO 标志 楼宇之窗 openid shop_member 已处理
            ' left join ' . tablename('superdesk_shop_member_group') . ' g on m.groupid=g.id' .
            ' left join ' . tablename('superdesk_shop_member_level') . ' l on m.level =l.id' .
            ' where log.uniacid=:uniacid ' .
            '       and log.type=:type ' .
            '       and log.money<>0 ' .
            '       and log.status=:status',
            array(
                ':uniacid' => $_W['uniacid'],
                ':type'    => 1,
                ':status'  => 0
            )
        );

        $commission_agent_total = pdo_fetchcolumn(
            ' select count(1) ' .
            ' from' . tablename('superdesk_shop_member') . ' dm ' . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' left join ' . tablename('superdesk_shop_member') . ' p on p.id = dm.agentid ' . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' left join ' . tablename('mc_mapping_fans') . 'f on f.openid=dm.openid' .
            ' where ' .
            '       dm.uniacid =:uniacid ' .
            '       and dm.isagent =1',
            array(
                ':uniacid' => $_W['uniacid']
            )
        );

        $commission_agent_status0_total = pdo_fetchcolumn(
            ' select count(1) ' .
            ' from' . tablename('superdesk_shop_member') . ' dm ' . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' left join ' . tablename('superdesk_shop_member') . ' p on p.id = dm.agentid ' . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' left join ' . tablename('mc_mapping_fans') . 'f on f.openid=dm.openid' .
            ' where ' .
            '       dm.uniacid =:uniacid ' .
            '       and dm.isagent =1 ' .
            '       and dm.status=:status',
            array(
                ':uniacid' => $_W['uniacid'],
                ':status'  => 0
            )
        );

        $commission_apply_status1_total = pdo_fetchcolumn(
            ' select count(1) '.
            ' from' . tablename('superdesk_shop_commission_apply') . ' a ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on m.uid = a.mid' . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' left join ' . tablename('superdesk_shop_commission_level') . ' l on l.id = m.agentlevel' .
            ' where ' .
            '       a.uniacid=:uniacid ' .
            '       and a.status=:status',
            array(
                ':uniacid' => $_W['uniacid'],
                ':status'  => 1
            )
        );

        $commission_apply_status2_total = pdo_fetchcolumn(
            ' select count(1) '.
            ' from' . tablename('superdesk_shop_commission_apply') . ' a ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on m.uid = a.mid' . // TODO 标志 楼宇之窗 openid shop_member 不处理
            ' left join ' . tablename('superdesk_shop_commission_level') . ' l on l.id = m.agentlevel' .
            ' where ' .
            '       a.uniacid=:uniacid ' .
            '       and a.status=:status',
            array(
                ':uniacid' => $_W['uniacid'],
                ':status'  => 2
            )
        );

        show_json(1, array(
                'goods_totals'                   => $goods_totals,
                'finance_total'                  => $finance_total,
                'commission_agent_total'         => $commission_agent_total,
                'commission_agent_status0_total' => $commission_agent_status0_total,
                'commission_apply_status1_total' => $commission_apply_status1_total,
                'commission_apply_status2_total' => $commission_apply_status2_total)
        );
    }
}