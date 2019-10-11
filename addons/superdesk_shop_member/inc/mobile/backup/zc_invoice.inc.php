<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/zc_invoice.class.php');
$zc_invoice = new zc_invoiceModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['zi_id'] = isset($item['zi_id']) ? $item['zi_id'] : '';
    $params['zi_invoiceType'] = isset($item['zi_invoiceType']) ? $item['zi_invoiceType'] : '';
    $params['zi_selectedInvoiceTitle'] = isset($item['zi_selectedInvoiceTitle']) ? $item['zi_selectedInvoiceTitle'] : '';
    $params['zi_companyName'] = isset($item['zi_companyName']) ? $item['zi_companyName'] : '';
    $params['zi_invoiceContent'] = isset($item['zi_invoiceContent']) ? $item['zi_invoiceContent'] : '';
    $params['zi_invoiceAddress'] = isset($item['zi_invoiceAddress']) ? $item['zi_invoiceAddress'] : '';
    $params['zi_invoicePhone'] = isset($item['zi_invoicePhone']) ? $item['zi_invoicePhone'] : '';
    $params['zi_state'] = isset($item['zi_state']) ? $item['zi_state'] : '';
    $params['zi_fansid'] = isset($item['zi_fansid']) ? $item['zi_fansid'] : '';
    $params['zi_ctime'] = isset($item['zi_ctime']) ? $item['zi_ctime'] : '';
    $params['zi_taxpayer_identification_number'] = isset($item['zi_taxpayer_identification_number']) ? $item['zi_taxpayer_identification_number'] : '';
    $params['zi_invoiceBank'] = isset($item['zi_invoiceBank']) ? $item['zi_invoiceBank'] : '';
    $params['zi_invoiceAccount'] = isset($item['zi_invoiceAccount']) ? $item['zi_invoiceAccount'] : '';
    $params['zi_invoiceName'] = isset($item['zi_invoiceName']) ? $item['zi_invoiceName'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);