<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/zc_waterstoreandenterprise_relation.class.php');
$zc_waterstoreandenterprise_relation = new zc_waterstoreandenterprise_relationModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['w_e_id'] = isset($item['w_e_id']) ? $item['w_e_id'] : '';
    $params['w_id'] = isset($item['w_id']) ? $item['w_id'] : '';
    $params['e_id'] = isset($item['e_id']) ? $item['e_id'] : '';
    $params['w_e_ctime'] = isset($item['w_e_ctime']) ? $item['w_e_ctime'] : '';
    $params['w_e_status'] = isset($item['w_e_status']) ? $item['w_e_status'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);