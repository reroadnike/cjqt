<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_wechat_group_list.class.php');
$wx_wechat_group_list = new wx_wechat_group_listModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['g_id'] = isset($item['g_id']) ? $item['g_id'] : '';
    $params['nickname'] = isset($item['nickname']) ? $item['nickname'] : '';
    $params['sex'] = isset($item['sex']) ? $item['sex'] : '';
    $params['province'] = isset($item['province']) ? $item['province'] : '';
    $params['city'] = isset($item['city']) ? $item['city'] : '';
    $params['headimgurl'] = isset($item['headimgurl']) ? $item['headimgurl'] : '';
    $params['subscribe_time'] = isset($item['subscribe_time']) ? $item['subscribe_time'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['openid'] = isset($item['openid']) ? $item['openid'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);