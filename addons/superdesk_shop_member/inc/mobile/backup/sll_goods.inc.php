<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_goods.class.php');
$sll_goods = new sll_goodsModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['goods_id'] = isset($item['goods_id']) ? $item['goods_id'] : '';
    $params['brand_id'] = isset($item['brand_id']) ? $item['brand_id'] : '';
    $params['name'] = isset($item['name']) ? $item['name'] : '';
    $params['referprice'] = isset($item['referprice']) ? $item['referprice'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    $params['pic'] = isset($item['pic']) ? $item['pic'] : '';
    $params['introductions'] = isset($item['introductions']) ? $item['introductions'] : '';
    $params['model'] = isset($item['model']) ? $item['model'] : '';
    $params['content'] = isset($item['content']) ? $item['content'] : '';
    $params['number'] = isset($item['number']) ? $item['number'] : '';
    $params['type'] = isset($item['type']) ? $item['type'] : '';
    $params['toGoodsid'] = isset($item['toGoodsid']) ? $item['toGoodsid'] : '';
    $params['sort'] = isset($item['sort']) ? $item['sort'] : '';
    $params['classify_id'] = isset($item['classify_id']) ? $item['classify_id'] : '';
    $params['stock'] = isset($item['stock']) ? $item['stock'] : '';
    $params['unit_id'] = isset($item['unit_id']) ? $item['unit_id'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    $params['oldprice'] = isset($item['oldprice']) ? $item['oldprice'] : '';
    $params['origin'] = isset($item['origin']) ? $item['origin'] : '';
    $params['sku'] = isset($item['sku']) ? $item['sku'] : '';
    $params['agreementprice'] = isset($item['agreementprice']) ? $item['agreementprice'] : '';
    $params['modified_at'] = isset($item['modified_at']) ? $item['modified_at'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);