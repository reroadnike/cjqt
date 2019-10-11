<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sys_role.class.php');
$sys_role = new sys_roleModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['role_id'] = isset($item['role_id']) ? $item['role_id'] : '';
    $params['role_name'] = isset($item['role_name']) ? $item['role_name'] : '';
    $params['role_orderTag'] = isset($item['role_orderTag']) ? $item['role_orderTag'] : '';
    $params['role_stat'] = isset($item['role_stat']) ? $item['role_stat'] : '';
    $params['role_inserttime'] = isset($item['role_inserttime']) ? $item['role_inserttime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);