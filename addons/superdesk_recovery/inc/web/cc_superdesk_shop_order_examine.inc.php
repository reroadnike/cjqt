<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/05/15
 * Time: 15:09
 * http://192.168.1.124/smart_office_building/web/index.php?c=site&a=entry&m=superdesk_recovery&do=cc_superdesk_shop_goods_cc_sku
 */

global $_GPC, $_W;

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

$page      = $_GPC['page'] ? $_GPC['page'] : 1;
$page_size = 1000;

$condition =
    ' where '.
    '   o.uniacid=:uniacid '.
    '   and o.paytype=3 './*企业月结*/
    '   and o.status=0 '. /*待发货*/
    '   and oe.id is null ' /*没有审核记录*/;
$params = array(
    ':uniacid' => $_W['uniacid']
);



$createtime = $_GPC['createtime'];
if (!empty($createtime)) {
    $starttime = strtotime($_GPC['createtime']['start']);
    $endtime   = strtotime($_GPC['createtime']['end']);
    $condition .= ' AND o.createtime >= :starttime AND o.createtime <= :endtime ';
    $params[':starttime'] = $starttime;
    $params[':endtime']   = $endtime;
}

if ($op == 'list') {

    $list = pdo_fetchall(
        ' select o.id,o.ordersn,o.expresssn,o.price,o.dispatchtype,o.isverify,o.virtual,o.isvirtual,o.carrier,o.address,o.createtime, '.
        '        a.realname as arealname,a.mobile as amobile,mu.merchname,core_enterprise.name as enterprise_name '.
        ' from '. tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
        ' left join ' . tablename('superdesk_shop_member_address') . ' a on a.id=o.addressid ' .
        ' left join ' . tablename('superdesk_shop_order_examine') . ' oe on oe.orderid=o.id ' .// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 已处理
        ' left join '. tablename('superdesk_shop_merch_user') . ' as mu on mu.id = o.merchid '.
        ' left join ' . tablename('superdesk_shop_member') . ' m on m.openid = o.openid and m.core_user = o.core_user ' .
        ' left join ' . tablename('superdesk_core_virtualarchitecture') . ' core_enterprise on core_enterprise.id = m.core_enterprise ' .
        $condition.
        ' order by o.id desc limit ' . ($page - 1) * $page_size . ',' . $page_size,
        $params
    );

    $count = pdo_fetchcolumn(
        ' select count(*) '.
        ' from '. tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
        ' left join ' . tablename('superdesk_shop_member_address') . ' a on a.id=o.addressid ' .
        ' left join ' . tablename('superdesk_shop_order_examine') . ' oe on oe.orderid=o.id ' .// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 不处理
        $condition,
        $params
    );

    foreach($list as $pk => &$pv){
        $pv['createtime'] = date('Y-m-d H:i:s',$pv['createtime']);
        if (($pv['dispatchtype'] == 1) || !empty($pv['isverify']) || !empty($pv['virtual']) || !empty($pv['isvirtual'])) {
            $carrier          = iunserializer($pv['carrier']);
            if (is_array($carrier)) {
                $pv['arealname']  = $carrier['carrier_realname'];
                $pv['amobile']    = $carrier['carrier_mobile'];
            }
        } else {
            $address = iunserializer($pv['address']);
            $isarray = is_array($address);

            $pv['arealname'] = (($isarray ? $address['realname'] : $pv['arealname']));
            $pv['amobile']   = (($isarray ? $address['mobile'] : $pv['amobile']));
        }
    }

    $pager = pagination($total, $page, $page_size);

    include $this->template('cc_superdesk_shop_order_examine_list');

}else if ($op == 'compensate'){
    $list = pdo_fetchall(
        ' select o.* '.
        ' from '. tablename('superdesk_shop_order') . ' o ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
        ' left join ' . tablename('superdesk_shop_order_examine') . ' oe on oe.orderid=o.id ' .// TODO 标志 楼宇之窗 openid superdesk_shop_order_examine 不处理
        $condition,
        $params
    );

    foreach($list as $k => $v){
        m('examine')->addExamine($v);
    }

}
