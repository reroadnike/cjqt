<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/shop_collection.class.php');
$shop_collection = new shop_collectionModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['collectionid'] = isset($item['collectionid']) ? $item['collectionid'] : '';
    $params['goodsid'] = isset($item['goodsid']) ? $item['goodsid'] : '';
    $params['showPrice'] = isset($item['showPrice']) ? $item['showPrice'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    $params['fansid'] = isset($item['fansid']) ? $item['fansid'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);