<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/advertisement.class.php');
$advertisement = new advertisementModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['name'] = isset($item['name']) ? $item['name'] : '';
    $params['url'] = isset($item['url']) ? $item['url'] : '';
    $params['imgUrl'] = isset($item['imgUrl']) ? $item['imgUrl'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);