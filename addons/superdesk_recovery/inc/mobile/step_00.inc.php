<?php
/**
 * 第一步:更新shop_order 10000以上记录
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 5/11/18
 * Time: 6:18 AM
 *
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_recovery&do=step_00
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_recovery&do=step_00&page=1
 *
 *
 */
global $_W, $_GPC;
//2018-04-20
//11:42:04

//2018-04-27
//10:25:05

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/order_submit_order_sku.class.php');
$_order_submit_order_skuModel = new order_submit_order_skuModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
$_goodsModel = new goodsModel();


//> SELECT * FROM ims_superdesk_jd_vop_order_submit_order_sku 3,415
//
//> skuId => shop_goods_id
//
//> shop_order_id shop_order_sn
//
//> price 用于检查 sku 有没有价格变动
//
//> price num jdOrderId name

//SELECT * FROM `ims_superdesk_shop_goods` WHERE updatetime > 1525996818
//DELETE FROM `ims_superdesk_shop_goods` WHERE updatetime > 1525996818

$where     = array();
$page      = max(1, intval($_GPC['page']));
$page_size = 1000;

$result = $_order_submit_order_skuModel->queryAll($where, $page, $page_size);


$source_total     = $result['total'];
$source_page      = $result['page'];
$source_page_size = $result['page_size'];
$source_list      = $result['data'];

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
//    "shop_goods_id": "4696",
//    "return_goods_nun": "0",
//    "return_goods_result": ""
//}

$overwrite = 1;
$page_num = 0;
foreach ($source_list as $index => $item) {

//    if($item['shop_goods_id']!=0){
//        echo json_encode($item,JSON_UNESCAPED_UNICODE);
//        echo PHP_EOL;
//    }

    $column    = array(
        "jd_vop_sku" => $item['skuId']
    );
    $_is_exist = $_goodsModel->getOneByColumn($column);

    if(!$_is_exist){
        $result = $_productService->businessProcessingGetDetailOne($item['skuId'],$page_num,$overwrite,$item['shop_goods_id']);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        echo PHP_EOL;
    } else {
        echo "exist => " . $item['skuId'];
        echo PHP_EOL;
    }



}