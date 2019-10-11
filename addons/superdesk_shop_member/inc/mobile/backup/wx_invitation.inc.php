<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_invitation.class.php');
$wx_invitation = new wx_invitationModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['picurl'] = isset($item['picurl']) ? $item['picurl'] : '';
    $params['title'] = isset($item['title']) ? $item['title'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    $params['visitcount'] = isset($item['visitcount']) ? $item['visitcount'] : '';
    $params['visitcode'] = isset($item['visitcode']) ? $item['visitcode'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['sharepic'] = isset($item['sharepic']) ? $item['sharepic'] : '';
    $params['sharemusic'] = isset($item['sharemusic']) ? $item['sharemusic'] : '';
    $params['sharetitle'] = isset($item['sharetitle']) ? $item['sharetitle'] : '';
    $params['sharecontext'] = isset($item['sharecontext']) ? $item['sharecontext'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);