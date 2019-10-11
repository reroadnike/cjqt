<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/9/17
 * Time: 6:05 PM
 *
 * 企业内购
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_price_get_price_test
 *
 * 福利商城
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=17&c=entry&m=superdesk_jd_vop&do=api_price_get_price_test
 */

global $_W, $_GPC;

include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/PriceService.class.php');
$_priceService = new PriceService();

$skuId = array();
$skuId[] = '4592575';
//$skuId[] = '4592575';

//$skuStr = implode(",", $skuId);
//var_dump($skuStr);
//die();



$_priceService->getPrice($skuId);