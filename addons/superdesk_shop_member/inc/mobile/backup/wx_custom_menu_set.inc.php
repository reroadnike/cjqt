<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_custom_menu_set.class.php');
$wx_custom_menu_set = new wx_custom_menu_setModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['appid'] = isset($item['appid']) ? $item['appid'] : '';
    $params['appsecret'] = isset($item['appsecret']) ? $item['appsecret'] : '';
    $params['access-token'] = isset($item['access-token']) ? $item['access-token'] : '';
    $params['access-time'] = isset($item['access-time']) ? $item['access-time'] : '';
    $params['jsapi-ticket'] = isset($item['jsapi-ticket']) ? $item['jsapi-ticket'] : '';
    $params['jsapi-time'] = isset($item['jsapi-time']) ? $item['jsapi-time'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);