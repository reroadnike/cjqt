<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_water_card_create_note.class.php');
$sll_water_card_create_note = new sll_water_card_create_noteModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['water_card_create_note_id'] = isset($item['water_card_create_note_id']) ? $item['water_card_create_note_id'] : '';
    $params['water_card_create_note_time'] = isset($item['water_card_create_note_time']) ? $item['water_card_create_note_time'] : '';
    $params['water_card_create_note_status'] = isset($item['water_card_create_note_status']) ? $item['water_card_create_note_status'] : '';
    $params['water_card_create_note_fileName'] = isset($item['water_card_create_note_fileName']) ? $item['water_card_create_note_fileName'] : '';
    $params['water_card_create_note_downURL'] = isset($item['water_card_create_note_downURL']) ? $item['water_card_create_note_downURL'] : '';
    $params['water_card_create_note_number'] = isset($item['water_card_create_note_number']) ? $item['water_card_create_note_number'] : '';
    $params['water_card_create_note_pkCode'] = isset($item['water_card_create_note_pkCode']) ? $item['water_card_create_note_pkCode'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    $params['goods_id'] = isset($item['goods_id']) ? $item['goods_id'] : '';
    $params['expire_time'] = isset($item['expire_time']) ? $item['expire_time'] : '';
    $params['water_card_create_note_title'] = isset($item['water_card_create_note_title']) ? $item['water_card_create_note_title'] : '';
    $params['assign_id'] = isset($item['assign_id']) ? $item['assign_id'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);