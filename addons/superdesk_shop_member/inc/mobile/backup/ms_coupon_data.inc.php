<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_coupon_data.class.php');
$ms_coupon_data = new ms_coupon_dataModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['coupon_data_id'] = isset($item['coupon_data_id']) ? $item['coupon_data_id'] : '';
    $params['coupon_standard_pkcode'] = isset($item['coupon_standard_pkcode']) ? $item['coupon_standard_pkcode'] : '';
    $params['coupon_data_name'] = isset($item['coupon_data_name']) ? $item['coupon_data_name'] : '';
    $params['coupon_data_type'] = isset($item['coupon_data_type']) ? $item['coupon_data_type'] : '';
    $params['coupon_data_pkcode'] = isset($item['coupon_data_pkcode']) ? $item['coupon_data_pkcode'] : '';
    $params['coupon_data_usetime'] = isset($item['coupon_data_usetime']) ? $item['coupon_data_usetime'] : '';
    $params['coupon_data_condition'] = isset($item['coupon_data_condition']) ? $item['coupon_data_condition'] : '';
    $params['coupon_data_mjprice'] = isset($item['coupon_data_mjprice']) ? $item['coupon_data_mjprice'] : '';
    $params['coupon_data_expiretype'] = isset($item['coupon_data_expiretype']) ? $item['coupon_data_expiretype'] : '';
    $params['coupon_data_begintime'] = isset($item['coupon_data_begintime']) ? $item['coupon_data_begintime'] : '';
    $params['coupon_data_endtime'] = isset($item['coupon_data_endtime']) ? $item['coupon_data_endtime'] : '';
    $params['coupon_data_ctime'] = isset($item['coupon_data_ctime']) ? $item['coupon_data_ctime'] : '';
    $params['coupon_data_useproducttype'] = isset($item['coupon_data_useproducttype']) ? $item['coupon_data_useproducttype'] : '';
    $params['coupon_data_state'] = isset($item['coupon_data_state']) ? $item['coupon_data_state'] : '';
    $params['coupon_m_id'] = isset($item['coupon_m_id']) ? $item['coupon_m_id'] : '';
    $params['coupon_use_time'] = isset($item['coupon_use_time']) ? $item['coupon_use_time'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);