<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sys_module.class.php');
$sys_module = new sys_moduleModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['module_id'] = isset($item['module_id']) ? $item['module_id'] : '';
    $params['module_name'] = isset($item['module_name']) ? $item['module_name'] : '';
    $params['module_info'] = isset($item['module_info']) ? $item['module_info'] : '';
    $params['module_image'] = isset($item['module_image']) ? $item['module_image'] : '';
    $params['module_url'] = isset($item['module_url']) ? $item['module_url'] : '';
    $params['stat'] = isset($item['stat']) ? $item['stat'] : '';
    $params['orderTag'] = isset($item['orderTag']) ? $item['orderTag'] : '';
    $params['inserttime'] = isset($item['inserttime']) ? $item['inserttime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);