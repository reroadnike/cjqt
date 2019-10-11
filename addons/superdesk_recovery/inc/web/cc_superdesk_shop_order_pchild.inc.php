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

if ($op == 'list') {

    $page      = $_GPC['page'] ? $_GPC['page'] : 1;
    $page_size = 1000;

    $params = array(
        ':uniacid' => $_W['uniacid'],
        ':merchid' => SUPERDESK_SHOPV2_JD_VOP_MERCHID
    );

    $parent_list = pdo_fetchall(
        ' select p.id,p.ordersn,p.expresssn,p.price,p.dispatchtype,p.isverify,p.virtual,p.isvirtual,p.carrier,p.address,p.createtime,a.realname as arealname,a.mobile as amobile '.
        ' from '. tablename('superdesk_shop_order') . ' p ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
        ' left join ' . tablename('superdesk_shop_member_address') . ' a on a.id=p.addressid ' .
        ' where p.uniacid=:uniacid '.
        '       and p.parentid=0 '.
        '       and p.merchid=:merchid '.
        '       and ' .
        '        p.price!=(select sum(sumprice) from
		                                        (select c.price as sumprice,c.parentid,c.deleted
		                                         from `ims_superdesk_shop_order` c
		                                         join ims_superdesk_shop_order_goods as og on og.orderid = c.id
		                                         GROUP BY c.id) as sp
		                                         where sp.parentid=p.id and sp.deleted=0
		                                         ) ' .
//		                                         and
//		         p.price != (select sum(sumprice) from
//		                                        (select c.price as sumprice,c.parentid
//		                                         from `ims_superdesk_shop_order` c
//		                                         join ims_superdesk_shop_order_goods as og on og.orderid = c.id
//		                                         GROUP BY c.id) as sp
//		                                         where sp.parentid=p.id
//		        ) + 6' .
        ' order by p.id desc limit ' . ($page - 1) * $page_size . ',' . $page_size,
        $params
    );

    $parent_count = pdo_fetchcolumn(
        ' select count(*) from '. tablename('superdesk_shop_order') . ' p ' .// TODO 标志 楼宇之窗 openid shop_order 不处理
        ' where uniacid=:uniacid and parentid=0 and merchid=:merchid and ' .
        '        p.price!=(select sum(sumprice) from
		                                        (select c.price as sumprice,c.parentid
		                                         from `ims_superdesk_shop_order` c
		                                         join ims_superdesk_shop_order_goods as og on og.orderid = c.id
		                                         GROUP BY c.id) as sp
		                                         where sp.parentid=p.id
		                                         ) ',
        $params
    );

    if(count($parent_list) > 0){
        $parent_id_arr = array_column($parent_list,'id');
        $parent_id_arr = implode(',',$parent_id_arr);
        $child_list = pdo_fetchall(
            ' select id,ordersn,expresssn,parentid,price from '. tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' where parentid in ('.$parent_id_arr.') and deleted=0 '
        );

        foreach($parent_list as $pk => &$pv){
            $sumprice = 0;
            foreach($child_list as $ck => $cv){
                if($pv['id'] == $cv['parentid']){
                    $pv['child'][] = $cv;
                    $sumprice += $cv['price'];
                }
            }
            $pv['sumprice'] = $sumprice;

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
    }

    $total     = $parent_count;
    $list      = $parent_list;

    $pager = pagination($total, $page, $page_size);

    include $this->template('cc_superdesk_shop_order_pchild_list');

}
