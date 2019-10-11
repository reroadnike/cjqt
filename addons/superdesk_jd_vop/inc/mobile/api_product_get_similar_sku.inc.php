<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/19/18
 * Time: 3:12 PM
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_similar_sku
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_similar_sku&sku=7897145
 */


global $_W, $_GPC;

$sku = $_GPC['sku'];

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
$jd_sdk = new JDVIPOpenPlatformSDK();
$jd_sdk->init_access_token();



//// 默认 x 100 sku
//$result = $_product_detailModel->queryForJdVopApiProductDetailUpdate();
//
//$total     = $result['total'];
//$page      = $result['page'];
//$page_size = $result['page_size'];
//$list      = $result['data'];
//
//
//$skuId = array();
//foreach ($list as $index => $item){
//
//    echo $index . " ";
//    echo json_encode($item);
//    echo "<br/>";
//
//    $skuId[] = $item['sku'];
//
//}
//
//$skuStr = implode(",",$skuId);


//7897145,
//7684322,
//7684320,
//7897105,
//7684308,
//7897103,
//7684312,
//7897127,
//7684314
// 3.13  商品可售验证接口
//$response = $jd_sdk->api_product_get_similar_sku('7897145');
//die(json_encode(json_decode($response,true),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

$response = $_productService->getSimilarSku($sku);
die(json_encode($response,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));



//测试样例
//
//http://localhost:8080/detail?id=1529324
//
//
//超出显示的反例
//https://item.jd.com/1259287.html
//
//
//有问题的京东商品
//https://b.superdesk.cn/detail?id=1819014