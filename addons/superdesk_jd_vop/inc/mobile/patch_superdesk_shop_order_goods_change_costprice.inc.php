<?php
/**
 *
 * 修复订单商品表中的成本价
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/15/17
 * Time: 1:56 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_order_goods_change_costprice
 */
global $_GPC, $_W;

$goodsList = pdo_fetchall(
    ' SELECT og.*,goption.costprice as option_costprice '.
    ' FROM ' . tablename('superdesk_shop_order_goods') . ' as og ' .
    ' LEFT JOIN ' . tablename('superdesk_shop_goods_option') . ' as goption on goption.id = og.optionid ' .
    ' WHERE og.uniacid=:uniacid '.
    '   AND og.optionid > 0 ' . //订单商品表中有规格的
    '   AND goption.costprice > 0 ' .   //规格表中的成本价大于0的
    '   AND og.costprice != goption.costprice ',    //订单商品表中的成本价跟规格表中的成本价不一样的
    array(
        ':uniacid' => $_W['uniacid']
    )
);

print_r($goodsList);

foreach ($goodsList as $item){

    pdo_update(
        'superdesk_shop_order_goods',
        array(
            'costprice' => $item['option_costprice']
        ),
        array(
            'id' => $item['id']
        )
    );
}