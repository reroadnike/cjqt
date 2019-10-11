<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_water_coin.class.php');
$sll_water_coin = new sll_water_coinModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['water_coin_id'] = isset($item['water_coin_id']) ? $item['water_coin_id'] : '';
    $params['water_coin_code'] = isset($item['water_coin_code']) ? $item['water_coin_code'] : '';
    $params['water_coin_no'] = isset($item['water_coin_no']) ? $item['water_coin_no'] : '';
    $params['water_coin_pkCode'] = isset($item['water_coin_pkCode']) ? $item['water_coin_pkCode'] : '';
    $params['water_card_create_note_pk_code'] = isset($item['water_card_create_note_pk_code']) ? $item['water_card_create_note_pk_code'] : '';
    $params['water_card_pkCode'] = isset($item['water_card_pkCode']) ? $item['water_card_pkCode'] : '';
    $params['water_coin_status'] = isset($item['water_coin_status']) ? $item['water_coin_status'] : '';
    $params['water_coin_ctime'] = isset($item['water_coin_ctime']) ? $item['water_coin_ctime'] : '';
    $params['goods_id'] = isset($item['goods_id']) ? $item['goods_id'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    $params['openid'] = isset($item['openid']) ? $item['openid'] : '';
    $params['water_coin_usetime'] = isset($item['water_coin_usetime']) ? $item['water_coin_usetime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);