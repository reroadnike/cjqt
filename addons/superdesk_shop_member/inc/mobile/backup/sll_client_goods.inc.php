<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_client_goods.class.php');
$sll_client_goods = new sll_client_goodsModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['client_goods_id'] = isset($item['client_goods_id']) ? $item['client_goods_id'] : '';
    $params['goods_id'] = isset($item['goods_id']) ? $item['goods_id'] : '';
    $params['brand_id'] = isset($item['brand_id']) ? $item['brand_id'] : '';
    $params['classify_id'] = isset($item['classify_id']) ? $item['classify_id'] : '';
    $params['referprice'] = isset($item['referprice']) ? $item['referprice'] : '';
    $params['trueprice'] = isset($item['trueprice']) ? $item['trueprice'] : '';
    $params['isHot'] = isset($item['isHot']) ? $item['isHot'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    $params['sort'] = isset($item['sort']) ? $item['sort'] : '';
    $params['stock'] = isset($item['stock']) ? $item['stock'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);