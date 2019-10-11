<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/zc_import_member.class.php');
$zc_import_member = new zc_import_memberModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['m_id'] = isset($item['m_id']) ? $item['m_id'] : '';
    $params['m_name'] = isset($item['m_name']) ? $item['m_name'] : '';
    $params['m_mobile'] = isset($item['m_mobile']) ? $item['m_mobile'] : '';
    $params['m_id_card'] = isset($item['m_id_card']) ? $item['m_id_card'] : '';
    $params['m_status'] = isset($item['m_status']) ? $item['m_status'] : '';
    $params['m_registration_time'] = isset($item['m_registration_time']) ? $item['m_registration_time'] : '';
    $params['m_account'] = isset($item['m_account']) ? $item['m_account'] : '';
    $params['m_poll_code'] = isset($item['m_poll_code']) ? $item['m_poll_code'] : '';
    $params['m_account_pwd'] = isset($item['m_account_pwd']) ? $item['m_account_pwd'] : '';
    $params['m_fansId'] = isset($item['m_fansId']) ? $item['m_fansId'] : '';
    $params['m_e_id'] = isset($item['m_e_id']) ? $item['m_e_id'] : '';
    $params['m_company'] = isset($item['m_company']) ? $item['m_company'] : '';
    $params['m_branch_company'] = isset($item['m_branch_company']) ? $item['m_branch_company'] : '';
    $params['m_department'] = isset($item['m_department']) ? $item['m_department'] : '';
    $params['m_team'] = isset($item['m_team']) ? $item['m_team'] : '';
    $params['m_position'] = isset($item['m_position']) ? $item['m_position'] : '';
    $params['m_welfare_level'] = isset($item['m_welfare_level']) ? $item['m_welfare_level'] : '';
    $params['m_import_time'] = isset($item['m_import_time']) ? $item['m_import_time'] : '';
    $params['m_card_num'] = isset($item['m_card_num']) ? $item['m_card_num'] : '';
    $params['m_job_num'] = isset($item['m_job_num']) ? $item['m_job_num'] : '';
    $params['m_e_code'] = isset($item['m_e_code']) ? $item['m_e_code'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);