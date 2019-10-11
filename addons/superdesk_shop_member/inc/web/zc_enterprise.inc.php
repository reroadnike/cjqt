<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=zc_enterprise */

global $_GPC, $_W;
$active = 'zc_enterprise';

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/zc_enterprise.class.php');
$zc_enterprise = new zc_enterpriseModel();

include_once(IA_ROOT . '/addons/superdesk_core/service/VirtualarchitectureService.class.php');
$_virtualarchitectureService = new VirtualarchitectureService();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $zc_enterprise->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id     = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'e_id'                            => $_GPC['e_id'],//
            'e_number'                        => $_GPC['e_number'],// 企业编号
            'e_name'                          => $_GPC['e_name'],// 企业名称
            'e_status'                        => $_GPC['e_status'],// 企业状态1:正常；0：过期
            'e_end_time'                      => $_GPC['e_end_time'],// 到期时间
            'e_ctime'                         => $_GPC['e_ctime'],// 创建时间
            'e_business_license_address'      => $_GPC['e_business_license_address'],// 营业执照地址
            'e_office_address'                => $_GPC['e_office_address'],// 办公地址
            'e_switchboard'                   => $_GPC['e_switchboard'],// 总机
            'e_fax'                           => $_GPC['e_fax'],// 传真
            'e_business_license'              => $_GPC['e_business_license'],// 营业执照
            'e_tax_registration_certificate'  => $_GPC['e_tax_registration_certificate'],// 税务登记证
            'e_organization_code_certificate' => $_GPC['e_organization_code_certificate'],// 组织机构代码证
            'e_uniform_credit_code'           => $_GPC['e_uniform_credit_code'],// 统一信用代码
            'e_province_name'                 => $_GPC['e_province_name'],// 省
            'e_city_name'                     => $_GPC['e_city_name'],// 市
            'e_area_name'                     => $_GPC['e_area_name'],// 区
            'e_street_name'                   => $_GPC['e_street_name'],// 街道
            'e_province_code'                 => $_GPC['e_province_code'],// 省code
            'e_city_code'                     => $_GPC['e_city_code'],// 市code
            'e_area_code'                     => $_GPC['e_area_code'],// 区code
            'e_street_code'                   => $_GPC['e_street_code'],// 街道code

        );
        $zc_enterprise->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('zc_enterprise', array('op' => 'list')), 'success');


    }
    include $this->template('zc_enterprise_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where  = array('id' => $id);

            $zc_enterprise->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('zc_enterprise', array('op' => 'list')), 'success');
    }

    $page      = $_GPC['page'];
    $page_size = 1000;

    $result    = $zc_enterprise->queryAll(array(), $page, $page_size);
    $total     = $result['total'];
    $page      = $result['page'];
    $page_size = $result['page_size'];
    $list      = $result['data'];

    $mapping_count = 0;

    foreach ($list as $index => $item){

        $list[$index]['mapping'] = $_virtualarchitectureService->cacheMappingByCodeNumber($item['e_id'],$item['e_number']);

        $mapping_count += $list[$index]['mapping'];
    }

    $pager = pagination($total, $page, $page_size);

    include $this->template('zc_enterprise_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $zc_enterprise->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $zc_enterprise->delete($id);

    message('删除成功！', referer(), 'success');
}

