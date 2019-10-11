<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

require SUPERDESK_SHOPV2_PLUGIN . 'commission/core/page_login_mobile.php';

class Down_SuperdeskShopV2Page extends CommissionMobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $member      = $this->model->getInfo($_W['openid']);
        $levelcount1 = $member['level1'];
        $levelcount2 = $member['level2'];
        $levelcount3 = $member['level3'];
        $level1      = $level2 = $level3 = 0;
        $level1      = pdo_fetchcolumn('select count(*) from ' . tablename('superdesk_shop_member') . ' where agentid=:agentid and uniacid=:uniacid limit 1', array(':agentid' => $member['id'], ':uniacid' => $_W['uniacid']));

        if ((2 <= $this->set['level']) && (0 < $levelcount1)) {
            $level2 = pdo_fetchcolumn('select count(*) from ' . tablename('superdesk_shop_member') . ' where agentid in( ' . implode(',', array_keys($member['level1_agentids'])) . ') and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
        }


        if ((3 <= $this->set['level']) && (0 < $levelcount2)) {
            $level3 = pdo_fetchcolumn('select count(*) from ' . tablename('superdesk_shop_member') . ' where agentid in( ' . implode(',', array_keys($member['level2_agentids'])) . ') and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
        }


        $total = $level1 + $level2 + $level3;
        include $this->template();
    }

    public function get_list()
    {
        global $_W;
        global $_GPC;
        $openid      = $_W['openid'];
        $member      = $this->model->getInfo($openid);
        $total_level = 0;
        $level       = intval($_GPC['level']);
        ((3 < $level) || ($level <= 0)) && ($level = 1);
        $condition   = '';
        $levelcount1 = $member['level1'];
        $levelcount2 = $member['level2'];
        $levelcount3 = $member['level3'];
        $pindex      = max(1, intval($_GPC['page']));
        $psize       = 20;

        if ($level == 1) {
            $condition   = ' and agentid=' . $member['id'];
            $hasangent   = true;
            $total_level = pdo_fetchcolumn('select count(*) from ' . tablename('superdesk_shop_member') . ' where agentid=:agentid and uniacid=:uniacid limit 1', array(':agentid' => $member['id'], ':uniacid' => $_W['uniacid']));
        } else if ($level == 2) {
            if (empty($levelcount1)) {
                show_json(1, array(
                    'list'     => array(),
                    'total'    => 0,
                    'pagesize' => $psize
                ));
            }


            $condition   = ' and agentid in( ' . implode(',', array_keys($member['level1_agentids'])) . ')';
            $hasangent   = true;
            $total_level = pdo_fetchcolumn('select count(*) from ' . tablename('superdesk_shop_member') . ' where agentid in( ' . implode(',', array_keys($member['level1_agentids'])) . ') and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
        } else if ($level == 3) {
            if (empty($levelcount2)) {
                show_json(1, array(
                    'list'     => array(),
                    'total'    => 0,
                    'pagesize' => $psize
                ));
            }


            $condition   = ' and agentid in( ' . implode(',', array_keys($member['level2_agentids'])) . ')';
            $hasangent   = true;
            $total_level = pdo_fetchcolumn('select count(*) from ' . tablename('superdesk_shop_member') . ' where agentid in( ' . implode(',', array_keys($member['level2_agentids'])) . ') and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));
        }


        $list = pdo_fetchall('select * from ' . tablename('superdesk_shop_member') . ' where uniacid = ' . $_W['uniacid'] . ' ' . $condition . '  ORDER BY isagent desc,id desc limit ' . (($pindex - 1) * $psize) . ',' . $psize);

        foreach ($list as &$row) {
            if ($member['isagent'] && $member['status']) {
                $info                    = $this->model->getInfo($row['openid'], array('total'));
                $row['commission_total'] = $info['commission_total'];
                $row['agentcount']       = $info['agentcount'];
                $row['agenttime']        = date('Y-m-d H:i', $row['agenttime']);
            }


            $ordercount = pdo_fetchcolumn(
                ' select count(id) from ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where openid=:openid and core_user=:core_user and uniacid=:uniacid limit 1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $row['openid'],
                    ':core_user' => $row['core_user'],
                )
            );

            $row['ordercount'] = number_format(intval($ordercount), 0);
            $moneycount        = pdo_fetchcolumn(
                ' select sum(og.realprice) from ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 已处理
                ' left join ' . tablename('superdesk_shop_order') . ' o on og.orderid=o.id ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
                ' where o.openid=:openid and core_user=:core_user  and o.status>=1 and o.uniacid=:uniacid limit 1',
                array(
                    ':uniacid'   => $_W['uniacid'],
                    ':openid'    => $row['openid'],
                    ':core_user' => $row['core_user'],
                )
            );

            $row['moneycount'] = number_format(floatval($moneycount), 2);
            $row['createtime'] = date('Y-m-d H:i', $row['createtime']);
        }

        unset($row);
        show_json(1, array('list' => $list, 'total' => $total_level, 'pagesize' => $psize));
    }
}