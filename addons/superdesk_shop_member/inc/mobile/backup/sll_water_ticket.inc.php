<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_water_ticket.class.php');
$sll_water_ticket = new sll_water_ticketModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['water_ticket_id'] = isset($item['water_ticket_id']) ? $item['water_ticket_id'] : '';
    $params['water_ticket_name'] = isset($item['water_ticket_name']) ? $item['water_ticket_name'] : '';
    $params['water_ticket_type'] = isset($item['water_ticket_type']) ? $item['water_ticket_type'] : '';
    $params['water_ticket_sn'] = isset($item['water_ticket_sn']) ? $item['water_ticket_sn'] : '';
    $params['water_ticket_ctime'] = isset($item['water_ticket_ctime']) ? $item['water_ticket_ctime'] : '';
    $params['water_ticket_status'] = isset($item['water_ticket_status']) ? $item['water_ticket_status'] : '';
    $params['water_ticket_endtime'] = isset($item['water_ticket_endtime']) ? $item['water_ticket_endtime'] : '';
    $params['fans_id'] = isset($item['fans_id']) ? $item['fans_id'] : '';
    $params['goods_id'] = isset($item['goods_id']) ? $item['goods_id'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    $params['water_ticket_gettime'] = isset($item['water_ticket_gettime']) ? $item['water_ticket_gettime'] : '';
    $params['water_ticket_usetime'] = isset($item['water_ticket_usetime']) ? $item['water_ticket_usetime'] : '';
    $params['water_ticket_price'] = isset($item['water_ticket_price']) ? $item['water_ticket_price'] : '';
    $params['water_ticket_create_note_pkCode'] = isset($item['water_ticket_create_note_pkCode']) ? $item['water_ticket_create_note_pkCode'] : '';
    $params['orderid'] = isset($item['orderid']) ? $item['orderid'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);