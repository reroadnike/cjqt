<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_integral_data.class.php');
$ms_integral_data = new ms_integral_dataModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['integral_data_id'] = isset($item['integral_data_id']) ? $item['integral_data_id'] : '';
    $params['integral_standard_pkcode'] = isset($item['integral_standard_pkcode']) ? $item['integral_standard_pkcode'] : '';
    $params['integral_data_name'] = isset($item['integral_data_name']) ? $item['integral_data_name'] : '';
    $params['integral_data_pkcode'] = isset($item['integral_data_pkcode']) ? $item['integral_data_pkcode'] : '';
    $params['integral_data_number'] = isset($item['integral_data_number']) ? $item['integral_data_number'] : '';
    $params['integral_data_expiretype'] = isset($item['integral_data_expiretype']) ? $item['integral_data_expiretype'] : '';
    $params['integral_data_begintime'] = isset($item['integral_data_begintime']) ? $item['integral_data_begintime'] : '';
    $params['integral_data_endtime'] = isset($item['integral_data_endtime']) ? $item['integral_data_endtime'] : '';
    $params['integral_data_ctime'] = isset($item['integral_data_ctime']) ? $item['integral_data_ctime'] : '';
    $params['integral_data_useproducttype'] = isset($item['integral_data_useproducttype']) ? $item['integral_data_useproducttype'] : '';
    $params['integral_data_state'] = isset($item['integral_data_state']) ? $item['integral_data_state'] : '';
    $params['integral_m_id'] = isset($item['integral_m_id']) ? $item['integral_m_id'] : '';
    $params['integral_use_time'] = isset($item['integral_use_time']) ? $item['integral_use_time'] : '';
    $params['integral_data_isReturn'] = isset($item['integral_data_isReturn']) ? $item['integral_data_isReturn'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);