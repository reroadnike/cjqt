<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/13/17
 * Time: 5:15 PM
 *
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=api_area_get_county&code=115
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_sku
 */

global $_W, $_GPC;

/******************************************************* redis *********************************************************/
//microtime_format('Y-m-d H:i:s.x',microtime())
include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();
/******************************************************* redis *********************************************************/

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/area.class.php');
$_areaModel = new areaModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
$jd_sdk = new JDVIPOpenPlatformSDK();
//$jd_sdk->debug = true;
$jd_sdk->init_access_token();




//   item 结构 array(4) {
//        ["code"]=> int(1)
//        ["parent_code"]=> int(0)
//        ["text"]=> string(6) "北京"
//        ["updatetime"]=> int(1510568194)}

$city['code'] = $_GPC['code'];

//4.3  获取三级地址
$response_county = $jd_sdk->api_area_get_county($city['code']);
$return_county = $_areaModel->saveOrUpdateByJdVopApiAreaBatch($response_county, $city['code'], 2);

$data = array();
$data['list'] = $return_county;

show_json(1,$data);

//foreach ($return_county as $___index => $county) {
//
//    //4.4  获取四级地址
//    $response_town = $jd_sdk->api_area_get_town($county['code']);
//    $return_town = $_areaModel->saveOrUpdateByJdVopApiAreaBatch($response_town, $county['code'], 3);
//
//}


////4.5  验证四级地址是否正确
//$jd_sdk->api_area_check_area();
