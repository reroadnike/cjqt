<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_coupon_label.class.php');
$ms_coupon_label = new ms_coupon_labelModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['coupon_label_id'] = isset($item['coupon_label_id']) ? $item['coupon_label_id'] : '';
    $params['coupon_label_name'] = isset($item['coupon_label_name']) ? $item['coupon_label_name'] : '';
    $params['coupon_label_number'] = isset($item['coupon_label_number']) ? $item['coupon_label_number'] : '';
    $params['e_id'] = isset($item['e_id']) ? $item['e_id'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);