<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/api_user.class.php');
$api_user = new api_userModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['api_user_id'] = isset($item['api_user_id']) ? $item['api_user_id'] : '';
    $params['api_user_pkcode'] = isset($item['api_user_pkcode']) ? $item['api_user_pkcode'] : '';
    $params['api_username'] = isset($item['api_username']) ? $item['api_username'] : '';
    $params['api_password'] = isset($item['api_password']) ? $item['api_password'] : '';
    $params['api_roleid'] = isset($item['api_roleid']) ? $item['api_roleid'] : '';
    $params['api_email'] = isset($item['api_email']) ? $item['api_email'] : '';
    $params['api_linkman'] = isset($item['api_linkman']) ? $item['api_linkman'] : '';
    $params['api_tel'] = isset($item['api_tel']) ? $item['api_tel'] : '';
    $params['api_token'] = isset($item['api_token']) ? $item['api_token'] : '';
    $params['api_token_endtime'] = isset($item['api_token_endtime']) ? $item['api_token_endtime'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);