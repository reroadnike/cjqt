<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_orders.class.php');
$sll_orders = new sll_ordersModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['orders_id'] = isset($item['orders_id']) ? $item['orders_id'] : '';
    $params['fansid'] = isset($item['fansid']) ? $item['fansid'] : '';
    $params['openid'] = isset($item['openid']) ? $item['openid'] : '';
    $params['tel'] = isset($item['tel']) ? $item['tel'] : '';
    $params['payconf'] = isset($item['payconf']) ? $item['payconf'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    $params['orderPrice'] = isset($item['orderPrice']) ? $item['orderPrice'] : '';
    $params['isPay'] = isset($item['isPay']) ? $item['isPay'] : '';
    $params['state'] = isset($item['state']) ? $item['state'] : '';
    $params['type'] = isset($item['type']) ? $item['type'] : '';
    $params['scheduleTime'] = isset($item['scheduleTime']) ? $item['scheduleTime'] : '';
    $params['handletime'] = isset($item['handletime']) ? $item['handletime'] : '';
    $params['endTime'] = isset($item['endTime']) ? $item['endTime'] : '';
    $params['linkname'] = isset($item['linkname']) ? $item['linkname'] : '';
    $params['address'] = isset($item['address']) ? $item['address'] : '';
    $params['trackingname'] = isset($item['trackingname']) ? $item['trackingname'] : '';
    $params['paytime'] = isset($item['paytime']) ? $item['paytime'] : '';
    $params['orderid'] = isset($item['orderid']) ? $item['orderid'] : '';
    $params['payNumber'] = isset($item['payNumber']) ? $item['payNumber'] : '';
    $params['remark'] = isset($item['remark']) ? $item['remark'] : '';
    $params['kfRemark'] = isset($item['kfRemark']) ? $item['kfRemark'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    $params['wx_users_id'] = isset($item['wx_users_id']) ? $item['wx_users_id'] : '';
    $params['isNew'] = isset($item['isNew']) ? $item['isNew'] : '';
    $params['main_id'] = isset($item['main_id']) ? $item['main_id'] : '';
    $params['returnMsg'] = isset($item['returnMsg']) ? $item['returnMsg'] : '';
    $params['order_coupin_price'] = isset($item['order_coupin_price']) ? $item['order_coupin_price'] : '';
    $params['order_original_price'] = isset($item['order_original_price']) ? $item['order_original_price'] : '';
    $params['order_integral_price'] = isset($item['order_integral_price']) ? $item['order_integral_price'] : '';
    $params['jdorderid'] = isset($item['jdorderid']) ? $item['jdorderid'] : '';
    $params['jdstate'] = isset($item['jdstate']) ? $item['jdstate'] : '';
    $params['cancelTime'] = isset($item['cancelTime']) ? $item['cancelTime'] : '';
    $params['freight'] = isset($item['freight']) ? $item['freight'] : '';
    $params['freight_result'] = isset($item['freight_result']) ? $item['freight_result'] : '';
    $params['e_id'] = isset($item['e_id']) ? $item['e_id'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);