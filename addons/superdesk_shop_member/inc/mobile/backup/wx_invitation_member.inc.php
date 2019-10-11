<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_invitation_member.class.php');
$wx_invitation_member = new wx_invitation_memberModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['phone'] = isset($item['phone']) ? $item['phone'] : '';
    $params['name'] = isset($item['name']) ? $item['name'] : '';
    $params['remarks'] = isset($item['remarks']) ? $item['remarks'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['visitcode'] = isset($item['visitcode']) ? $item['visitcode'] : '';
    $params['job'] = isset($item['job']) ? $item['job'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);