<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_wechat_group.class.php');
$wx_wechat_group = new wx_wechat_groupModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['wechatgroupid'] = isset($item['wechatgroupid']) ? $item['wechatgroupid'] : '';
    $params['name'] = isset($item['name']) ? $item['name'] : '';
    $params['intro'] = isset($item['intro']) ? $item['intro'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['fanscount'] = isset($item['fanscount']) ? $item['fanscount'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);