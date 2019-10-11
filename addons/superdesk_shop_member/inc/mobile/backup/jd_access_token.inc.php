<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/jd_access_token.class.php');
$jd_access_token = new jd_access_tokenModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['UID'] = isset($item['UID']) ? $item['UID'] : '';
    $params['Access_token'] = isset($item['Access_token']) ? $item['Access_token'] : '';
    $params['Refresh_token'] = isset($item['Refresh_token']) ? $item['Refresh_token'] : '';
    $params['time'] = isset($item['time']) ? $item['time'] : '';
    $params['expires_in'] = isset($item['expires_in']) ? $item['expires_in'] : '';
    $params['refresh_token_expires'] = isset($item['refresh_token_expires']) ? $item['refresh_token_expires'] : '';
    $params['client_id'] = isset($item['client_id']) ? $item['client_id'] : '';
    $params['client_secret'] = isset($item['client_secret']) ? $item['client_secret'] : '';
    $params['update_time'] = isset($item['update_time']) ? $item['update_time'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);