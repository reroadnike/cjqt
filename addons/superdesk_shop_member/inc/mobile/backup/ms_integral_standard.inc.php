<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_integral_standard.class.php');
$ms_integral_standard = new ms_integral_standardModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['integral_standard_id'] = isset($item['integral_standard_id']) ? $item['integral_standard_id'] : '';
    $params['integral_standard_pkcode'] = isset($item['integral_standard_pkcode']) ? $item['integral_standard_pkcode'] : '';
    $params['integral_standard_name'] = isset($item['integral_standard_name']) ? $item['integral_standard_name'] : '';
    $params['integral_standard_number'] = isset($item['integral_standard_number']) ? $item['integral_standard_number'] : '';
    $params['integral_standard_expiretype'] = isset($item['integral_standard_expiretype']) ? $item['integral_standard_expiretype'] : '';
    $params['integral_standard_begintime'] = isset($item['integral_standard_begintime']) ? $item['integral_standard_begintime'] : '';
    $params['integral_standard_endtime'] = isset($item['integral_standard_endtime']) ? $item['integral_standard_endtime'] : '';
    $params['integral_standard_ctime'] = isset($item['integral_standard_ctime']) ? $item['integral_standard_ctime'] : '';
    $params['integral_standard_useproducttype'] = isset($item['integral_standard_useproducttype']) ? $item['integral_standard_useproducttype'] : '';
    $params['integral_standard_state'] = isset($item['integral_standard_state']) ? $item['integral_standard_state'] : '';
    $params['jsons'] = isset($item['jsons']) ? $item['jsons'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);