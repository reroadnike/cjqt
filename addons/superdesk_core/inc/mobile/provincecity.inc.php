<?php
/**
* Created by linjinyu.
* User: linjinyu
* Date: 6/19/17
* Time: 11:28 AM
*/
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/provincecity.class.php');
$provincecity = new provincecityModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['ID'] = isset($item['ID']) ? $item['ID'] : '';
    $params['type'] = isset($item['type']) ? $item['type'] : '';
    $params['name'] = isset($item['name']) ? $item['name'] : '';
    $params['provinceCode'] = isset($item['provinceCode']) ? $item['provinceCode'] : '';
    $params['cityCode'] = isset($item['cityCode']) ? $item['cityCode'] : '';
    $params['description'] = isset($item['description']) ? $item['description'] : '';
    $params['creator'] = isset($item['creator']) ? $item['creator'] : '';
    $params['createTime'] = isset($item['createTime']) ? $item['createTime'] : '';
    $params['modifier'] = isset($item['modifier']) ? $item['modifier'] : '';
    $params['modifyTime'] = isset($item['modifyTime']) ? $item['modifyTime'] : '';
    $params['isEnabled'] = isset($item['isEnabled']) ? $item['isEnabled'] : '';
    $params['createtime_'] = isset($item['createtime_']) ? $item['createtime_'] : '';
    $params['enabled'] = isset($item['enabled']) ? $item['enabled'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);