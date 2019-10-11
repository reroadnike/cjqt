<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_img_multi.class.php');
$wx_img_multi = new wx_img_multiModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['keywords'] = isset($item['keywords']) ? $item['keywords'] : '';
    $params['imgs'] = isset($item['imgs']) ? $item['imgs'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    $params['keyworduuid'] = isset($item['keyworduuid']) ? $item['keyworduuid'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    $params['state'] = isset($item['state']) ? $item['state'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);