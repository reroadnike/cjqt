<?php
/**
 * 京东余额查询
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/21/17
 * Time: 6:42 PM
 */
global $_GPC, $_W;

$_DEBUG = true;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');
$_priceService = new PriceService();

$balance = $_priceService->getBalance();





$page = max(1, intval($_GPC['page']));
$page_size = 10;

$result = $_priceService->getBalanceDetail($page, $page_size);

//var_dump($result);


$total = $result['result']['total'];
$page = $result['result']['pageNo'];
$page_size = $result['result']['pageSize'];
$list = $result['result']['data'];

$pager = pagination($total, $page, $page_size);



include $this->template('jd_vop_price_get_balance');