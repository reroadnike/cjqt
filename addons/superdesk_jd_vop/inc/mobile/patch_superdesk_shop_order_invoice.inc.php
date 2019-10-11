<?php
/**
 *
 * 修正商城订单invoice字段值
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/15/17
 * Time: 1:56 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_invoice
 *
 * http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_invoice
 */


// 补丁前请运行
// ALTER TABLE `ims_superdesk_shop_order` CHANGE `invoicename` `invoice` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `invoiceid`;
global $_GPC, $_W;

$list_fix_invoice = pdo_fetchall(
    ' SELECT * '.
    ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
    ' WHERE invoiceid <> 0 '.
    '       and uniacid=:uniacid',
    array(
        ':uniacid' => $_W['uniacid']
    )
);


print_r($list_fix_invoice);

foreach ($list_fix_invoice as $item){
    
    $user_invoice = pdo_fetch(
        ' SELECT * ' .
        ' FROM ' . tablename('superdesk_shop_member_invoice') .
        ' WHERE id = :id ' .
        '       and uniacid=:uniacid',
        array(
            ':id'      => $item['invoiceid'],
            ':uniacid' => $_W['uniacid']
        )
    );


    pdo_update(
        'superdesk_shop_order',// TODO 标志 楼宇之窗 openid shop_order 不处理
        array(
            'invoice' => iserializer($user_invoice)
        ),
        array(
            'id' => $item['id']
        )
    );
}