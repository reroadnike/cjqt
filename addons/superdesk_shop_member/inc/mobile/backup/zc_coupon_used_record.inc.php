<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/zc_coupon_used_record.class.php');
$zc_coupon_used_record = new zc_coupon_used_recordModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['r_id'] = isset($item['r_id']) ? $item['r_id'] : '';
    $params['r_coupon_id'] = isset($item['r_coupon_id']) ? $item['r_coupon_id'] : '';
    $params['r_coupon_type'] = isset($item['r_coupon_type']) ? $item['r_coupon_type'] : '';
    $params['r_coupon_price'] = isset($item['r_coupon_price']) ? $item['r_coupon_price'] : '';
    $params['r_goods_id'] = isset($item['r_goods_id']) ? $item['r_goods_id'] : '';
    $params['r_user_id'] = isset($item['r_user_id']) ? $item['r_user_id'] : '';
    $params['r_orderid'] = isset($item['r_orderid']) ? $item['r_orderid'] : '';
    $params['r_ctime'] = isset($item['r_ctime']) ? $item['r_ctime'] : '';
    $params['r_type'] = isset($item['r_type']) ? $item['r_type'] : '';
    $params['r_m_id'] = isset($item['r_m_id']) ? $item['r_m_id'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);