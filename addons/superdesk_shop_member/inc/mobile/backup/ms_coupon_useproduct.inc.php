<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_coupon_useproduct.class.php');
$ms_coupon_useproduct = new ms_coupon_useproductModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['coupon_useproduct_id'] = isset($item['coupon_useproduct_id']) ? $item['coupon_useproduct_id'] : '';
    $params['coupon_standard_pkcode'] = isset($item['coupon_standard_pkcode']) ? $item['coupon_standard_pkcode'] : '';
    $params['classify_id'] = isset($item['classify_id']) ? $item['classify_id'] : '';
    $params['coupon_useproduct_state'] = isset($item['coupon_useproduct_state']) ? $item['coupon_useproduct_state'] : '';
    $params['classify_name'] = isset($item['classify_name']) ? $item['classify_name'] : '';
    $params['coupon_useproduct_ctime'] = isset($item['coupon_useproduct_ctime']) ? $item['coupon_useproduct_ctime'] : '';
    $params['coupon_useproduct_supplierID'] = isset($item['coupon_useproduct_supplierID']) ? $item['coupon_useproduct_supplierID'] : '';
    $params['coupon_useproduct_supplierName'] = isset($item['coupon_useproduct_supplierName']) ? $item['coupon_useproduct_supplierName'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);