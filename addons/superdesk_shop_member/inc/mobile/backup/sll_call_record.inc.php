<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_call_record.class.php');
$sll_call_record = new sll_call_recordModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['call_record_id'] = isset($item['call_record_id']) ? $item['call_record_id'] : '';
    $params['call_record_mobile'] = isset($item['call_record_mobile']) ? $item['call_record_mobile'] : '';
    $params['call_record_store_id'] = isset($item['call_record_store_id']) ? $item['call_record_store_id'] : '';
    $params['call_record_ctime'] = isset($item['call_record_ctime']) ? $item['call_record_ctime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);