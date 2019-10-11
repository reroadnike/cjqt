<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wsd_team_invite.class.php');
$wsd_team_invite = new wsd_team_inviteModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['team_invite_id'] = isset($item['team_invite_id']) ? $item['team_invite_id'] : '';
    $params['team_invite_ctime'] = isset($item['team_invite_ctime']) ? $item['team_invite_ctime'] : '';
    $params['team_invite_etime'] = isset($item['team_invite_etime']) ? $item['team_invite_etime'] : '';
    $params['team_invite_state'] = isset($item['team_invite_state']) ? $item['team_invite_state'] : '';
    $params['team_invite_openid'] = isset($item['team_invite_openid']) ? $item['team_invite_openid'] : '';
    $params['team_invite_code'] = isset($item['team_invite_code']) ? $item['team_invite_code'] : '';
    $params['team_invite_tel'] = isset($item['team_invite_tel']) ? $item['team_invite_tel'] : '';
    $params['team_invite_token'] = isset($item['team_invite_token']) ? $item['team_invite_token'] : '';
    $params['team_invite_relation'] = isset($item['team_invite_relation']) ? $item['team_invite_relation'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);