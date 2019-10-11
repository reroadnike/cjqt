<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Member_cost_SuperdeskShopV2Page extends WebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $condition = ' and o.uniacid=' . $_W['uniacid'];

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $params = array();

        $shop = m('common')->getSysset('shop');

        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime   = time();
        }

        if (!empty($_GPC['datetime']['start']) && !empty($_GPC['datetime']['end'])) {
            $starttime = strtotime($_GPC['datetime']['start']);
            $endtime   = strtotime($_GPC['datetime']['end']);
            $condition .= ' AND o.createtime >=' . $starttime . ' AND o.createtime <= ' . $endtime . ' ';
        }


        $condition1 = ' and m.uniacid=:uniacid';
        $params1    = array(':uniacid' => $_W['uniacid']);

        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword']     = trim($_GPC['keyword']);
            $condition1          .= ' and ( m.realname like :keyword or m.mobile like :keyword or m.nickname like :keyword)';
            $params1[':keyword'] = '%' . $_GPC['keyword'] . '%';
        }


//		$orderby = (empty($_GPC['orderby']) ? 'ordermoney' : 'ordercount'); // 错的
        $orderby = empty($_GPC['orderby']) ? 'ordermoney' : $_GPC['orderby'];

        // mark welfare
        $leftJoinSql = '';
        $selectSql   = '';

        switch (SUPERDESK_SHOPV2_MODE_USER) {
            case 1:// 1 超级前台
                $selectSql   = ' core_enterprise.name as enterprise_name, ';
                $leftJoinSql = ' left join ' . tablename('superdesk_core_virtualarchitecture') . ' core_enterprise on core_enterprise.id = m.core_enterprise ';
                break;
            case 2:// 2 福利商城
                $selectSql   = ' core_enterprise.enterprise_name, ';
                $leftJoinSql = ' left join ' . tablename('superdesk_shop_enterprise_user') . ' core_enterprise on core_enterprise.id = m.core_enterprise ';
                break;
        }

        $sql =
            ' SELECT m.realname, m.mobile,m.avatar,m.nickname,m.openid,l.levelname,' .
            $selectSql .
            '(select ifnull( count(o.id) ,0) ' .
            ' from  ' . tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where o.openid=m.openid and o.core_user=m.core_user and o.status>=1 ' . $condition . ')  as ordercount,' .
            '(select ifnull(sum(o.price),0) ' .
            ' from  ' . tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where o.openid=m.openid and o.core_user=m.core_user and o.status>=1 ' . $condition . ')  as ordermoney' .
            ' from ' . tablename('superdesk_shop_member') . ' m  ' .
            ' left join ' . tablename('superdesk_shop_member_level') . ' l on l.id = m.level' .
            $leftJoinSql .
            ' where 1 ' .
            $condition1 .
            ' order by ' .
            $orderby . ' desc';

        if (empty($_GPC['export'])) {
            $sql .= ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
        }


        $list  = pdo_fetchall($sql, $params1);

        $total = pdo_fetchcolumn(
            'select  count(1) '.
            ' from ' . tablename('superdesk_shop_member') . ' m ' .
            ' where 1 ' .
            $condition1 . ' ',
            $params1
        );

        $pager = pagination($total, $pindex, $psize);

        if ($_GPC['export'] == 1) {
            ca('statistics.member_cost.export');
            m('excel')->export($list, array(
                'title'   => '会员消费排行报告-' . date('Y-m-d-H-i', time()),
                'columns' => array(
                    array('title' => '昵称', 'field' => 'nickname', 'width' => 12),
                    array('title' => '姓名', 'field' => 'realname', 'width' => 12),
                    array('title' => '手机号', 'field' => 'mobile', 'width' => 12),
                    array('title' => 'openid', 'field' => 'openid', 'width' => 24),
                    array('title' => '企业名称', 'field' => 'enterprise_name', 'width' => 24),
                    array('title' => '消费金额', 'field' => 'ordermoney', 'width' => 12),
                    array('title' => '订单数', 'field' => 'ordercount', 'width' => 12)
                )
            ));
            plog('statistics.member_cost.export', '导出会员消费排行');
        }


        load()->func('tpl');
        include $this->template('statistics/member_cost');
    }
}


