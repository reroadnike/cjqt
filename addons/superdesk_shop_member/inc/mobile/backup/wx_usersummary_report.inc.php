<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/wx_usersummary_report.class.php');
$wx_usersummary_report = new wx_usersummary_reportModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['subscribed'] = isset($item['subscribed']) ? $item['subscribed'] : '';
    $params['cancle'] = isset($item['cancle']) ? $item['cancle'] : '';
    $params['newadd'] = isset($item['newadd']) ? $item['newadd'] : '';
    $params['cumulate'] = isset($item['cumulate']) ? $item['cumulate'] : '';
    $params['days'] = isset($item['days']) ? $item['days'] : '';
    $params['token'] = isset($item['token']) ? $item['token'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);