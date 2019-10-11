<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/shop_jd_logs.class.php');
$shop_jd_logs = new shop_jd_logsModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['jd_logs_id'] = isset($item['jd_logs_id']) ? $item['jd_logs_id'] : '';
    $params['jd_logs_event'] = isset($item['jd_logs_event']) ? $item['jd_logs_event'] : '';
    $params['jd_logs_ctime'] = isset($item['jd_logs_ctime']) ? $item['jd_logs_ctime'] : '';
    $params['jd_logs_sendData'] = isset($item['jd_logs_sendData']) ? $item['jd_logs_sendData'] : '';
    $params['jd_logs_receiveData'] = isset($item['jd_logs_receiveData']) ? $item['jd_logs_receiveData'] : '';
    $params['jd_logs_url'] = isset($item['jd_logs_url']) ? $item['jd_logs_url'] : '';
    $params['jd_logs_jdorderid'] = isset($item['jd_logs_jdorderid']) ? $item['jd_logs_jdorderid'] : '';
    $params['jd_logs_orderid'] = isset($item['jd_logs_orderid']) ? $item['jd_logs_orderid'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);