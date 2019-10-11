<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/21/17
 * Time: 9:23 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_stock_get_new_stock_by_id
 */

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/StockService.class.php');
$_stockService = new StockService();

$json_str = "{\"skuNums\":[{\"goodsId\":597,\"skuId\":\"892900\",\"num\":1},{\"goodsId\":595,\"skuId\":\"1037029\",\"num\":1},{\"goodsId\":207,\"skuId\":\"124157\",\"num\":1}]}";

//$_stockService->getNewStockById($json_str,"19_1607_3639");


$json = json_decode($json_str,true);


$_stockService->getNewStockById(json_encode($json['skuNums']),"19_1607_3639");