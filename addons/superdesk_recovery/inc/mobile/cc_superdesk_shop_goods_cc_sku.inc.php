<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/05/15 * Time: 15:09 */
global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_recovery/model/cc_superdesk_shop_goods_cc_sku.class.php');
$cc_superdesk_shop_goods_cc_sku = new cc_superdesk_shop_goods_cc_skuModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['sku'] = isset($item['sku']) ? $item['sku'] : '';
    $params['num'] = isset($item['num']) ? $item['num'] : '';
    $params['ids'] = isset($item['ids']) ? $item['ids'] : '';
    $params['is_delete'] = isset($item['is_delete']) ? $item['is_delete'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);