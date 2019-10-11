<?php
/**
 * 正向通过分类(has jd vop page num)
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/7/17
 * Time: 4:15 PM
 *
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


$page = 1;
$page_size = 1000;

$result = $_categoryModel->queryByJdVopApiProductGetSku(array(),$page,$page_size);

$total     = $result['total'];
$page      = $result['page'];
$page_size = $result['page_size'];
$list      = $result['data'];

//echo json_encode($result , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
//exit();

$table__key = 'superdesk_jd_vop_' . 'api_product_get_sku' . ':' . $_W['uniacid'];


// $list = 获取page num 不为0的分类
foreach ($list as $index => $item){


    $page_num = $item['jd_vop_page_num'];
    echo $page_num ;
    echo "<br/>";

    $result        = $_redis->hget($table__key, $page_num);
    $result_decode = json_decode($result, true);


    if(empty($result)){

        echo " => " . "redis 不存在";
        echo "<br/>";

        $response = $jd_sdk->api_product_get_sku($page_num);
        $_redis->hset($table__key, $page_num , $response);

    } else {

//        {
//          "success": false,
//          "resultMessage": "pageNum不存在",
//          "resultCode": "0010",
//          "result": null,
//          "code": 200
//        }



        if($result_decode['success'] == true){
            echo $page_num . " => " . $result;
            echo "<br/>";

            $response = $jd_sdk->api_product_get_sku($page_num);
            $_redis->hset($table__key, $page_num , $response);
        } elseif ($result_decode['success'] == false) {

            if($result_decode['resultMessage'] == "pageNum不存在"
                && $result_decode['resultCode'] == "0010"){
                continue;
            }
        }
    }





}