<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/jd_product_duplicate.class.php');
$jd_product_duplicate = new jd_product_duplicateModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['classify_id'] = isset($item['classify_id']) ? $item['classify_id'] : '';
    $params['sku'] = isset($item['sku']) ? $item['sku'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);