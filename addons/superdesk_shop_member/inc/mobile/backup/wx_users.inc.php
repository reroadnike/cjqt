<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_users.class.php');
$wx_users = new wx_usersModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['openid'] = isset($item['openid']) ? $item['openid'] : '';
    $params['agentid'] = isset($item['agentid']) ? $item['agentid'] : '';
    $params['inviter'] = isset($item['inviter']) ? $item['inviter'] : '';
    $params['gid'] = isset($item['gid']) ? $item['gid'] : '';
    $params['username'] = isset($item['username']) ? $item['username'] : '';
    $params['mp'] = isset($item['mp']) ? $item['mp'] : '';
    $params['smscount'] = isset($item['smscount']) ? $item['smscount'] : '';
    $params['password'] = isset($item['password']) ? $item['password'] : '';
    $params['email'] = isset($item['email']) ? $item['email'] : '';
    $params['lasttime'] = isset($item['lasttime']) ? $item['lasttime'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    $params['createip'] = isset($item['createip']) ? $item['createip'] : '';
    $params['lastip'] = isset($item['lastip']) ? $item['lastip'] : '';
    $params['diynum'] = isset($item['diynum']) ? $item['diynum'] : '';
    $params['activitynum'] = isset($item['activitynum']) ? $item['activitynum'] : '';
    $params['card_num'] = isset($item['card_num']) ? $item['card_num'] : '';
    $params['card_create_status'] = isset($item['card_create_status']) ? $item['card_create_status'] : '';
    $params['money'] = isset($item['money']) ? $item['money'] : '';
    $params['moneybalance'] = isset($item['moneybalance']) ? $item['moneybalance'] : '';
    $params['spend'] = isset($item['spend']) ? $item['spend'] : '';
    $params['viptime'] = isset($item['viptime']) ? $item['viptime'] : '';
    $params['connectnum'] = isset($item['connectnum']) ? $item['connectnum'] : '';
    $params['lastloginmonth'] = isset($item['lastloginmonth']) ? $item['lastloginmonth'] : '';
    $params['attachmentsize'] = isset($item['attachmentsize']) ? $item['attachmentsize'] : '';
    $params['wechat_card_num'] = isset($item['wechat_card_num']) ? $item['wechat_card_num'] : '';
    $params['serviceUserNum'] = isset($item['serviceUserNum']) ? $item['serviceUserNum'] : '';
    $params['invitecode'] = isset($item['invitecode']) ? $item['invitecode'] : '';
    $params['remark'] = isset($item['remark']) ? $item['remark'] : '';
    $params['business'] = isset($item['business']) ? $item['business'] : '';
    $params['usertplid'] = isset($item['usertplid']) ? $item['usertplid'] : '';
    $params['sysuser'] = isset($item['sysuser']) ? $item['sysuser'] : '';
    $params['wxuserid'] = isset($item['wxuserid']) ? $item['wxuserid'] : '';
    $params['parent_id'] = isset($item['parent_id']) ? $item['parent_id'] : '';
    $params['province'] = isset($item['province']) ? $item['province'] : '';
    $params['city'] = isset($item['city']) ? $item['city'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);