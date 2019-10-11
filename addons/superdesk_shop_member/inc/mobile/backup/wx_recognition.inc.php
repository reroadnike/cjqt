<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_recognition.class.php');
$wx_recognition = new wx_recognitionModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['title'] = isset($item['title']) ? $item['title'] : '';
    $params['attention_num'] = isset($item['attention_num']) ? $item['attention_num'] : '';
    $params['keyword'] = isset($item['keyword']) ? $item['keyword'] : '';
    $params['code_url'] = isset($item['code_url']) ? $item['code_url'] : '';
    $params['scene_id'] = isset($item['scene_id']) ? $item['scene_id'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    $params['groupid'] = isset($item['groupid']) ? $item['groupid'] : '';
    $params['groupname'] = isset($item['groupname']) ? $item['groupname'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);