<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class History_SuperdeskShopV2Page extends MobileLoginPage
{
    public function main()
    {
        global $_W;
        global $_GPC;

        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if ($merch_plugin && $merch_data['is_openmerch']) {
            include $this->template('merch/member/history');
            return NULL;
        }

        include $this->template();
    }

    public function get_list()
    {
        global $_W;
        global $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 10;

        $condition =
            ' and f.uniacid = :uniacid ' .
            ' and f.openid=:openid ' .
            ' and f.core_user=:core_user ' .
            ' and f.deleted=0';

        $params = array(
            ':uniacid'   => $_W['uniacid'],
            ':openid'    => $_W['openid'],
            ':core_user' => $_W['core_user']
        );

        $sql   = 'SELECT COUNT(*) ' .
            ' FROM ' . tablename('superdesk_shop_member_history') . ' f ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_history 已处理
            ' where 1 ' .
            $condition;

        $total = pdo_fetchcolumn($sql, $params);

        $sql  = 'SELECT ' .
            '       f.id,f.goodsid,g.title,g.thumb,g.marketprice,g.productprice,f.createtime,g.merchid,g.tcate,g.costprice ' .
            ' FROM ' . tablename('superdesk_shop_member_history') . ' f ' . // TODO 标志 楼宇之窗 openid superdesk_shop_member_history 已处理
            '       left join ' . tablename('superdesk_shop_goods') . ' g on f.goodsid = g.id ' .
            ' where 1 ' .
            $condition .
            ' ORDER BY `id` DESC ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize;

        $list = pdo_fetchall($sql, $params);

        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');

        if (!empty($list) && $merch_plugin && $merch_data['is_openmerch']) {
            $merch_user = $merch_plugin->getListUser($list, 'merch_user');
        }

        foreach ($list as &$row) {
            $row['createtime'] = date('Y-m-d H:i:s', $row['createtime']);
            $row['thumb']      = tomedia($row['thumb']);
            $row['merchname']  = ($merch_user[$row['merchid']]['merchname'] ? $merch_user[$row['merchid']]['merchname'] : $_W['shopset']['shop']['name']);
        }

        unset($row);

        //2019年5月24日 16:48:51 zjh 文礼 价套
        $list = m('goods')->getGoodsCategoryEnterpriseDiscount($list);

        show_json(1, array(
            'list'     => $list,
            'total'    => $total,
            'pagesize' => $psize
        ));
    }

    public function remove()
    {
        global $_W;
        global $_GPC;

        $ids = $_GPC['ids'];

        if (empty($ids) || !is_array($ids)) {
            show_json(0, '参数错误');
        }

        $sql = 'update ' . tablename('superdesk_shop_member_history') . // TODO 标志 楼宇之窗 openid superdesk_shop_member_history 已处理
            ' set ' .
            '       deleted=1 ' .
            ' where ' .
            '       openid=:openid ' .
            '       core_user=:core_user ' .
            '       and id in (' . implode(',', $ids) . ')';

        pdo_query(
            $sql,
            array(
                ':openid'    => $_W['openid'],
                ':core_user' => $_W['core_user'],
            )
        );

        show_json(1);
    }
}