<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/special_address.class.php');
$special_address = new special_addressModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['name'] = isset($item['name']) ? $item['name'] : '';
    $params['url'] = isset($item['url']) ? $item['url'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    $params['province_code'] = isset($item['province_code']) ? $item['province_code'] : '';
    $params['city_code'] = isset($item['city_code']) ? $item['city_code'] : '';
    $params['country_code'] = isset($item['country_code']) ? $item['country_code'] : '';
    $params['street_code'] = isset($item['street_code']) ? $item['street_code'] : '';
    $params['community_code'] = isset($item['community_code']) ? $item['community_code'] : '';
    $params['province_name'] = isset($item['province_name']) ? $item['province_name'] : '';
    $params['city_name'] = isset($item['city_name']) ? $item['city_name'] : '';
    $params['country_name'] = isset($item['country_name']) ? $item['country_name'] : '';
    $params['street_name'] = isset($item['street_name']) ? $item['street_name'] : '';
    $params['community_name'] = isset($item['community_name']) ? $item['community_name'] : '';
    $params['communityId'] = isset($item['communityId']) ? $item['communityId'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);