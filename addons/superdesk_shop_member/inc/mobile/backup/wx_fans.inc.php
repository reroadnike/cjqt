<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_fans.class.php');
$wx_fans = new wx_fansModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['fansId'] = isset($item['fansId']) ? $item['fansId'] : '';
    $params['openId'] = isset($item['openId']) ? $item['openId'] : '';
    $params['nickName'] = isset($item['nickName']) ? $item['nickName'] : '';
    $params['headImgUrl'] = isset($item['headImgUrl']) ? $item['headImgUrl'] : '';
    $params['telePhone'] = isset($item['telePhone']) ? $item['telePhone'] : '';
    $params['password'] = isset($item['password']) ? $item['password'] : '';
    $params['wxProvince'] = isset($item['wxProvince']) ? $item['wxProvince'] : '';
    $params['city'] = isset($item['city']) ? $item['city'] : '';
    $params['sex'] = isset($item['sex']) ? $item['sex'] : '';
    $params['is_subscribed'] = isset($item['is_subscribed']) ? $item['is_subscribed'] : '';
    $params['is_wsd_user'] = isset($item['is_wsd_user']) ? $item['is_wsd_user'] : '';
    $params['version'] = isset($item['version']) ? $item['version'] : '';
    $params['created_time'] = isset($item['created_time']) ? $item['created_time'] : '';
    $params['modified_time'] = isset($item['modified_time']) ? $item['modified_time'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['marking_time'] = isset($item['marking_time']) ? $item['marking_time'] : '';
    $params['scene_str'] = isset($item['scene_str']) ? $item['scene_str'] : '';
    $params['fansType'] = isset($item['fansType']) ? $item['fansType'] : '';
    $params['language'] = isset($item['language']) ? $item['language'] : '';
    $params['levelid'] = isset($item['levelid']) ? $item['levelid'] : '';
    $params['userName'] = isset($item['userName']) ? $item['userName'] : '';
    $params['account'] = isset($item['account']) ? $item['account'] : '';
    $params['pwd'] = isset($item['pwd']) ? $item['pwd'] : '';
    $params['salerid'] = isset($item['salerid']) ? $item['salerid'] : '';
    $params['logintype'] = isset($item['logintype']) ? $item['logintype'] : '';
    $params['stated'] = isset($item['stated']) ? $item['stated'] : '';
    $params['pointPwd'] = isset($item['pointPwd']) ? $item['pointPwd'] : '';
    $params['userInfoAddress'] = isset($item['userInfoAddress']) ? $item['userInfoAddress'] : '';
    $params['storeQRCode'] = isset($item['storeQRCode']) ? $item['storeQRCode'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);