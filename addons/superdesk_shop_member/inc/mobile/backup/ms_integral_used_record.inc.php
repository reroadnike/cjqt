<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_integral_used_record.class.php');
$ms_integral_used_record = new ms_integral_used_recordModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['integral_used_record_id'] = isset($item['integral_used_record_id']) ? $item['integral_used_record_id'] : '';
    $params['integral_used_record_data_id'] = isset($item['integral_used_record_data_id']) ? $item['integral_used_record_data_id'] : '';
    $params['integral_used_record_orderid'] = isset($item['integral_used_record_orderid']) ? $item['integral_used_record_orderid'] : '';
    $params['integral_used_record_number'] = isset($item['integral_used_record_number']) ? $item['integral_used_record_number'] : '';
    $params['integral_used_record_ctime'] = isset($item['integral_used_record_ctime']) ? $item['integral_used_record_ctime'] : '';
    $params['integral_used_record_goodsid'] = isset($item['integral_used_record_goodsid']) ? $item['integral_used_record_goodsid'] : '';
    $params['integral_used_record_mid'] = isset($item['integral_used_record_mid']) ? $item['integral_used_record_mid'] : '';
    $params['integral_used_record_type'] = isset($item['integral_used_record_type']) ? $item['integral_used_record_type'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);