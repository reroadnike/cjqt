<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_water_card_no.class.php');
$sll_water_card_no = new sll_water_card_noModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['water_card_no_id'] = isset($item['water_card_no_id']) ? $item['water_card_no_id'] : '';
    $params['water_card_no_beg'] = isset($item['water_card_no_beg']) ? $item['water_card_no_beg'] : '';
    $params['water_card_no_end'] = isset($item['water_card_no_end']) ? $item['water_card_no_end'] : '';
    $params['water_card_no_ctime'] = isset($item['water_card_no_ctime']) ? $item['water_card_no_ctime'] : '';
    $params['water_card_create_note_pkCode'] = isset($item['water_card_create_note_pkCode']) ? $item['water_card_create_note_pkCode'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);