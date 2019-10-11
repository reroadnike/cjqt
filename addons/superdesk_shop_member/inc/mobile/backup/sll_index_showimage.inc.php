<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_index_showimage.class.php');
$sll_index_showimage = new sll_index_showimageModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['img_name'] = isset($item['img_name']) ? $item['img_name'] : '';
    $params['img_url'] = isset($item['img_url']) ? $item['img_url'] : '';
    $params['status'] = isset($item['status']) ? $item['status'] : '';
    $params['user_id'] = isset($item['user_id']) ? $item['user_id'] : '';
    $params['ctime'] = isset($item['ctime']) ? $item['ctime'] : '';
    $params['sort'] = isset($item['sort']) ? $item['sort'] : '';
    $params['fileUrl'] = isset($item['fileUrl']) ? $item['fileUrl'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);