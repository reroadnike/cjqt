<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/7/17
 * Time: 5:58 PM
 *
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=api_product_get_detail
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_product_get_sku
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


$page      = 1;
$page_size = 1000;

$result = $_categoryModel->queryAll(array(), $page, $page_size);

$total     = $result['total'];
$page      = $result['page'];
$page_size = $result['page_size'];
$list      = $result['data'];


$table__key_get_sku     = 'superdesk_jd_vop_' . 'api_product_get_sku' . ':' . $_W['uniacid'];
$table__key_get_detail  = 'superdesk_jd_vop_' . 'api_product_get_detail' . ':' . $_W['uniacid'];



// 根据 分类表 的分类 调用 京东vop api 获取商品池 sku id 
foreach ($list as $index => $item){

    $colunm_key_get_sku = $item['jd_vop_page_num'];

    $result = $_redis->hget($table__key_get_sku, $colunm_key_get_sku);
    $result_decode = json_decode($result,true);

//    if($index > 10){
//        return;
//    }

    if(!empty($result) && $result_decode['success'] == true){

        $sku_list = explode(",",$result_decode['result']);

        if(sizeof($sku_list) > 0){

            $sku = $sku_list[0];
            $colunm_key_get_detail = $item['jd_vop_page_num'].":".$sku;

//            echo $colunm_key_get_detail . "";
            echo "<br/>";

            $result_get_detail        = $_redis->hget($table__key_get_detail, $colunm_key_get_detail);
            $result_get_detail_decode = json_decode($result_get_detail, true);

            if($result_get_detail_decode['success'] == false){
                $response = $jd_sdk->api_product_get_detail($sku,true);
                $_redis->hset($table__key_get_detail, $colunm_key_get_detail , $response);
            }
        }

    }
}