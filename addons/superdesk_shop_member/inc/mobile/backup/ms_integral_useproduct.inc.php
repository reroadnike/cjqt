<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_integral_useproduct.class.php');
$ms_integral_useproduct = new ms_integral_useproductModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['integral_useproduct_id'] = isset($item['integral_useproduct_id']) ? $item['integral_useproduct_id'] : '';
    $params['integral_standard_pkcode'] = isset($item['integral_standard_pkcode']) ? $item['integral_standard_pkcode'] : '';
    $params['classify_id'] = isset($item['classify_id']) ? $item['classify_id'] : '';
    $params['integral_useproduct_state'] = isset($item['integral_useproduct_state']) ? $item['integral_useproduct_state'] : '';
    $params['classify_name'] = isset($item['classify_name']) ? $item['classify_name'] : '';
    $params['integral_useproduct_ctime'] = isset($item['integral_useproduct_ctime']) ? $item['integral_useproduct_ctime'] : '';
    $params['integral_useproduct_supplierID'] = isset($item['integral_useproduct_supplierID']) ? $item['integral_useproduct_supplierID'] : '';
    $params['integral_useproduct_supplierName'] = isset($item['integral_useproduct_supplierName']) ? $item['integral_useproduct_supplierName'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);