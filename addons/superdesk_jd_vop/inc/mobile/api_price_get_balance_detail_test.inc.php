<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/29/18
 * Time: 11:01 AM
 *
 *
 * 企业采购
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_price_get_balance_detail_test
 *
 * 福利内购
 * view-source:https://wxn.avic-s.com/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=api_price_get_balance_detail_test
 */


/**
 * 用于初始化
 */
global $_W, $_GPC;


include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');
$_priceService = new PriceService();


$page_size = 1000;

// 数据监测了一下,大概是不会删除一年内数据
// page size 最大是 1000

// init start
$response = $_priceService->getBalanceDetail(1, $page_size);

$source_total      = $response['result']['total'];
$source_page       = $response['result']['pageNo'];
$source_page_size  = $response['result']['pageSize'];
$source_page_count = $response['result']['pageCount'];

$source_list = $response['result']['data'];

//die(json_encode($response , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

// init end


for ($page = $source_page_count; $page > 0; $page--) {
    $_priceService->syncBalanceDetailAll($page, $page_size);
}




