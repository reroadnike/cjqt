<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/6/18
 * Time: 11:24 AM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_sku_state
 */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();


$skuArr = array();
$skuArr[] = "5729524";
$response = $_productService->skuState($skuArr);

die(json_encode($response , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));