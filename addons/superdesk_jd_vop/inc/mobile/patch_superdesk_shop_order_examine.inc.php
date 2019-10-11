<?php
/**
 * 数据补丁 修正 审核的订单 京东拆单后显示 未审核 问题
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/25/19
 * Time: 5:24 PM
 *
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_examine
 */


// 找出 京东 拆单 记录

global $_GPC, $_W;

$result_order = pdo_fetchall(
    ' SELECT o.* ' .
    ' FROM ' . tablename('superdesk_shop_order') . ' o ' .
//    '   	left join ' . tablename('superdesk_shop_order_examine') . ' oe ON oe.orderid = o.id' .
    ' WHERE 1 ' .
    '       and o.uniacid=:uniacid' .
    '       AND o.isparent = 0 ' .
    '       AND o.deleted = 1 ' .
    '       AND o.express = \'jd\' ' .
    '       AND o.status >1 ',

    array(
        ':uniacid' => $_W['uniacid']
    )
);

print_r($result_order);



//include $this->template('patch_superdesk_shop_order_examine_list');

//SELECT * FROM `ims_superdesk_shop_order` WHERE ordersn = 'ME20181121170701096042'

//18020
//
//oX8KYwuk_Y_72gkqeDjmGr6B80qI