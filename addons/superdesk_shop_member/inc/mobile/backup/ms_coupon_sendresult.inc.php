<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_coupon_sendresult.class.php');
$ms_coupon_sendresult = new ms_coupon_sendresultModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['coupon_sendresult_id'] = isset($item['coupon_sendresult_id']) ? $item['coupon_sendresult_id'] : '';
    $params['coupon_sendresult_ctime'] = isset($item['coupon_sendresult_ctime']) ? $item['coupon_sendresult_ctime'] : '';
    $params['e_id'] = isset($item['e_id']) ? $item['e_id'] : '';
    $params['coupon_sendresult_type'] = isset($item['coupon_sendresult_type']) ? $item['coupon_sendresult_type'] : '';
    $params['coupon_standard_pkcode'] = isset($item['coupon_standard_pkcode']) ? $item['coupon_standard_pkcode'] : '';
    $params['coupon_sendresult_state'] = isset($item['coupon_sendresult_state']) ? $item['coupon_sendresult_state'] : '';
    $params['coupon_send_id'] = isset($item['coupon_send_id']) ? $item['coupon_send_id'] : '';
    $params['m_id'] = isset($item['m_id']) ? $item['m_id'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);