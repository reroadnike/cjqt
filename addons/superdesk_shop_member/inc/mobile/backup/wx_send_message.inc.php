<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_send_message.class.php');
$wx_send_message = new wx_send_messageModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['msg_id'] = isset($item['msg_id']) ? $item['msg_id'] : '';
    $params['title'] = isset($item['title']) ? $item['title'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['msgtype'] = isset($item['msgtype']) ? $item['msgtype'] : '';
    $params['text'] = isset($item['text']) ? $item['text'] : '';
    $params['imgids'] = isset($item['imgids']) ? $item['imgids'] : '';
    $params['mediasrc'] = isset($item['mediasrc']) ? $item['mediasrc'] : '';
    $params['mediaid'] = isset($item['mediaid']) ? $item['mediaid'] : '';
    $params['reachcount'] = isset($item['reachcount']) ? $item['reachcount'] : '';
    $params['groupid'] = isset($item['groupid']) ? $item['groupid'] : '';
    $params['openid'] = isset($item['openid']) ? $item['openid'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    $params['send_type'] = isset($item['send_type']) ? $item['send_type'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);