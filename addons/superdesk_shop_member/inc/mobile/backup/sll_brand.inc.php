<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_brand.class.php');
$sll_brand = new sll_brandModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['brand_id'] = isset($item['brand_id']) ? $item['brand_id'] : '';
    $params['brand_name'] = isset($item['brand_name']) ? $item['brand_name'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);