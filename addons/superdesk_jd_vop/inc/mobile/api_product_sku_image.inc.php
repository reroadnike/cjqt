<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 2/27/18
 * Time: 11:22 AM
 */


global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();

// skuImage
$_productService->skuImage($_product_detail['sku']);