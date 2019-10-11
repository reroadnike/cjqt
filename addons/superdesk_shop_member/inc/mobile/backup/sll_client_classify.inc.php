<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_client_classify.class.php');
$sll_client_classify = new sll_client_classifyModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['client_classify_id'] = isset($item['client_classify_id']) ? $item['client_classify_id'] : '';
    $params['client_classify_user_id'] = isset($item['client_classify_user_id']) ? $item['client_classify_user_id'] : '';
    $params['classify_id'] = isset($item['classify_id']) ? $item['classify_id'] : '';
    $params['client_classify_pid'] = isset($item['client_classify_pid']) ? $item['client_classify_pid'] : '';
    $params['client_classify_status'] = isset($item['client_classify_status']) ? $item['client_classify_status'] : '';
    $params['client_classify_ctime'] = isset($item['client_classify_ctime']) ? $item['client_classify_ctime'] : '';
    $params['client_classify_sort'] = isset($item['client_classify_sort']) ? $item['client_classify_sort'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);