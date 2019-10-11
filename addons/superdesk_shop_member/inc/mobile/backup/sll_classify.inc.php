<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/sll_classify.class.php');
$_sll_classifyModel = new sll_classifyModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['classify_id'] = isset($item['classify_id']) ? $item['classify_id'] : '';
    $params['classify_name'] = isset($item['classify_name']) ? $item['classify_name'] : '';
    $params['classify_status'] = isset($item['classify_status']) ? $item['classify_status'] : '';
    $params['classify_pic'] = isset($item['classify_pic']) ? $item['classify_pic'] : '';
    $params['classify_sort'] = isset($item['classify_sort']) ? $item['classify_sort'] : '';
    $params['classify_ctime'] = isset($item['classify_ctime']) ? $item['classify_ctime'] : '';
    $params['classify_pid'] = isset($item['classify_pid']) ? $item['classify_pid'] : '';
    $params['page_num'] = isset($item['page_num']) ? $item['page_num'] : '';
    $params['classify_isshow'] = isset($item['classify_isshow']) ? $item['classify_isshow'] : '';
    $params['classify_main_pic'] = isset($item['classify_main_pic']) ? $item['classify_main_pic'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);