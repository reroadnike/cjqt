<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_store_employee.class.php');
$sll_store_employee = new sll_store_employeeModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['employee_id'] = isset($item['employee_id']) ? $item['employee_id'] : '';
    $params['employee_name'] = isset($item['employee_name']) ? $item['employee_name'] : '';
    $params['phone'] = isset($item['phone']) ? $item['phone'] : '';
    $params['employee_cardid'] = isset($item['employee_cardid']) ? $item['employee_cardid'] : '';
    $params['cardid_imageUrl1'] = isset($item['cardid_imageUrl1']) ? $item['cardid_imageUrl1'] : '';
    $params['cardd_imageUrl2'] = isset($item['cardd_imageUrl2']) ? $item['cardd_imageUrl2'] : '';
    $params['store_imageUrl1'] = isset($item['store_imageUrl1']) ? $item['store_imageUrl1'] : '';
    $params['store_imageUrl2'] = isset($item['store_imageUrl2']) ? $item['store_imageUrl2'] : '';
    $params['store_imageUrl3'] = isset($item['store_imageUrl3']) ? $item['store_imageUrl3'] : '';
    $params['employee_imageUrl'] = isset($item['employee_imageUrl']) ? $item['employee_imageUrl'] : '';
    $params['store_id'] = isset($item['store_id']) ? $item['store_id'] : '';
    $params['employee_type'] = isset($item['employee_type']) ? $item['employee_type'] : '';
    $params['stated'] = isset($item['stated']) ? $item['stated'] : '';
    $params['employee_cardimage'] = isset($item['employee_cardimage']) ? $item['employee_cardimage'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);