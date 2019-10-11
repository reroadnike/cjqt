<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sys_role_submodule.class.php');
$sys_role_submodule = new sys_role_submoduleModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['role_id'] = isset($item['role_id']) ? $item['role_id'] : '';
    $params['sub_id'] = isset($item['sub_id']) ? $item['sub_id'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);