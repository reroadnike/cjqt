<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_integral_label.class.php');
$ms_integral_label = new ms_integral_labelModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['integral_label_id'] = isset($item['integral_label_id']) ? $item['integral_label_id'] : '';
    $params['integral_label_name'] = isset($item['integral_label_name']) ? $item['integral_label_name'] : '';
    $params['integral_label_number'] = isset($item['integral_label_number']) ? $item['integral_label_number'] : '';
    $params['e_id'] = isset($item['e_id']) ? $item['e_id'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);