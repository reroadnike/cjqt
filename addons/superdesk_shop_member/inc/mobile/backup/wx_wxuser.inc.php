<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_wxuser.class.php');
$wx_wxuser = new wx_wxuserModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['uid'] = isset($item['uid']) ? $item['uid'] : '';
    $params['wxname'] = isset($item['wxname']) ? $item['wxname'] : '';
    $params['winxintype'] = isset($item['winxintype']) ? $item['winxintype'] : '';
    $params['aeskey'] = isset($item['aeskey']) ? $item['aeskey'] : '';
    $params['encode'] = isset($item['encode']) ? $item['encode'] : '';
    $params['appid'] = isset($item['appid']) ? $item['appid'] : '';
    $params['appsecret'] = isset($item['appsecret']) ? $item['appsecret'] : '';
    $params['wxid'] = isset($item['wxid']) ? $item['wxid'] : '';
    $params['weixin'] = isset($item['weixin']) ? $item['weixin'] : '';
    $params['headerpic'] = isset($item['headerpic']) ? $item['headerpic'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['pigsecret'] = isset($item['pigsecret']) ? $item['pigsecret'] : '';
    $params['province'] = isset($item['province']) ? $item['province'] : '';
    $params['city'] = isset($item['city']) ? $item['city'] : '';
    $params['qq'] = isset($item['qq']) ? $item['qq'] : '';
    $params['wxfans'] = isset($item['wxfans']) ? $item['wxfans'] : '';
    $params['typeid'] = isset($item['typeid']) ? $item['typeid'] : '';
    $params['typename'] = isset($item['typename']) ? $item['typename'] : '';
    $params['oauth'] = isset($item['oauth']) ? $item['oauth'] : '';
    $params['oauthinfo'] = isset($item['oauthinfo']) ? $item['oauthinfo'] : '';
    $params['state'] = isset($item['state']) ? $item['state'] : '';
    $params['mchid'] = isset($item['mchid']) ? $item['mchid'] : '';
    $params['wxkey'] = isset($item['wxkey']) ? $item['wxkey'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);