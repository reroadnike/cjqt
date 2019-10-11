<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_community.class.php');
$sll_community = new sll_communityModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['community_id'] = isset($item['community_id']) ? $item['community_id'] : '';
    $params['community_name'] = isset($item['community_name']) ? $item['community_name'] : '';
    $params['community_code'] = isset($item['community_code']) ? $item['community_code'] : '';
    $params['pcode'] = isset($item['pcode']) ? $item['pcode'] : '';
    $params['community_state'] = isset($item['community_state']) ? $item['community_state'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);