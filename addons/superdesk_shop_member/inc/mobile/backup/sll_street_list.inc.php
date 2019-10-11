<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_street_list.class.php');
$sll_street_list = new sll_street_listModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['street_list_id'] = isset($item['street_list_id']) ? $item['street_list_id'] : '';
    $params['store_id'] = isset($item['store_id']) ? $item['store_id'] : '';
    $params['street_id'] = isset($item['street_id']) ? $item['street_id'] : '';
    $params['pk_code'] = isset($item['pk_code']) ? $item['pk_code'] : '';
    $params['pk1'] = isset($item['pk1']) ? $item['pk1'] : '';
    $params['pk2'] = isset($item['pk2']) ? $item['pk2'] : '';
    $params['administrativeDivision_id'] = isset($item['administrativeDivision_id']) ? $item['administrativeDivision_id'] : '';
    $params['community_id'] = isset($item['community_id']) ? $item['community_id'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);