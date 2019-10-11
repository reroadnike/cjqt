<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 4/3/18
 * Time: 4:19 PM
 *
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_jd_vop_order_submit_order_sku
 * view-source:http://www.avic-s.com/plugins/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_jd_vop_order_submit_order_sku
 */

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order.class.php');
$_orderModel = new orderModel();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
$_goodsModel = new goodsModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/order_submit_order.class.php');
$_order_submit_orderModel     = new order_submit_orderModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/order_submit_order_sku.class.php');
$_order_submit_order_skuModel = new order_submit_order_skuModel();



$result = $_order_submit_order_skuModel->queryAll(array(
    'shop_order_id' => 0,
    'shop_goods_id' => 0
), 1, 9999);

$source_total     = $result['total'];
$source_page      = $result['page'];
$source_page_size = $result['page_size'];
$source_list      = $result['data'];

echo "正处理:" . $source_total;
echo PHP_EOL;

//{
//    "id": "2",
//    "pOrder": "",
//    "jdOrderId": "70430984204",
//    "skuId": "5326765",
//    "num": "1",
//    "category": "4837",
//    "price": "15.00",
//    "name": "晨光（M&G）炫彩订书机12号颜色随机ABS91641",
//    "tax": "17",
//    "taxPrice": "2.18",
//    "nakedPrice": "12.82",
//    "type": "0",
//    "oid": "0",
//    "shop_order_id": "0",
//    "shop_order_sn": "",
//    "shop_goods_id": "0",
//    "return_goods_nun": "0",
//    "return_goods_result": ""
//}
foreach ($source_list as $index => $item) {
    echo json_encode($item, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo PHP_EOL;

    $_jd_vop_submit_order = $_order_submit_orderModel->getOneByColumn(array(
        'jd_vop_result_jdOrderId' => $item['jdOrderId']
    ));

    $_shop_order = $_orderModel->getOneByColumn(array(
        'ordersn'   => $_jd_vop_submit_order['thirdOrder'],
        'id'        => $_jd_vop_submit_order['order_id'],
        'expresssn' => $item['jdOrderId']
    ));

    $_jd_vop_submit_order_sku['shop_order_id'] = $_shop_order['id'];
    $_jd_vop_submit_order_sku['shop_order_sn'] = $_shop_order['ordersn'];
    $_jd_vop_submit_order_sku['shop_goods_id'] = $_goodsModel->getGoodsIdBySkuId($item['skuId']);// 正常



    $_order_submit_order_skuModel->saveOrUpdateByColumn($_jd_vop_submit_order_sku, array(
        'jdOrderId' => $item['jdOrderId'],
        'skuId'     => $item['skuId']
    ));
}