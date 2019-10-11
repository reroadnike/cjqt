<?php
/**
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_recovery&do=step_00_1_get_one_sku
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_recovery&do=step_00_1_get_one_sku
 */
global $_W, $_GPC;


include_once(IA_ROOT . '/addons/superdesk_shopv2/model/order/order_goods.class.php');
$_order_goodsModel = new order_goodsModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
$_goodsModel = new goodsModel();

$overwrite       = 1;
$page_num        = 0;
$target_sku      = 6170130;
$target_goods_id = 0;//382404;

$result = $_productService->businessProcessingGetDetailOne($target_sku, $page_num, $overwrite, $target_goods_id);



//$column    = array(
//    "jd_vop_sku" => $target_sku
//);
//$_is_exist = $_goodsModel->getOneByColumn($column);
//
//if (!$_is_exist) {
//    echo "##### => " . $target_sku;
//    $result = $_productService->businessProcessingGetDetailOne($target_sku, $page_num, $overwrite, $target_goods_id);
//    echo json_encode($result, JSON_UNESCAPED_UNICODE);
//    echo PHP_EOL;
//} else {
//    echo "exist => " . $target_sku;
//    echo PHP_EOL;
//}
