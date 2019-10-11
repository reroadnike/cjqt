<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_custom_menu_class.class.php');
$wx_custom_menu_class = new wx_custom_menu_classModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['pid'] = isset($item['pid']) ? $item['pid'] : '';
    $params['title'] = isset($item['title']) ? $item['title'] : '';
    $params['keyword'] = isset($item['keyword']) ? $item['keyword'] : '';
    $params['url'] = isset($item['url']) ? $item['url'] : '';
    $params['is_show'] = isset($item['is_show']) ? $item['is_show'] : '';
    $params['sort'] = isset($item['sort']) ? $item['sort'] : '';
    $params['wxsys'] = isset($item['wxsys']) ? $item['wxsys'] : '';
    $params['text'] = isset($item['text']) ? $item['text'] : '';
    $params['emoji_code'] = isset($item['emoji_code']) ? $item['emoji_code'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);