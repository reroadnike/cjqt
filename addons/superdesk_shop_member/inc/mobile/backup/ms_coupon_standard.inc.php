<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_coupon_standard.class.php');
$ms_coupon_standard = new ms_coupon_standardModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['coupon_standard_id'] = isset($item['coupon_standard_id']) ? $item['coupon_standard_id'] : '';
    $params['coupon_standard_pkcode'] = isset($item['coupon_standard_pkcode']) ? $item['coupon_standard_pkcode'] : '';
    $params['coupon_standard_name'] = isset($item['coupon_standard_name']) ? $item['coupon_standard_name'] : '';
    $params['coupon_standard_type'] = isset($item['coupon_standard_type']) ? $item['coupon_standard_type'] : '';
    $params['coupon_standard_usetime'] = isset($item['coupon_standard_usetime']) ? $item['coupon_standard_usetime'] : '';
    $params['coupon_standard_condition'] = isset($item['coupon_standard_condition']) ? $item['coupon_standard_condition'] : '';
    $params['coupon_standard_mjprice'] = isset($item['coupon_standard_mjprice']) ? $item['coupon_standard_mjprice'] : '';
    $params['coupon_standard_expiretype'] = isset($item['coupon_standard_expiretype']) ? $item['coupon_standard_expiretype'] : '';
    $params['coupon_standard_begintime'] = isset($item['coupon_standard_begintime']) ? $item['coupon_standard_begintime'] : '';
    $params['coupon_standard_endtime'] = isset($item['coupon_standard_endtime']) ? $item['coupon_standard_endtime'] : '';
    $params['coupon_standard_ctime'] = isset($item['coupon_standard_ctime']) ? $item['coupon_standard_ctime'] : '';
    $params['coupon_standard_useproducttype'] = isset($item['coupon_standard_useproducttype']) ? $item['coupon_standard_useproducttype'] : '';
    $params['coupon_standard_state'] = isset($item['coupon_standard_state']) ? $item['coupon_standard_state'] : '';
    $params['jsons'] = isset($item['jsons']) ? $item['jsons'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);