<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/7/17
 * Time: 5:58 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_detail_one
 *
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_detail_one&page_num=9439&sku=1300639
 * debug 9439 :: 1300639
 *
 * debug 360000701 :: 5640991
 *
 *
 * debug 20170703 :: 3794327 for test syncCategoryFormSkuDetail
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_detail_one&overwrite=1&page_num=20170703&sku=3794327
 */

global $_W, $_GPC;

/******************************************************* redis *********************************************************/
//microtime_format('Y-m-d H:i:s.x',microtime())
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();
/******************************************************* redis *********************************************************/

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
$_goodsModel = new goodsModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_detail.class.php');
$_product_detailModel = new product_detailModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_price.class.php');
$_product_priceModel = new product_priceModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();


include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
$jd_sdk = new JDVIPOpenPlatformSDK();
$jd_sdk->debug = false;
$jd_sdk->init_access_token();


$sku       = $_GPC['sku'];
$page_num  = $_GPC['page_num'];
$overwrite = $_GPC['overwrite'];

//$debug_echo               = array();
//$debug_echo['sku']       = $sku;
//$debug_echo['page_num']  = $page_num;
//$debug_echo['overwrite'] = $overwrite;
//$debug_echo['merchid'] = SUPERDESK_SHOPV2_JD_VOP_MERCHID;


//$jd_sdk->api_product_get_detail($sku/*只支持单个*/, true);
//return;
//show_json(1,$debug_echo);

$table_key = 'superdesk_jd_vop_' . 'api_product_get_detail' . ':' . $_W['uniacid'] . ':' .$page_num;

$is_insert = false;

if ($_redis->ishExists($table_key, $sku) == 1) {


    $response                 = $_redis->hget($table_key, $sku);
    $result_get_detail_decode = json_decode($response, true);

    if ($overwrite == 1 || !isset($result_get_detail_decode['success']) || $result_get_detail_decode['success'] == false) {

        $response = $jd_sdk->api_product_get_detail($sku/*只支持单个*/, true);
        $_redis->hset($table_key, $sku, $response);
        $result_get_detail_decode = json_decode($response, true);
        $is_insert                = true;
    }

    // 京东价格 start
    $_product_priceModel->saveOrUpdateByJdVop($sku);
    // 京东价格 end

    // debug
//    $is_insert = true;

    if($is_insert == true || $overwrite == 1 ){

    } else {
        show_json(1,$result_get_detail_decode);
    }


    
} else {

    $response = $jd_sdk->api_product_get_detail($sku/*只支持单个*/, true);
    $_redis->hset($table_key, $sku, $response);
    $result_get_detail_decode = json_decode($response, true);

    $is_insert = true;

}


// bug
//1153795
//{
//    "status": 1,
//    "result": {
//    "success": false,
//        "resultMessage": "服务异常，请稍后重试",
//        "resultCode": "5001",
//        "code": 200,
//        "url": "http:\/\/192.168.1.124\/superdesk\/web\/index.php?c=site&a=entry&eid=1466"
//    }
//}


if($result_get_detail_decode['success'] == true && $is_insert == true){


    $skuId[] = $sku;

    // save to temp table start
    $where = array();
    $where['sku'] = $sku;
    $result_get_detail_decode['result']['page_num'] = $page_num;
    $_product_detailModel->saveOrUpdateByColumn($result_get_detail_decode['result'],$where);
    // save to temp table end


    // load from temp table
    $_product_detail = $_product_detailModel->getOneBySku($sku);

    // 处理分类
    $jd_vop_category = $_product_detail['category'];
    $jd_vop_category = explode(";",$jd_vop_category);

    $_productService->syncCategoryFormSkuDetail($jd_vop_category,$page_num);

    // 分销商城sku表入库
    $_goodsModel->saveOrUpdateByJdVopApiProductGetDetail(
        $_product_detail,
        $_product_detail['sku']
    );

    // skuImage
    $_productService->skuImage($_product_detail['sku']);

    // update updatetime
    $_product_detailModel->callbackForJdVopApiProductDetailUpdate($skuId);

    // 京东价格临时表 init start
    $_product_priceModel->saveOrUpdateByJdVop($sku);
    // 京东价格临时表 init end

    show_json(1,$result_get_detail_decode);

} else {

    show_json(0,$result_get_detail_decode);
    
}
