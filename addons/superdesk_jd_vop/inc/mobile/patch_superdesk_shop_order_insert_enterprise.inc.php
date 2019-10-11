<?php
/**
 * Created by PhpStorm.
 * User: zjh
 * Date: 2018年9月8日 15:04:02
 *
 * 企业采购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_insert_enterprise
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_insert_enterprise
 */

global $_W;
global $_GPC;

/**
 * 这个sql呢..就是订单表连会员表然后连企业表.然后将企业表中的名称更新到订单表中的企业名称中 为了保险起见...还是从程序上面做...
 * update ims_superdesk_shop_order as o
 * left join ims_superdesk_shop_member as m on m.openid = o.openid
 * left join ims_superdesk_shop_enterprise_user as eu on eu.id = m.core_enterprise
 * set o.member_enterprise_name = eu.enterprise_name where o.member_enterprise_name = ''
 */


//这玩意是测试一下怎么规范京东返回提示语的。。
//resultMessage [5729524]商品已下架，不能下单 ;
//                    resultMessage [586565]商品是品类限购，不能下单 ;
//resultMessage 编号为2586166的赠品无货，主商品为:929693;
//                    resultMessage 编号为7788763的商品无货;
//$testStr = $_GPC['test'] == 1 ? '编号为2586166的赠品无货，主商品为:1990699;' : '编号为1990699的商品无货;';
//print_r(strpos($testStr,'赠'));

//die();

//正在显示第 0 - 24 行 (共 6122 行, 查询花费 0.0010 秒。)
//SELECT * FROM ims_superdesk_shop_order WHERE member_enterprise_name =''


$page      = isset($_GPC['page']) && !empty($_GPC['page']) ? $_GPC['page'] : 1;
$page_size = isset($_GPC['page_size']) && !empty($_GPC['page_size']) ? $_GPC['page_size'] : 6122;

// mark welfare
$leftjoin = '';
switch (SUPERDESK_SHOPV2_MODE_USER) {
    case 1:// 1 超级前台
        $select   = 'core_enterprise.name as enterprise_name,core_enterprise.organizationId ';
        $leftjoin = ' left join ' . tablename('superdesk_core_virtualarchitecture') . ' as core_enterprise on core_enterprise.id = m.core_enterprise ';
        break;
    case 2:// 2 福利商城
        $select   = 'core_enterprise.enterprise_name,0 as organizationId';
        $leftjoin = ' left join ' . tablename('superdesk_shop_enterprise_user') . ' as core_enterprise on core_enterprise.id = m.core_enterprise ';
        break;
}

$orderList = pdo_fetchall(
    ' select o.id,m.core_enterprise,' . $select . ' from ' . tablename('superdesk_shop_order') . ' as o ' .// TODO 标志 楼宇之窗 openid shop_order 已处理
    ' left join ' . tablename('superdesk_shop_member') . ' as m on m.openid = o.openid and m.core_user = o.core_user ' .
    $leftjoin .
    ' where o.uniacid=:uniacid ' .
//    '   and o.member_enterprise_name = "" '.    //订单表中还没有插入该值的
    '   and o.member_enterprise_id = 0 ' .    //订单表中还没有插入该值的
    '   and o.member_organization_id = 0 ' .    //订单表中还没有插入该值的
    '   and m.core_user > 0 ' .   //没有该会员的,或者会员表中没有企业的
    '   and m.core_enterprise > 0 ' .   //没有该会员的,或者会员表中没有企业的
    '   and m.core_organization > 0 ' .   //没有该会员的,或者会员表中没有企业的
    ' limit ' . (($page - 1) * $page_size) . ',' . $page_size,
    array(':uniacid' => $_W['uniacid'])
);

if (count($orderList) > 0) {
    foreach ($orderList as $v) {
        pdo_update('superdesk_shop_order',// TODO 标志 楼宇之窗 openid shop_order 不处理
            array(
                'member_enterprise_name' => $v['enterprise_name'],
                'member_enterprise_id'   => $v['core_enterprise'],
                'member_organization_id' => $v['organizationId'],
            ), array('id' => $v['id']));
    }
}

show_json(1, $orderList);