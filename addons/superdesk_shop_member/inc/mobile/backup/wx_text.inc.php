<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_text.class.php');
$wx_text = new wx_textModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['uid'] = isset($item['uid']) ? $item['uid'] : '';
    $params['uname'] = isset($item['uname']) ? $item['uname'] : '';
    $params['keyword'] = isset($item['keyword']) ? $item['keyword'] : '';
    $params['precisions_type'] = isset($item['precisions_type']) ? $item['precisions_type'] : '';
    $params['precisions'] = isset($item['precisions']) ? $item['precisions'] : '';
    $params['usorts'] = isset($item['usorts']) ? $item['usorts'] : '';
    $params['text'] = isset($item['text']) ? $item['text'] : '';
    $params['click'] = isset($item['click']) ? $item['click'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['stauts'] = isset($item['stauts']) ? $item['stauts'] : '';
    $params['keyworduuid'] = isset($item['keyworduuid']) ? $item['keyworduuid'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);