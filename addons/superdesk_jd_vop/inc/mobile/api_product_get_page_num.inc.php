<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/7/17
 * Time: 11:48 AM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_page_num
 */

global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');
$_categoryModel = new categoryModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/page_num.class.php');
$_page_numModel = new page_numModel();


include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
$jd_sdk = new JDVIPOpenPlatformSDK();
$jd_sdk->debug = true;
$jd_sdk->init_access_token();


//3.1  获取商品池编号接口
$result = $jd_sdk->api_product_get_page_num();
$result = json_decode($result,true);

//{
//    "success": true,
//  "resultMessage": "",
//  "resultCode": "0000",
//  "result": [
//    {
//        "name": "办公设备",
//      "page_num": "333333"
//    },
//    {
//        "name": "IT设备",
//      "page_num": "87654321"
//    }
//  ]
//}


foreach ($result['result'] as $index => $item){

//    $_categoryModel->saveOrUpdateByJdVop($item,$item['page_num']);
    $_page_numModel->saveOrUpdateByJdVop($item,$item['page_num']);

}