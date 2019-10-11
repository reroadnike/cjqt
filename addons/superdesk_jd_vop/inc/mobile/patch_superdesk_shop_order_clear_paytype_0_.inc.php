<?php
/**
 *
 * 删除 为未支付 的 0普通状态(没付款: 待付款 ; 付了款: 待发货) 订单
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/15/17
 * Time: 1:56 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_clear_paytype_0_
 */
global $_GPC, $_W;

$list_delete = pdo_fetchall(
    ' SELECT * '.
    ' FROM ' . tablename('superdesk_shop_order') .// TODO 标志 楼宇之窗 openid shop_order 不处理
    ' WHERE status = 0 '.
    '       and paytype=0'.
    '       and uniacid=:uniacid',
    array(
        ':uniacid' => $_W['uniacid']
    )
);

//paytype	支付类型 0为未支付 1为余额支付 2在线支付 3 货到付款 11 后台付款 21 微信支付 22 支付宝支付 23 银联支付

//status	状态 -1取消状态（交易关闭），0普通状态（没付款: 待付款 ; 付了款: 待发货），1 买家已付款（待发货），2 卖家已发货（待收货），3 成功（可评价: 等待评价 ; 不可评价 : 交易完成）4 退款申请



print_r($list_delete);

foreach ($list_delete as $item){
    pdo_delete('superdesk_shop_order_refund', array('orderid' => $item['id']));
    pdo_delete('superdesk_shop_order_goods', array('orderid' => $item['id']));// TODO 标志 楼宇之窗 openid shop_order_goods 不处理
    pdo_delete('superdesk_shop_order', array('id' => $item['id']));// TODO 标志 楼宇之窗 openid shop_order 不处理
}