<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/14/17
 * Time: 11:20 AM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_category
 */

global $_W, $_GPC;

/******************************************************* redis *********************************************************/
//microtime_format('Y-m-d H:i:s.x',microtime())
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();
/******************************************************* redis *********************************************************/

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');
$_categoryModel = new categoryModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
$jd_sdk = new JDVIPOpenPlatformSDK();
$jd_sdk->init_access_token();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();

$_productService->getCategory(671);