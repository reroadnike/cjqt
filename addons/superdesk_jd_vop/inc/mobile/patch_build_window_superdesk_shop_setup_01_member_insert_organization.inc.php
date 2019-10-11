<?php
/**
 * Created by PhpStorm.
 * User: zjh
 * Date: 2018年9月8日 15:04:02
 *
 * 企业采购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_build_window_superdesk_shop_setup_01_member_insert_organization
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_build_window_superdesk_shop_setup_01_member_insert_organization
 * view-source:https://bmt.superdesk.cn/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_build_window_superdesk_shop_setup_01_member_insert_organization
 */

global $_W;
global $_GPC;

/**
 * 补丁----用户表连接企业表,将企业表中的项目id更新到用户表中..
 * update ims_superdesk_shop_member as m LEFT JOIN ims_superdesk_core_virtualarchitecture as enterprise on enterprise.id = m.core_enterprise
 * set m.core_organization = IFNULL(enterprise.organizationId,0)
 */

//die();

//正在显示第 0 - 24 行 (共 6122 行, 查询花费 0.0010 秒。)
//SELECT * FROM ims_superdesk_shop_order WHERE member_enterprise_name =

//


$page      = isset($_GPC['page']) && !empty($_GPC['page']) ? $_GPC['page'] : 1;
$page_size = isset($_GPC['page_size']) && !empty($_GPC['page_size']) ? $_GPC['page_size'] : 100000;

// mark welfare
$leftjoin = '';
switch (SUPERDESK_SHOPV2_MODE_USER) {
    case 1:// 1 超级前台
        $select   = ' IFNULL(core_enterprise.organizationId,0) as organizationId ';
        $leftjoin = ' join ' . tablename('superdesk_core_virtualarchitecture') . ' as core_enterprise on core_enterprise.id = m.core_enterprise ';
        break;
    case 2:// 2 福利商城
        $select   = ' 0 as organizationId';
        $leftjoin = ' join ' . tablename('superdesk_shop_enterprise_user') . ' as core_enterprise on core_enterprise.id = m.core_enterprise ';
        break;
}

$memberList = pdo_fetchall(
    ' select m.id,' . $select . ' from ' . tablename('superdesk_shop_member') . ' as m ' .
    $leftjoin .
    ' where m.uniacid = :uniacid ' .
//    '   and m.core_organization = 0 ' .
    ' limit ' . (($page - 1) * $page_size) . ',' . $page_size,
    array(
        ':uniacid' => $_W['uniacid']
    )
);

if (count($memberList) > 0) {
    foreach ($memberList as $v) {
        pdo_update('superdesk_shop_member',
            array(
                'core_organization' => $v['organizationId'],
            ), array(
                'id' => $v['id']
            )
        );
    }
}

show_json(1, $memberList);