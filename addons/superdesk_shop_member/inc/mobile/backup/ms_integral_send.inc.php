<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_integral_send.class.php');
$ms_integral_send = new ms_integral_sendModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['integral_send_id'] = isset($item['integral_send_id']) ? $item['integral_send_id'] : '';
    $params['integral_send_name'] = isset($item['integral_send_name']) ? $item['integral_send_name'] : '';
    $params['integral_standard_pkcode'] = isset($item['integral_standard_pkcode']) ? $item['integral_standard_pkcode'] : '';
    $params['integral_send_timetype'] = isset($item['integral_send_timetype']) ? $item['integral_send_timetype'] : '';
    $params['integral_send_diytime'] = isset($item['integral_send_diytime']) ? $item['integral_send_diytime'] : '';
    $params['integral_send_number'] = isset($item['integral_send_number']) ? $item['integral_send_number'] : '';
    $params['integral_send_ctime'] = isset($item['integral_send_ctime']) ? $item['integral_send_ctime'] : '';
    $params['integral_send_state'] = isset($item['integral_send_state']) ? $item['integral_send_state'] : '';
    $params['e_id'] = isset($item['e_id']) ? $item['e_id'] : '';
    $params['integral_send_companyid'] = isset($item['integral_send_companyid']) ? $item['integral_send_companyid'] : '';
    $params['integral_send_branch'] = isset($item['integral_send_branch']) ? $item['integral_send_branch'] : '';
    $params['integral_send_department'] = isset($item['integral_send_department']) ? $item['integral_send_department'] : '';
    $params['integral_send_group'] = isset($item['integral_send_group']) ? $item['integral_send_group'] : '';
    $params['integral_send_position'] = isset($item['integral_send_position']) ? $item['integral_send_position'] : '';
    $params['integral_send_welfare'] = isset($item['integral_send_welfare']) ? $item['integral_send_welfare'] : '';
    $params['integral_send_endtime'] = isset($item['integral_send_endtime']) ? $item['integral_send_endtime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);