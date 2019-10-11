<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_integral_sendresult.class.php');
$ms_integral_sendresult = new ms_integral_sendresultModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['integral_sendresult_id'] = isset($item['integral_sendresult_id']) ? $item['integral_sendresult_id'] : '';
    $params['integral_sendresult_ctime'] = isset($item['integral_sendresult_ctime']) ? $item['integral_sendresult_ctime'] : '';
    $params['e_id'] = isset($item['e_id']) ? $item['e_id'] : '';
    $params['integral_sendresult_type'] = isset($item['integral_sendresult_type']) ? $item['integral_sendresult_type'] : '';
    $params['integral_standard_pkcode'] = isset($item['integral_standard_pkcode']) ? $item['integral_standard_pkcode'] : '';
    $params['integral_sendresult_state'] = isset($item['integral_sendresult_state']) ? $item['integral_sendresult_state'] : '';
    $params['integral_send_id'] = isset($item['integral_send_id']) ? $item['integral_send_id'] : '';
    $params['m_id'] = isset($item['m_id']) ? $item['m_id'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);