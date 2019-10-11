<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_shop_car.class.php');
$sll_shop_car = new sll_shop_carModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['car_id'] = isset($item['car_id']) ? $item['car_id'] : '';
    $params['client_goods_id'] = isset($item['client_goods_id']) ? $item['client_goods_id'] : '';
    $params['price'] = isset($item['price']) ? $item['price'] : '';
    $params['number'] = isset($item['number']) ? $item['number'] : '';
    $params['total'] = isset($item['total']) ? $item['total'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    $params['fansid'] = isset($item['fansid']) ? $item['fansid'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);