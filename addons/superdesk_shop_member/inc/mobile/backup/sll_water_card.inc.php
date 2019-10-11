<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_water_card.class.php');
$sll_water_card = new sll_water_cardModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['water_card_id'] = isset($item['water_card_id']) ? $item['water_card_id'] : '';
    $params['water_card_code'] = isset($item['water_card_code']) ? $item['water_card_code'] : '';
    $params['water_card_no'] = isset($item['water_card_no']) ? $item['water_card_no'] : '';
    $params['water_card_url'] = isset($item['water_card_url']) ? $item['water_card_url'] : '';
    $params['water_card_status'] = isset($item['water_card_status']) ? $item['water_card_status'] : '';
    $params['water_card_ctime'] = isset($item['water_card_ctime']) ? $item['water_card_ctime'] : '';
    $params['water_card_create_note_pk_code'] = isset($item['water_card_create_note_pk_code']) ? $item['water_card_create_note_pk_code'] : '';
    $params['water_card_pkCode'] = isset($item['water_card_pkCode']) ? $item['water_card_pkCode'] : '';
    $params['water_card_sellPrice'] = isset($item['water_card_sellPrice']) ? $item['water_card_sellPrice'] : '';
    $params['goods_id'] = isset($item['goods_id']) ? $item['goods_id'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    $params['number'] = isset($item['number']) ? $item['number'] : '';
    $params['proxy_price'] = isset($item['proxy_price']) ? $item['proxy_price'] : '';
    $params['base_price'] = isset($item['base_price']) ? $item['base_price'] : '';
    $params['sale_price'] = isset($item['sale_price']) ? $item['sale_price'] : '';
    $params['activate_id'] = isset($item['activate_id']) ? $item['activate_id'] : '';
    $params['activate_time'] = isset($item['activate_time']) ? $item['activate_time'] : '';
    $params['own_id'] = isset($item['own_id']) ? $item['own_id'] : '';
    $params['get_time'] = isset($item['get_time']) ? $item['get_time'] : '';
    $params['expire_time'] = isset($item['expire_time']) ? $item['expire_time'] : '';
    $params['assign_id'] = isset($item['assign_id']) ? $item['assign_id'] : '';
    $params['water_card_title'] = isset($item['water_card_title']) ? $item['water_card_title'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);