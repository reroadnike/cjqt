<?php
/**
 * 关于商品
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/7/17
 * Time: 5:58 PM
 *
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_jd_vop&do=do_product_get_detail_step01
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

include_once(IA_ROOT . '/addons/superdesk_shopv2/model/goods/goods.class.php');
$_goodsModel = new goodsModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/model/product_detail.class.php');
$_product_detailModel = new product_detailModel();

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
$jd_sdk = new JDVIPOpenPlatformSDK();
$jd_sdk->init_access_token();


$page = 1;
$page_size = 1000;

$result = $_categoryModel->queryByJdVopApiProductGetSku(array(),$page,$page_size);

$total = $result['total'];
$page = $result['page'];
$page_size = $result['page_size'];
$list = $result['data'];


$table__key_get_sku     = 'superdesk_jd_vop_' . 'api_product_get_sku' . ':' . $_W['uniacid'];
$table__key_get_detail  = 'superdesk_jd_vop_' . 'api_product_get_detail' . ':' . $_W['uniacid'];



// 从redis 中取得已记录的product 入mysql
foreach ($list as $index => $item){

    $page_num = $item['jd_vop_page_num'];

    $result = $_redis->hget($table__key_get_sku, $page_num);
    $result_decode = json_decode($result,true);

//    if($index > 10){
//        return;
//    }

    if(!empty($result) && $result_decode['success'] == true){

        $sku_list = explode(",",$result_decode['result']);

        if(sizeof($sku_list) > 0){

            $sku = $sku_list[0];
            $colunm_key_get_detail = $item['jd_vop_page_num'].":".$sku;

            $result_get_detail = $_redis->hget($table__key_get_detail, $colunm_key_get_detail);
            $result_get_detail_decode = json_decode($result_get_detail,true);

            // 如果从redis中得到与京东返回success=true
            if($result_get_detail != false && $result_get_detail_decode['success'] == true){

                $where = array();
                $where['sku'] = $result_get_detail_decode['result']['sku'];

//                page_num
                $result_get_detail_decode['result']['page_num'] = $item['jd_vop_page_num'];

                $_product_detailModel->saveOrUpdateByColumn($result_get_detail_decode['result'],$where);

            }
        }
    }

}