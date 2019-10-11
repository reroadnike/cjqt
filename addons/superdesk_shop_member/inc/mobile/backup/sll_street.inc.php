<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_street.class.php');
$sll_street = new sll_streetModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['street_id'] = isset($item['street_id']) ? $item['street_id'] : '';
    $params['street_name'] = isset($item['street_name']) ? $item['street_name'] : '';
    $params['pCode'] = isset($item['pCode']) ? $item['pCode'] : '';
    $params['street_code'] = isset($item['street_code']) ? $item['street_code'] : '';
    $params['street_state'] = isset($item['street_state']) ? $item['street_state'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);