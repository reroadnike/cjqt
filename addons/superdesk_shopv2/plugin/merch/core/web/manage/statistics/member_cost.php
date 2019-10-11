<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}


require SUPERDESK_SHOPV2_PLUGIN . 'merch/core/inc/page_merch.php';

class Member_cost_SuperdeskShopV2Page extends MerchWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;


        $pindex = max(1, intval($_GPC['page']));
        $psize  = 20;

        $condition =
            ' and o.uniacid=' . $_W['uniacid'] . ' ' .
            ' and o.merchid=' . $_W['merchid'] . ' ' ;

        $params = array();

        $shop = m('common')->getSysset('shop');

        if (!empty($_GPC['datetime'])) {

            $starttime = strtotime($_GPC['datetime']['start']);
            $endtime   = strtotime($_GPC['datetime']['end']);

            $condition .=
                ' AND o.createtime >=' . $starttime .
                ' AND o.createtime <= ' . $endtime . ' ';
        }


        $condition1 =
            ' and m.uniacid=:uniacid ' .
            ' and m.core_user in ( SELECT distinct core_user from ' . tablename('superdesk_shop_order') . // TODO 标志 楼宇之窗 openid shop_order 已处理
            '                   WHERE uniacid =:uniacid and merchid=:merchid)';
//		$condition1 =
//			' and m.uniacid=:uniacid ' .
//			' and m.openid in ( SELECT distinct openid from ' . tablename('superdesk_shop_order') .
//			'                   WHERE uniacid =:uniacid and merchid=:merchid)';

        $params1 = array(':uniacid' => $_W['uniacid'], ':merchid' => $_W['merchid']);

        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword']     = trim($_GPC['keyword']);
            $condition1          .= ' and ( m.realname like :keyword or m.mobile like :keyword or m.nickname like :keyword)';
            $params1[':keyword'] = '%' . $_GPC['keyword'] . '%';
        }


        $orderby = ((empty($_GPC['orderby']) ? 'ordermoney' : 'ordercount'));

        $sql = 'SELECT m.realname, m.mobile,m.avatar,m.nickname,m.openid,m.core_user,l.levelname,' .
            '         (select ifnull( count(o.id) ,0) from  ' . tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            '          where o.openid=m.openid and o.core_user=m.core_user and o.status>=1 ' . $condition . ')  as ordercount,' .
            '         (select ifnull(sum(o.price),0) from  ' . tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            '          where o.openid=m.openid and o.core_user=m.core_user and o.status>=1 ' . $condition . ')  as ordermoney' .
            ' from ' . tablename('superdesk_shop_member') . ' m  ' .// TODO 标志 楼宇之窗 openid superdesk_shop_member 已处理
            ' left join ' . tablename('superdesk_shop_member_level') . ' l on l.id = m.level' .
            ' where 1 ' .
            $condition1 .
            ' order by ' . $orderby . ' desc';

        if (empty($_GPC['export'])) {
            $sql .= ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;
        }


        $list = pdo_fetchall($sql, $params1);

        $total = pdo_fetchcolumn(
            'select  count(1) ' .
            ' from ' . tablename('superdesk_shop_member') . ' m ' .// TODO 标志 楼宇之窗 openid superdesk_shop_member 已处理
            ' where 1 ' .
            $condition1 .
            ' ',
            $params1);

        $pager = pagination($total, $pindex, $psize);

        if ($_GPC['export'] == 1) {
            mca('statistics.member_cost.export');
            m('excel')->export($list, array(
                'title'   => '会员消费排行报告-' . date('Y-m-d-H-i', time()),
                'columns' => array(
                    array('title' => '昵称', 'field' => 'nickname', 'width' => 12),
                    array('title' => '姓名', 'field' => 'realname', 'width' => 12),
                    array('title' => '手机号', 'field' => 'mobile', 'width' => 12),
                    array('title' => 'openid', 'field' => 'openid', 'width' => 24),
                    array('title' => '消费金额', 'field' => 'ordermoney', 'width' => 12),
                    array('title' => '订单数', 'field' => 'ordercount', 'width' => 12)
                )
            ));
            mplog('statistics.member_cost.export', '导出会员消费排行');
        }

        load()->func('tpl');

        include $this->template('statistics/member_cost');
    }
}