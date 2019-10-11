<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_img.class.php');
$wx_img = new wx_imgModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['uid'] = isset($item['uid']) ? $item['uid'] : '';
    $params['uname'] = isset($item['uname']) ? $item['uname'] : '';
    $params['keyword'] = isset($item['keyword']) ? $item['keyword'] : '';
    $params['precisions'] = isset($item['precisions']) ? $item['precisions'] : '';
    $params['text'] = isset($item['text']) ? $item['text'] : '';
    $params['classid'] = isset($item['classid']) ? $item['classid'] : '';
    $params['classname'] = isset($item['classname']) ? $item['classname'] : '';
    $params['pic'] = isset($item['pic']) ? $item['pic'] : '';
    $params['showpic'] = isset($item['showpic']) ? $item['showpic'] : '';
    $params['info'] = isset($item['info']) ? $item['info'] : '';
    $params['url'] = isset($item['url']) ? $item['url'] : '';
    $params['uptatetime'] = isset($item['uptatetime']) ? $item['uptatetime'] : '';
    $params['click'] = isset($item['click']) ? $item['click'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['title'] = isset($item['title']) ? $item['title'] : '';
    $params['usort'] = isset($item['usort']) ? $item['usort'] : '';
    $params['longitude'] = isset($item['longitude']) ? $item['longitude'] : '';
    $params['latitude'] = isset($item['latitude']) ? $item['latitude'] : '';
    $params['type'] = isset($item['type']) ? $item['type'] : '';
    $params['writer'] = isset($item['writer']) ? $item['writer'] : '';
    $params['texttype'] = isset($item['texttype']) ? $item['texttype'] : '';
    $params['usorts'] = isset($item['usorts']) ? $item['usorts'] : '';
    $params['is_focus'] = isset($item['is_focus']) ? $item['is_focus'] : '';
    $params['keyworduuid'] = isset($item['keyworduuid']) ? $item['keyworduuid'] : '';
    $params['stauts'] = isset($item['stauts']) ? $item['stauts'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);