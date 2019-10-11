<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Index_SuperdeskShopV2Page extends MobileLoginPage
{
    protected function merchData()
    {
        $merch_plugin = p('merch');
        $merch_data   = m('common')->getPluginset('merch');
        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        } else {
            $is_openmerch = 0;
        }
        return array('is_openmerch' => $is_openmerch, 'merch_plugin' => $merch_plugin, 'merch_data' => $merch_data);
    }

    public function main()
    {
        global $_W;
        global $_GPC;
        $trade = m('common')->getSysset('trade');

        include $this->template();
    }

    public function get_list()
    {
        global $_W;
        global $_GPC;

        $uniacid = $_W['uniacid'];

        $pindex = max(1, intval($_GPC['page']));
        $psize  = 50;

        $show_status = $_GPC['status'];

        $member = m('member')->getMember($_W['openid'], $_W['core_user']);

        $condition =
            ' and oe.uniacid=:uniacid ' .
            ' and oe.parent_order_id=0 ' .
            ' and oe.enterprise=:enterprise ';

        $params = array(
            ':uniacid'    => $uniacid,
            ':enterprise' => $member['core_enterprise'],
        );

        $merchdata = $this->merchData();
        extract($merchdata);

        if ($show_status != '') {
            switch ($show_status) {
                case 0 :
                    $condition .= ' and (oe.status = -1 or oe.status = 0) ';
                    break;
                case 1 :
                    $condition .= ' and oe.status = 1 ';
                    break;
                case 2 :
                    $condition .= ' and oe.status = 2 ';
                    break;
            }
        }

        $list = pdo_fetchall(
            ' select o.id,o.ordersn,o.price,o.isparent,oe.status,oe.id as examine_id' .
            ' from ' . tablename('superdesk_shop_order_examine') . ' oe ' .// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 不处理
            ' left join ' . tablename('superdesk_shop_order') . ' o on o.id = oe.orderid ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where 1 ' .
            $condition .
            ' order by oe.createtime desc ' .
            ' LIMIT ' . (($pindex - 1) * $psize) . ',' . $psize, $params);

        $total = pdo_fetchcolumn(
            'select count(*) ' .
            ' from ' . tablename('superdesk_shop_order_examine') . ' oe ' .// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 不处理
            ' where 1 ' .
            $condition,
            $params
        );

        if ($is_openmerch == 1) {
            $merch_user = $merch_plugin->getListUser($list, 'merch_user');
        }

        foreach ($list as &$row) {

            $param = array();

            if ($row['isparent'] == 1) {
                $scondition              = ' og.parentorderid=:parentorderid';
                $param[':parentorderid'] = $row['id'];
            } else {
                $scondition        = ' og.orderid=:orderid';
                $param[':orderid'] = $row['id'];
            }

            $sql   =
                ' SELECT og.goodsid,og.total,g.title,g.thumb,og.price,og.optionname as optiontitle,og.optionid,op.specs ' .
                ' FROM ' . tablename('superdesk_shop_order_goods') . ' og ' .// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
                '       left join ' . tablename('superdesk_shop_goods') . ' g on og.goodsid = g.id ' .
                '       left join ' . tablename('superdesk_shop_goods_option') . ' op on og.optionid = op.id ' .
                ' where ' .
                $scondition .
                ' order by og.id asc';
            $goods = pdo_fetchall($sql, $param);


            // Fix is not array
            is_array($goods) ? null : $goods = array();


            foreach ($goods as &$r) {
                if (!empty($r['specs'])) {
                    $thumb = m('goods')->getSpecThumb($r['specs']);
                    if (!empty($thumb)) {
                        $r['thumb'] = $thumb;
                    }
                }
            }
            unset($r);

            $row['goods'] = set_medias($goods, 'thumb');

            // Fix is not array
            is_array($row['goods']) ? null : $row['goods'] = array();

            foreach ($row['goods'] as &$r) {
                $r['thumb'] .= '?t=' . random(50);
            }

            unset($r);

            $statuscss = 'text-cancel';

            switch ($row['status']) {
                case '0':
                    $status    = '未审核';
                    $statuscss = 'text-cancel';
                    break;
                case '1':
                    $status    = '已通过';
                    $statuscss = 'text-success';
                    break;
                case '2':
                    $status    = '不通过';
                    $statuscss = 'text-danger';
                    break;
                default :
                    $status    = 'error';
                    $statuscss = 'text-warning';
            }

            $row['statusstr'] = $status;
            $row['statuscss'] = $statuscss;


            if ($is_openmerch == 1) {
                $row['merchname'] = ($merch_user[$row['merchid']]['merchname'] ? $merch_user[$row['merchid']]['merchname'] : $_W['shopset']['shop']['name']);
            }
        }

        unset($row);

        show_json(1, array('list' => $list, 'pagesize' => $psize, 'total' => $total));
    }
}

