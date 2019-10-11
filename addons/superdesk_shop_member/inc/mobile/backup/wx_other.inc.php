<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_other.class.php');
$wx_other = new wx_otherModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['keyword'] = isset($item['keyword']) ? $item['keyword'] : '';
    $params['info'] = isset($item['info']) ? $item['info'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);