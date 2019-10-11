<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 1/29/18
 * Time: 11:01 AM
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_price_get_balance_detail
 */


global $_W, $_GPC;


include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');
$_priceService = new PriceService();


// 数据监测了一下,大概是不会删除一年内数据
// page size 最大是 1000



$page = max(3, intval($_GPC['page']));
$page_size = 1000;

$response = $_priceService->getBalanceDetail($page, $page_size);

die(json_encode($response , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));


$total = $result['result']['total'];
$page = $result['result']['pageNo'];
$page_size = $result['result']['pageSize'];
$list = $result['result']['data'];

$pager = pagination($total, $page, $page_size);

