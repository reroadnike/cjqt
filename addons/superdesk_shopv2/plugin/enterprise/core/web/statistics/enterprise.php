<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Enterprise_SuperdeskShopV2Page extends PluginWebPage
{
    public function main()
    {
        global $_W;
        global $_GPC;
        $pindex    = max(1, intval($_GPC['page']));
        $psize     = 20;
        $condition = ' and eu.uniacid=:uniacid and o.`status`=3';
        $params    = array(':uniacid' => $_W['uniacid']);
        if (empty($starttime) || empty($endtime)) {
            $starttime = strtotime('-1 month');
            $endtime   = time();
        }


        if (!empty($_GPC['datetime']['start']) && !empty($_GPC['datetime']['end'])) {
            $starttime = strtotime($_GPC['datetime']['start']);
            $endtime   = strtotime($_GPC['datetime']['end']);
            $condition .= ' AND o.createtime >= :starttime AND o.createtime <= :endtime ';
            $params[':starttime'] = $starttime;
            $params[':endtime']   = $endtime;
        }


        if (!empty($_GPC['groupname'])) {
            $_GPC['groupname'] = intval($_GPC['groupname']);
            $condition .= ' and eu.groupid=:groupid';
            $params[':groupid'] = $_GPC['groupname'];
        }


        if (!empty($_GPC['realname'])) {
            $_GPC['realname'] = trim($_GPC['realname']);
            $condition .= ' and ( eu.enterprise_name like :realname or eu.mobile like :realname or eu.realname like :realname)';
            $params[':realname'] = '%' . $_GPC['realname'] . '%';
        }


        $sql =
            ' select eu.*,sum(o.price) price,sum(o.goodsprice) goodsprice,sum(o.dispatchprice) dispatchprice,sum(o.discountprice) discountprice,sum(o.deductprice) deductprice,sum(o.deductcredit2) deductcredit2,sum(o.isdiscountprice) isdiscountprice ' .
            ' from ' . tablename('superdesk_shop_enterprise_user') . ' eu ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on eu.id=m.core_enterprise' .
            ' left join ' . tablename('superdesk_shop_order') . ' o on o.openid=m.openid and o.core_user=m.core_user' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where 1 ' . $condition . ' GROUP BY eu.id ORDER BY eu.id DESC';

        if (empty($_GPC['export'])) {
            $sql .= ' limit ' . (($pindex - 1) * $psize) . ',' . $psize;
        }


        $list = pdo_fetchall($sql, $params);

        if ($_GPC['export'] == '1') {
            mplog('enterprise.statistics.enterprise', '导出企业数据');

            foreach ($list as &$row) {
                $row['realprice'] = $row['goodsprice'] + $row['dispatchprice'];
            }

            unset($row);
            m('excel')->export($list, array(
                'title'   => '企业数据-' . date('Y-m-d-H-i', time()),
                'columns' => array(
                    array('title' => '商城信息', 'field' => 'enterprise_name', 'width' => 12),
                    array('title' => '姓名', 'field' => 'realname', 'width' => 12),
                    array('title' => '手机号', 'field' => 'mobile', 'width' => 12),
                    array('title' => '订单应收', 'field' => 'realprice', 'width' => 12),
                    array('title' => '积分抵扣', 'field' => 'deductprice', 'width' => 12),
                    array('title' => '余额抵扣', 'field' => 'deductcredit2', 'width' => 12),
                    array('title' => '会员抵扣', 'field' => 'discountprice', 'width' => 12),
                    array('title' => '促销优惠', 'field' => 'isdiscountprice', 'width' => 12),
                    array('title' => '订单实收', 'field' => 'price', 'width' => 12)
                )
            ));
        }


        $total  = pdo_fetchcolumn(
            ' select COUNT(eu.id) from ' . tablename('superdesk_shop_enterprise_user') . ' eu ' .
            ' left join ' . tablename('superdesk_shop_member') . ' m on eu.id=m.core_enterprise' .
            ' left join ' . tablename('superdesk_shop_order') . ' o on o.openid=m.openid and o.core_user=m.core_user' .// TODO 标志 楼宇之窗 openid shop_order 已处理
            ' where 1 ' . $condition . ' GROUP BY eu.id',
            $params
        );
        $total  = count($total);
        $pager  = pagination($total, $pindex, $psize);
        $groups = $this->model->getGroups();
        include $this->template();
    }
}


?>