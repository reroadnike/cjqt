<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_store_import_user_note.class.php');
$sll_store_import_user_note = new sll_store_import_user_noteModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['store_import_user_note_id'] = isset($item['store_import_user_note_id']) ? $item['store_import_user_note_id'] : '';
    $params['store_import_user_note_ctime'] = isset($item['store_import_user_note_ctime']) ? $item['store_import_user_note_ctime'] : '';
    $params['store_import_user_note_number'] = isset($item['store_import_user_note_number']) ? $item['store_import_user_note_number'] : '';
    $params['store_import_user_note_status'] = isset($item['store_import_user_note_status']) ? $item['store_import_user_note_status'] : '';
    $params['store_id'] = isset($item['store_id']) ? $item['store_id'] : '';
    $params['store_import_user_note_downurl'] = isset($item['store_import_user_note_downurl']) ? $item['store_import_user_note_downurl'] : '';
    $params['store_import_user_note_filename'] = isset($item['store_import_user_note_filename']) ? $item['store_import_user_note_filename'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);