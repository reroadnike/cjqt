<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/05/17 * Time: 18:53 */
global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shopv2_elasticsearch/model/elasticsearch_dictionary_category.class.php');
$elasticsearch_dictionary_category = new elasticsearch_dictionary_categoryModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['parentid'] = isset($item['parentid']) ? $item['parentid'] : '';
    $params['name'] = isset($item['name']) ? $item['name'] : '';
    $params['description'] = isset($item['description']) ? $item['description'] : '';
    $params['displayorder'] = isset($item['displayorder']) ? $item['displayorder'] : '';
    $params['enabled'] = isset($item['enabled']) ? $item['enabled'] : '';
    $params['level'] = isset($item['level']) ? $item['level'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);