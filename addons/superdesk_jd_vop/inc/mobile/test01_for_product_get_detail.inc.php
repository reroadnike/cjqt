<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/7/17
 * Time: 5:58 PM
 *
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=test01_for_product_get_detail
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=test01_for_product_get_detail
 */

global $_W, $_GPC;

/******************************************************* redis *********************************************************/
//microtime_format('Y-m-d H:i:s.x',microtime())
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();
/******************************************************* redis *********************************************************/

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');
$_categoryModel = new categoryModel();

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
$_goodsModel = new goodsModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_detail.class.php');
$_product_detailModel = new product_detailModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
$jd_sdk = new JDVIPOpenPlatformSDK();
$jd_sdk->init_access_token();


//$page = 1;
//$page_size = 1000;
//
//$result = $_categoryModel->queryAll(array(),$page,$page_size);
//
//$total = $result['total'];
//$page = $result['page'];
//$page_size = $result['page_size'];
//$list = $result['data'];
//
//
//$table__key_get_sku     = 'superdesk_jd_vop_' . 'api_product_get_sku' . ':' . $_W['uniacid'];
//$table__key_get_detail  = 'superdesk_jd_vop_' . 'api_product_get_detail' . ':' . $_W['uniacid'];
//
//
//foreach ($list as $index => $item){
//
//    $colunm_key_get_sku = $item['jd_vop_page_num'];
//
//    $test00_id = $item['id'];
//    $test01_id = $_categoryModel->getIdByColumnJdVopPageNum($item['jd_vop_page_num']);
//
//    echo $test00_id." = " . $test01_id . " ? " ;
//    echo $test00_id == $test01_id? " true " : " false ";
//    echo "<br/>";
//
//
//}