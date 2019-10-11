<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_orders_record.class.php');
$sll_orders_record = new sll_orders_recordModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['newOrdersCount'] = isset($item['newOrdersCount']) ? $item['newOrdersCount'] : '';
    $params['untreatedOrdersCount'] = isset($item['untreatedOrdersCount']) ? $item['untreatedOrdersCount'] : '';
    $params['completedOrdersCount'] = isset($item['completedOrdersCount']) ? $item['completedOrdersCount'] : '';
    $params['ordersPriceSum'] = isset($item['ordersPriceSum']) ? $item['ordersPriceSum'] : '';
    $params['callCount'] = isset($item['callCount']) ? $item['callCount'] : '';
    $params['transactionAmount'] = isset($item['transactionAmount']) ? $item['transactionAmount'] : '';
    $params['newGoodsCount'] = isset($item['newGoodsCount']) ? $item['newGoodsCount'] : '';
    $params['storeId'] = isset($item['storeId']) ? $item['storeId'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);