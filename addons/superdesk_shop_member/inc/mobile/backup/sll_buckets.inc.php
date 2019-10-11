<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_buckets.class.php');
$sll_buckets = new sll_bucketsModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['buckets_id'] = isset($item['buckets_id']) ? $item['buckets_id'] : '';
    $params['fans_id'] = isset($item['fans_id']) ? $item['fans_id'] : '';
    $params['get_time'] = isset($item['get_time']) ? $item['get_time'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    $params['number'] = isset($item['number']) ? $item['number'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    $params['goods_id'] = isset($item['goods_id']) ? $item['goods_id'] : '';
    $params['order_id'] = isset($item['order_id']) ? $item['order_id'] : '';
    $params['return_time'] = isset($item['return_time']) ? $item['return_time'] : '';
    $params['goods_name'] = isset($item['goods_name']) ? $item['goods_name'] : '';
    $params['user_name'] = isset($item['user_name']) ? $item['user_name'] : '';
    $params['total_deposit'] = isset($item['total_deposit']) ? $item['total_deposit'] : '';
    $params['buckets_number'] = isset($item['buckets_number']) ? $item['buckets_number'] : '';
    $params['single_deposit'] = isset($item['single_deposit']) ? $item['single_deposit'] : '';
    $params['water_shop'] = isset($item['water_shop']) ? $item['water_shop'] : '';
    $params['company'] = isset($item['company']) ? $item['company'] : '';
    $params['watershop_deposit'] = isset($item['watershop_deposit']) ? $item['watershop_deposit'] : '';
    $params['surplus_deposit'] = isset($item['surplus_deposit']) ? $item['surplus_deposit'] : '';
    $params['empty_bucket'] = isset($item['empty_bucket']) ? $item['empty_bucket'] : '';
    $params['return_bucket'] = isset($item['return_bucket']) ? $item['return_bucket'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);