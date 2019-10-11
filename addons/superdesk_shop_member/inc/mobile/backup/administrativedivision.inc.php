<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/administrativedivision.class.php');
$administrativedivision = new administrativedivisionModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['code'] = isset($item['code']) ? $item['code'] : '';
    $params['name'] = isset($item['name']) ? $item['name'] : '';
    $params['parent_code'] = isset($item['parent_code']) ? $item['parent_code'] : '';
    $params['state'] = isset($item['state']) ? $item['state'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);