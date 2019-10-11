<?php
/**
 * Created by linjinyu. 
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 */
global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/zc_enterprise.class.php');
$zc_enterprise = new zc_enterpriseModel();

$json = json_decode(trim(file_get_contents('php://input')), true);

foreach ($json['data'] as $index => $item){
    $params['e_id'] = isset($item['e_id']) ? $item['e_id'] : '';
    $params['e_number'] = isset($item['e_number']) ? $item['e_number'] : '';
    $params['e_name'] = isset($item['e_name']) ? $item['e_name'] : '';
    $params['e_status'] = isset($item['e_status']) ? $item['e_status'] : '';
    $params['e_end_time'] = isset($item['e_end_time']) ? $item['e_end_time'] : '';
    $params['e_ctime'] = isset($item['e_ctime']) ? $item['e_ctime'] : '';
    $params['e_business_license_address'] = isset($item['e_business_license_address']) ? $item['e_business_license_address'] : '';
    $params['e_office_address'] = isset($item['e_office_address']) ? $item['e_office_address'] : '';
    $params['e_switchboard'] = isset($item['e_switchboard']) ? $item['e_switchboard'] : '';
    $params['e_fax'] = isset($item['e_fax']) ? $item['e_fax'] : '';
    $params['e_business_license'] = isset($item['e_business_license']) ? $item['e_business_license'] : '';
    $params['e_tax_registration_certificate'] = isset($item['e_tax_registration_certificate']) ? $item['e_tax_registration_certificate'] : '';
    $params['e_organization_code_certificate'] = isset($item['e_organization_code_certificate']) ? $item['e_organization_code_certificate'] : '';
    $params['e_uniform_credit_code'] = isset($item['e_uniform_credit_code']) ? $item['e_uniform_credit_code'] : '';
    $params['e_province_name'] = isset($item['e_province_name']) ? $item['e_province_name'] : '';
    $params['e_city_name'] = isset($item['e_city_name']) ? $item['e_city_name'] : '';
    $params['e_area_name'] = isset($item['e_area_name']) ? $item['e_area_name'] : '';
    $params['e_street_name'] = isset($item['e_street_name']) ? $item['e_street_name'] : '';
    $params['e_province_code'] = isset($item['e_province_code']) ? $item['e_province_code'] : '';
    $params['e_city_code'] = isset($item['e_city_code']) ? $item['e_city_code'] : '';
    $params['e_area_code'] = isset($item['e_area_code']) ? $item['e_area_code'] : '';
    $params['e_street_code'] = isset($item['e_street_code']) ? $item['e_street_code'] : '';
    pdo_insert($table_name, $params);
}


$data['code'] = 200;
$data['msg'] = '信息';
$data['data'] = '数据结构随具体接口变化';
echo json_encode($data);