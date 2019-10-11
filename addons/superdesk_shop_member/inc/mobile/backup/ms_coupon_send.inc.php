<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_coupon_send.class.php');
$ms_coupon_send = new ms_coupon_sendModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['coupon_send_id'] = isset($item['coupon_send_id']) ? $item['coupon_send_id'] : '';
    $params['coupon_send_name'] = isset($item['coupon_send_name']) ? $item['coupon_send_name'] : '';
    $params['coupon_standard_pkcode'] = isset($item['coupon_standard_pkcode']) ? $item['coupon_standard_pkcode'] : '';
    $params['coupon_send_timetype'] = isset($item['coupon_send_timetype']) ? $item['coupon_send_timetype'] : '';
    $params['coupon_send_diytime'] = isset($item['coupon_send_diytime']) ? $item['coupon_send_diytime'] : '';
    $params['coupon_send_number'] = isset($item['coupon_send_number']) ? $item['coupon_send_number'] : '';
    $params['coupon_send_ctime'] = isset($item['coupon_send_ctime']) ? $item['coupon_send_ctime'] : '';
    $params['coupon_send_state'] = isset($item['coupon_send_state']) ? $item['coupon_send_state'] : '';
    $params['e_id'] = isset($item['e_id']) ? $item['e_id'] : '';
    $params['coupon_send_companyid'] = isset($item['coupon_send_companyid']) ? $item['coupon_send_companyid'] : '';
    $params['coupon_send_branch'] = isset($item['coupon_send_branch']) ? $item['coupon_send_branch'] : '';
    $params['coupon_send_department'] = isset($item['coupon_send_department']) ? $item['coupon_send_department'] : '';
    $params['coupon_send_group'] = isset($item['coupon_send_group']) ? $item['coupon_send_group'] : '';
    $params['coupon_send_position'] = isset($item['coupon_send_position']) ? $item['coupon_send_position'] : '';
    $params['coupon_send_welfare'] = isset($item['coupon_send_welfare']) ? $item['coupon_send_welfare'] : '';
    $params['coupon_send_endtime'] = isset($item['coupon_send_endtime']) ? $item['coupon_send_endtime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);