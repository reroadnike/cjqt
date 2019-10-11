<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_keyword.class.php');
$wx_keyword = new wx_keywordModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['keywordid'] = isset($item['keywordid']) ? $item['keywordid'] : '';
    $params['keyword'] = isset($item['keyword']) ? $item['keyword'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['module'] = isset($item['module']) ? $item['module'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    $params['state'] = isset($item['state']) ? $item['state'] : '';
    $params['keyworduuid'] = isset($item['keyworduuid']) ? $item['keyworduuid'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);