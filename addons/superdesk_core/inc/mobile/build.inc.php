<?php
/**
* Created by linjinyu.
* User: linjinyu
* Date: 6/19/17
* Time: 11:28 AM
*/
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/build.class.php');
$build = new buildModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['name'] = isset($item['name']) ? $item['name'] : '';
    $params['organizationId'] = isset($item['organizationId']) ? $item['organizationId'] : '';
    $params['vip'] = isset($item['vip']) ? $item['vip'] : '';
    $params['remark'] = isset($item['remark']) ? $item['remark'] : '';
    $params['address'] = isset($item['address']) ? $item['address'] : '';
    $params['lng'] = isset($item['lng']) ? $item['lng'] : '';
    $params['lat'] = isset($item['lat']) ? $item['lat'] : '';
    $params['createTime'] = isset($item['createTime']) ? $item['createTime'] : '';
    $params['creator'] = isset($item['creator']) ? $item['creator'] : '';
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