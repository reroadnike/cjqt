<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_main_orders.class.php');
$sll_main_orders = new sll_main_ordersModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['main_id'] = isset($item['main_id']) ? $item['main_id'] : '';
    $params['main_tel'] = isset($item['main_tel']) ? $item['main_tel'] : '';
    $params['main_fansid'] = isset($item['main_fansid']) ? $item['main_fansid'] : '';
    $params['main_openid'] = isset($item['main_openid']) ? $item['main_openid'] : '';
    $params['main_orderid'] = isset($item['main_orderid']) ? $item['main_orderid'] : '';
    $params['main_orderPrice'] = isset($item['main_orderPrice']) ? $item['main_orderPrice'] : '';
    $params['main_pay_time'] = isset($item['main_pay_time']) ? $item['main_pay_time'] : '';
    $params['main_payNumber'] = isset($item['main_payNumber']) ? $item['main_payNumber'] : '';
    $params['main_isPay'] = isset($item['main_isPay']) ? $item['main_isPay'] : '';
    $params['main_ctime'] = isset($item['main_ctime']) ? $item['main_ctime'] : '';
    $params['main_payconf'] = isset($item['main_payconf']) ? $item['main_payconf'] : '';
    $params['main_type'] = isset($item['main_type']) ? $item['main_type'] : '';
    $params['main_payMsg'] = isset($item['main_payMsg']) ? $item['main_payMsg'] : '';
    $params['main_e_id'] = isset($item['main_e_id']) ? $item['main_e_id'] : '';
    $params['main_invoiceType'] = isset($item['main_invoiceType']) ? $item['main_invoiceType'] : '';
    $params['main_selectedInvoiceTitle'] = isset($item['main_selectedInvoiceTitle']) ? $item['main_selectedInvoiceTitle'] : '';
    $params['main_companyName'] = isset($item['main_companyName']) ? $item['main_companyName'] : '';
    $params['main_invoiceContent'] = isset($item['main_invoiceContent']) ? $item['main_invoiceContent'] : '';
    $params['main_invoiceName'] = isset($item['main_invoiceName']) ? $item['main_invoiceName'] : '';
    $params['main_invoicePhone'] = isset($item['main_invoicePhone']) ? $item['main_invoicePhone'] : '';
    $params['main_make_out_invoice_state'] = isset($item['main_make_out_invoice_state']) ? $item['main_make_out_invoice_state'] : '';
    $params['main_taxpayer_identification_number'] = isset($item['main_taxpayer_identification_number']) ? $item['main_taxpayer_identification_number'] : '';
    $params['main_invoiceBank'] = isset($item['main_invoiceBank']) ? $item['main_invoiceBank'] : '';
    $params['main_invoiceAccount'] = isset($item['main_invoiceAccount']) ? $item['main_invoiceAccount'] : '';
    $params['main_invoiceAddress'] = isset($item['main_invoiceAddress']) ? $item['main_invoiceAddress'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);