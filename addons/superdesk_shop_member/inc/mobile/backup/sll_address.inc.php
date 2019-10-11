<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_address.class.php');
$sll_address = new sll_addressModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['address_id'] = isset($item['address_id']) ? $item['address_id'] : '';
    $params['address_name'] = isset($item['address_name']) ? $item['address_name'] : '';
    $params['fansid'] = isset($item['fansid']) ? $item['fansid'] : '';
    $params['phone'] = isset($item['phone']) ? $item['phone'] : '';
    $params['user_name'] = isset($item['user_name']) ? $item['user_name'] : '';
    $params['create_time'] = isset($item['create_time']) ? $item['create_time'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['address_state'] = isset($item['address_state']) ? $item['address_state'] : '';
    $params['address_default'] = isset($item['address_default']) ? $item['address_default'] : '';
    $params['province'] = isset($item['province']) ? $item['province'] : '';
    $params['city'] = isset($item['city']) ? $item['city'] : '';
    $params['country'] = isset($item['country']) ? $item['country'] : '';
    $params['pkCode'] = isset($item['pkCode']) ? $item['pkCode'] : '';
    $params['street'] = isset($item['street']) ? $item['street'] : '';
    $params['citycode'] = isset($item['citycode']) ? $item['citycode'] : '';
    $params['community_id'] = isset($item['community_id']) ? $item['community_id'] : '';
    $params['community_name'] = isset($item['community_name']) ? $item['community_name'] : '';
    $params['community_code'] = isset($item['community_code']) ? $item['community_code'] : '';
    $params['provinceCode'] = isset($item['provinceCode']) ? $item['provinceCode'] : '';
    $params['countryCode'] = isset($item['countryCode']) ? $item['countryCode'] : '';
    $params['streetCode'] = isset($item['streetCode']) ? $item['streetCode'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);