<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 11/21/17
 * Time: 9:23 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=api_invoice_get_invoice_list
 * 11.6  获取电子发票信息.v1
 */

include_once(IA_ROOT . '/addons/superdesk_jd_vop/sdk/jd_vop_sdk.php');
include_once(IA_ROOT . '/addons/superdesk_jd_vop/service/InvoiceService.class.php');
$_invoiceService = new InvoiceService();

die;

$jdOrderId = '';
$ivcType = 1;

$result = $_invoiceService->get_invoice_list($jdOrderId, $ivcType);

show_json(1,$result);