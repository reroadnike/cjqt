<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/21/17
 * Time: 9:23 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_jincai_get_bill_detail
 */

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/JincaiService.class.php');
$_jincaiService = new JincaiService();

//die;

global $_GPC;

$billId   = $_GPC['billId'] ? $_GPC['billId'] : '';
$orderId  = $_GPC['orderId'] ? $_GPC['orderId'] : '';
$pageNo   = 1;
$pageSize = 100;

$result = $_jincaiService->getBillDetail($billId, $orderId, $pageNo, $pageSize);

show_json(1,$result);