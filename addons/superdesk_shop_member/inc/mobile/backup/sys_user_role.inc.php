<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sys_user_role.class.php');
$sys_user_role = new sys_user_roleModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['user_role_id'] = isset($item['user_role_id']) ? $item['user_role_id'] : '';
    $params['role_id'] = isset($item['role_id']) ? $item['role_id'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);