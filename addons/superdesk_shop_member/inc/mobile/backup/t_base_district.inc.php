<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/t_base_district.class.php');
$t_base_district = new t_base_districtModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['ID'] = isset($item['ID']) ? $item['ID'] : '';
    $params['NAME'] = isset($item['NAME']) ? $item['NAME'] : '';
    $params['ENG_NAME'] = isset($item['ENG_NAME']) ? $item['ENG_NAME'] : '';
    $params['BRIEF_SPELL'] = isset($item['BRIEF_SPELL']) ? $item['BRIEF_SPELL'] : '';
    $params['DISTRICT_LEVEL'] = isset($item['DISTRICT_LEVEL']) ? $item['DISTRICT_LEVEL'] : '';
    $params['PARENT_ID'] = isset($item['PARENT_ID']) ? $item['PARENT_ID'] : '';
    $params['SEQ_NO'] = isset($item['SEQ_NO']) ? $item['SEQ_NO'] : '';
    $params['CODE'] = isset($item['CODE']) ? $item['CODE'] : '';
    $params['AREA_CODE'] = isset($item['AREA_CODE']) ? $item['AREA_CODE'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);