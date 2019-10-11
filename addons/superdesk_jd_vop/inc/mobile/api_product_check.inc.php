<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/13/18
 * Time: 6:43 PM
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_check
 */


global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_detail.class.php');
$_product_detailModel = new product_detailModel();

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


// 3.13  商品可售验证接口
$response = $jd_sdk->api_product_check('102214');


die(json_encode(json_decode($response,true),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));