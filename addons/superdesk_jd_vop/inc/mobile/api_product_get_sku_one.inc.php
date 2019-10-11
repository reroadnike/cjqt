<?php
/**
 * 正向通过分类(has jd vop page num)
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/7/17
 * Time: 4:15 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_sku_one
 */

global $_W, $_GPC;

/******************************************************* redis *********************************************************/

include_once(IA_ROOT . '/framework/library/redis/RedisUtil.class.php');
$_redis = new RedisUtil();
/******************************************************* redis *********************************************************/

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/category.class.php');
$_categoryModel = new categoryModel();


include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
$jd_sdk = new JDVIPOpenPlatformSDK();
//$jd_sdk->debug = false;
$jd_sdk->init_access_token();

$table__key = 'superdesk_jd_vop_' . 'api_product_get_sku' . ':' . $_W['uniacid'];


$page_num = $_GPC['page_num'];

//$page_num = 730;
//$page_num = 11966;//钟饰


if (intval($page_num) == 0) {
    show_json(0, "pageNum=0,为系统辅助分类,不作京东Sku同步");
}

$_DEBUG = false;

if($_DEBUG){
    $_redis->hDel($table__key, $page_num);
}



// 老方法
//if ($_redis->ishExists($table__key, $page_num) == 1) {
//
//    $response      = $_redis->hget($table__key, $page_num);
//    $result_decode = json_decode($response, true);
//} else {
//
//    $response = $jd_sdk->api_product_get_sku($page_num);
//    $_redis->hset($table__key, $page_num, $response);
//    $result_decode = json_decode($response, true);
//}
//
//if ($result_decode['success'] == true) {
//
//    $arr_split = explode(",", $result_decode['result']);
//
//    $data             = array();
//    $data['page_num'] = $page_num;
//    $data['list']     = $arr_split;
//    $data['total']    = sizeof($arr_split);
//
//    show_json(1, $data);
//
//} elseif ($result_decode['success'] == false) {
//
//    if ($result_decode['resultMessage'] == "pageNum不存在"
//        && $result_decode['resultCode'] == "0010"
//    ) {
//
//        $params = array(
//            'enabled' => 0
//        );
//        $column = array(
//            'jd_vop_page_num' => $page_num
//        );
//        $_categoryModel->saveOrUpdateByColumn($params,$column);
//        // TODO 把这个屏蔽
//        show_json(0, $result_decode['resultMessage']);
//    }
//
//    show_json(0, $result_decode['resultMessage']);
//}


include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/ProductService.class.php');
$_productService = new ProductService();

$result = $_productService->businessProcessingGetSkuByPageForManual($page_num);

show_json(1, $result);






