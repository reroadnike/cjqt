<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_unit.class.php');
$sll_unit = new sll_unitModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['unit_id'] = isset($item['unit_id']) ? $item['unit_id'] : '';
    $params['unit_name'] = isset($item['unit_name']) ? $item['unit_name'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);