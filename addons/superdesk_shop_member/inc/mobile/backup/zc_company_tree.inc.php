<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/zc_company_tree.class.php');
$zc_company_tree = new zc_company_treeModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['c_id'] = isset($item['c_id']) ? $item['c_id'] : '';
    $params['c_name'] = isset($item['c_name']) ? $item['c_name'] : '';
    $params['c_pid'] = isset($item['c_pid']) ? $item['c_pid'] : '';
    $params['c_point'] = isset($item['c_point']) ? $item['c_point'] : '';
    $params['c_e_id'] = isset($item['c_e_id']) ? $item['c_e_id'] : '';
    $params['c_ctime'] = isset($item['c_ctime']) ? $item['c_ctime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);