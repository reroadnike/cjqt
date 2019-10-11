<?php
/**
 *
 * 修正商城订单merchshow的值
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/15/17
 * Time: 1:56 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_merchshow_error
 *
 * http://wxn.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_merchshow_error
 */


global $_GPC, $_W;

$page      = isset($_GPC['page']) && !empty($_GPC['page']) ? $_GPC['page'] : 1;
$page_size = isset($_GPC['page_size']) && !empty($_GPC['page_size']) ? $_GPC['page_size'] : 100;

$orderList = pdo_fetchall(
    ' SELECT id,parentid,isparent,merchshow,status,paytype,merchid,FROM_UNIXTIME(createtime) '.
    ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
    ' WHERE uniacid=:uniacid '.
    '       and merchshow = 0 ' .   //标识位错误的
    '       and isparent = 1 ' .    //为父单的
    '       and createtime > 1545580800 ' .     //该时间节点为2018-12-24 00:00:00
    '       and id in ( ' .
    '            select parentid ' .
    '            from ims_superdesk_shop_order ' .
    '            where uniacid=:uniacid ' .
    '              and paytype = 11 ' .
    '              and merchshow = 1 ' .
    '       ) ' .  //由于后台确认支付是操作的子单.所以需要先找到后台支付的子单.
    ' limit ' . (($page - 1) * $page_size) . ',' . $page_size,
    array(
        ':uniacid' => $_W['uniacid']
    )
);

if(count($orderList) > 0){
    foreach ($orderList as &$item){

        $orderListChild = pdo_fetchall(
            ' SELECT id,parentid,isparent,merchshow,status,paytype,merchid,FROM_UNIXTIME(createtime) '.
            ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
            ' WHERE uniacid=:uniacid '.
            '       and paytype = 11 ' .    //为后台确认支付的
            '       and merchshow = 1 ' .   //标识位错误的
            '       and parentid = :parentid ' ,    //为该单的子单
            array(
                ':uniacid' => $_W['uniacid'],
                ':parentid' => $item['id']
            )
        );

        $item['child'] = $orderListChild;

        //更新父单
        pdo_update(
            'superdesk_shop_order',// TODO 标志 楼宇之窗 openid shop_order 不处理
            array(
                'merchshow' => 1
            ),
            array(
                'id'      => $item['id'],
                'uniacid' => $_W['uniacid']
            )
        );

        //更新所有子单
        pdo_update(
            'superdesk_shop_order',// TODO 标志 楼宇之窗 openid shop_order 不处理
            array(
                'merchshow' => 0,
                'status'   => $item['status']
            ),
            array(
                'parentid' => $item['id'],
                'merchshow' => 1,
                'uniacid'  => $_W['uniacid']
            )
        );
    }
}

print_r($orderList);
die;