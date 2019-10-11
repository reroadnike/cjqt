<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/ms_integral_temporary_member.class.php');
$ms_integral_temporary_member = new ms_integral_temporary_memberModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['temporary_member_id'] = isset($item['temporary_member_id']) ? $item['temporary_member_id'] : '';
    $params['phone'] = isset($item['phone']) ? $item['phone'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    $params['pk_code'] = isset($item['pk_code']) ? $item['pk_code'] : '';
    $params['state'] = isset($item['state']) ? $item['state'] : '';
    $params['mid'] = isset($item['mid']) ? $item['mid'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);