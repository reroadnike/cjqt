<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sys_admin.class.php');
$sys_admin = new sys_adminModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['admin_id'] = isset($item['admin_id']) ? $item['admin_id'] : '';
    $params['admin_name'] = isset($item['admin_name']) ? $item['admin_name'] : '';
    $params['admin_pwd'] = isset($item['admin_pwd']) ? $item['admin_pwd'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);