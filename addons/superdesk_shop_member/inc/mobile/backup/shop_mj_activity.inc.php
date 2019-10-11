<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/shop_mj_activity.class.php');
$shop_mj_activity = new shop_mj_activityModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['shop_mj_id'] = isset($item['shop_mj_id']) ? $item['shop_mj_id'] : '';
    $params['shop_mj_name'] = isset($item['shop_mj_name']) ? $item['shop_mj_name'] : '';
    $params['shop_mj_condition'] = isset($item['shop_mj_condition']) ? $item['shop_mj_condition'] : '';
    $params['shop_mj_value'] = isset($item['shop_mj_value']) ? $item['shop_mj_value'] : '';
    $params['shop_mj_level'] = isset($item['shop_mj_level']) ? $item['shop_mj_level'] : '';
    $params['shop_mj_begintime'] = isset($item['shop_mj_begintime']) ? $item['shop_mj_begintime'] : '';
    $params['shop_mj_endtime'] = isset($item['shop_mj_endtime']) ? $item['shop_mj_endtime'] : '';
    $params['shop_mj_status'] = isset($item['shop_mj_status']) ? $item['shop_mj_status'] : '';
    $params['shop_mj_remark'] = isset($item['shop_mj_remark']) ? $item['shop_mj_remark'] : '';
    $params['shop_mj_code'] = isset($item['shop_mj_code']) ? $item['shop_mj_code'] : '';
    $params['shop_mj_activityType'] = isset($item['shop_mj_activityType']) ? $item['shop_mj_activityType'] : '';
    $params['shop_mj_description'] = isset($item['shop_mj_description']) ? $item['shop_mj_description'] : '';
    $params['shop_mj_ctime'] = isset($item['shop_mj_ctime']) ? $item['shop_mj_ctime'] : '';
    $params['shop_mj_productType'] = isset($item['shop_mj_productType']) ? $item['shop_mj_productType'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);