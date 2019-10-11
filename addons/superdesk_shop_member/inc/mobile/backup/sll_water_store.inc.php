<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_water_store.class.php');
$sll_water_store = new sll_water_storeModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['store_id'] = isset($item['store_id']) ? $item['store_id'] : '';
    $params['beginStoreTime'] = isset($item['beginStoreTime']) ? $item['beginStoreTime'] : '';
    $params['endTime'] = isset($item['endTime']) ? $item['endTime'] : '';
    $params['store_name'] = isset($item['store_name']) ? $item['store_name'] : '';
    $params['store_address'] = isset($item['store_address']) ? $item['store_address'] : '';
    $params['store_code'] = isset($item['store_code']) ? $item['store_code'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    $params['userid'] = isset($item['userid']) ? $item['userid'] : '';
    $params['store_city'] = isset($item['store_city']) ? $item['store_city'] : '';
    $params['store_account'] = isset($item['store_account']) ? $item['store_account'] : '';
    $params['phone'] = isset($item['phone']) ? $item['phone'] : '';
    $params['store_user'] = isset($item['store_user']) ? $item['store_user'] : '';
    $params['bank_card_number'] = isset($item['bank_card_number']) ? $item['bank_card_number'] : '';
    $params['bank_name'] = isset($item['bank_name']) ? $item['bank_name'] : '';
    $params['bank_account'] = isset($item['bank_account']) ? $item['bank_account'] : '';
    $params['phone_type'] = isset($item['phone_type']) ? $item['phone_type'] : '';
    $params['store_province'] = isset($item['store_province']) ? $item['store_province'] : '';
    $params['endStoreTime'] = isset($item['endStoreTime']) ? $item['endStoreTime'] : '';
    $params['store_pwd'] = isset($item['store_pwd']) ? $item['store_pwd'] : '';
    $params['store_imageUrl1'] = isset($item['store_imageUrl1']) ? $item['store_imageUrl1'] : '';
    $params['store_imageUrl2'] = isset($item['store_imageUrl2']) ? $item['store_imageUrl2'] : '';
    $params['store_imageUrl3'] = isset($item['store_imageUrl3']) ? $item['store_imageUrl3'] : '';
    $params['store_codeUrl1'] = isset($item['store_codeUrl1']) ? $item['store_codeUrl1'] : '';
    $params['telmunber'] = isset($item['telmunber']) ? $item['telmunber'] : '';
    $params['store_district'] = isset($item['store_district']) ? $item['store_district'] : '';
    $params['store_street'] = isset($item['store_street']) ? $item['store_street'] : '';
    $params['store_addre_id'] = isset($item['store_addre_id']) ? $item['store_addre_id'] : '';
    $params['store_json'] = isset($item['store_json']) ? $item['store_json'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);