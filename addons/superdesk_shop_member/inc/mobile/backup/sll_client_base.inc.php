<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_client_base.class.php');
$sll_client_base = new sll_client_baseModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['client_id'] = isset($item['client_id']) ? $item['client_id'] : '';
    $params['client_name'] = isset($item['client_name']) ? $item['client_name'] : '';
    $params['address'] = isset($item['address']) ? $item['address'] : '';
    $params['x'] = isset($item['x']) ? $item['x'] : '';
    $params['y'] = isset($item['y']) ? $item['y'] : '';
    $params['pic'] = isset($item['pic']) ? $item['pic'] : '';
    $params['begin_time'] = isset($item['begin_time']) ? $item['begin_time'] : '';
    $params['end_time'] = isset($item['end_time']) ? $item['end_time'] : '';
    $params['qrcode'] = isset($item['qrcode']) ? $item['qrcode'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);