<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/zc_import_member_note.class.php');
$zc_import_member_note = new zc_import_member_noteModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['n_import_id'] = isset($item['n_import_id']) ? $item['n_import_id'] : '';
    $params['n_import_time'] = isset($item['n_import_time']) ? $item['n_import_time'] : '';
    $params['n_import_status'] = isset($item['n_import_status']) ? $item['n_import_status'] : '';
    $params['n_import_fileName'] = isset($item['n_import_fileName']) ? $item['n_import_fileName'] : '';
    $params['n_import_file_downUrl'] = isset($item['n_import_file_downUrl']) ? $item['n_import_file_downUrl'] : '';
    $params['n_import_number'] = isset($item['n_import_number']) ? $item['n_import_number'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);