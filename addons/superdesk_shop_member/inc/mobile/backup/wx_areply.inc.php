<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_areply.class.php');
$wx_areply = new wx_areplyModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['keyword'] = isset($item['keyword']) ? $item['keyword'] : '';
    $params['content'] = isset($item['content']) ? $item['content'] : '';
    $params['uid'] = isset($item['uid']) ? $item['uid'] : '';
    $params['uname'] = isset($item['uname']) ? $item['uname'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['home'] = isset($item['home']) ? $item['home'] : '';
    $params['check_box'] = isset($item['check_box']) ? $item['check_box'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);