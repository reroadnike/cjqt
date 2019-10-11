<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sys_submodule.class.php');
$sys_submodule = new sys_submoduleModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['submodule_id'] = isset($item['submodule_id']) ? $item['submodule_id'] : '';
    $params['mid'] = isset($item['mid']) ? $item['mid'] : '';
    $params['submodule_name'] = isset($item['submodule_name']) ? $item['submodule_name'] : '';
    $params['submodule_info'] = isset($item['submodule_info']) ? $item['submodule_info'] : '';
    $params['submodule_url'] = isset($item['submodule_url']) ? $item['submodule_url'] : '';
    $params['inserttime'] = isset($item['inserttime']) ? $item['inserttime'] : '';
    $params['stat'] = isset($item['stat']) ? $item['stat'] : '';
    $params['orderTag'] = isset($item['orderTag']) ? $item['orderTag'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);