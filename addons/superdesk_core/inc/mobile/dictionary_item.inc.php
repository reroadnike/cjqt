<?php
/**
* Created by linjinyu.
* User: linjinyu
* Date: 6/19/17
* Time: 11:28 AM
*/
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/dictionary_item.class.php');
$dictionary_item = new dictionary_itemModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['itemcode'] = isset($item['itemcode']) ? $item['itemcode'] : '';
    $params['itemname'] = isset($item['itemname']) ? $item['itemname'] : '';
    $params['groupid'] = isset($item['groupid']) ? $item['groupid'] : '';
    $params['isenabled'] = isset($item['isenabled']) ? $item['isenabled'] : '';
    $params['oprateversion'] = isset($item['oprateversion']) ? $item['oprateversion'] : '';
    $params['opratetype'] = isset($item['opratetype']) ? $item['opratetype'] : '';
    $params['createby'] = isset($item['createby']) ? $item['createby'] : '';
    $params['lastupdateby'] = isset($item['lastupdateby']) ? $item['lastupdateby'] : '';
    $params['orderno'] = isset($item['orderno']) ? $item['orderno'] : '';
    $params['lastupdatetime'] = isset($item['lastupdatetime']) ? $item['lastupdatetime'] : '';
    $params['createtime_'] = isset($item['createtime_']) ? $item['createtime_'] : '';
    $params['enabled'] = isset($item['enabled']) ? $item['enabled'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);