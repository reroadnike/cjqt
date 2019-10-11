<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_orders_detail.class.php');
$sll_orders_detail = new sll_orders_detailModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['orders_detail_id'] = isset($item['orders_detail_id']) ? $item['orders_detail_id'] : '';
    $params['goodsid'] = isset($item['goodsid']) ? $item['goodsid'] : '';
    $params['pro_price'] = isset($item['pro_price']) ? $item['pro_price'] : '';
    $params['total_price'] = isset($item['total_price']) ? $item['total_price'] : '';
    $params['number'] = isset($item['number']) ? $item['number'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    $params['orderid'] = isset($item['orderid']) ? $item['orderid'] : '';
    $params['jdcOrder'] = isset($item['jdcOrder']) ? $item['jdcOrder'] : '';
    $params['agreementprice'] = isset($item['agreementprice']) ? $item['agreementprice'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);