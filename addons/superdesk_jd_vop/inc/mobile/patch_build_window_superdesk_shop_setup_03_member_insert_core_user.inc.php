<?php
/**
 * Created by PhpStorm.
 * User: zjh
 * Date: 2018年9月8日 15:04:02
 *
 * 企业采购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_build_window_superdesk_shop_setup_03_member_insert_core_user
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_build_window_superdesk_shop_setup_03_member_insert_core_user
 * view-source:https://bmt.superdesk.cn/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_build_window_superdesk_shop_setup_03_member_insert_core_user
 */

global $_W;
global $_GPC;

/**
 * 补丁----用户表插入uuid
 */

//die();

//正在显示第 0 - 24 行 (共 6122 行, 查询花费 0.0010 秒。)
//SELECT * FROM ims_superdesk_shop_order WHERE member_enterprise_name =''


$page      = isset($_GPC['page']) && !empty($_GPC['page']) ? $_GPC['page'] : 1;
$page_size = isset($_GPC['page_size']) && !empty($_GPC['page_size']) ? $_GPC['page_size'] : 10000;

//获取到所有用户的id,手机号,项目id,企业id
$memberList = pdo_fetchall(
    ' select ' .
    '       id,mobile,core_enterprise,core_organization ' .
    ' from ' . tablename('superdesk_shop_member') .
    ' where ' .
    '       uniacid = :uniacid ' .
    '       and core_user = 0 ' .
    '       and core_enterprise > 0 ' .
    '       and core_organization > 0 ' .
    ' limit ' . (($page - 1) * $page_size) . ',' . $page_size,
    array(
        ':uniacid' => $_W['uniacid']
    )
);

//echo json_encode($memberList);

if (count($memberList) > 0) {

    foreach ($memberList as $value) {

        $tb_user = pdo_fetch(
            'select ' .
            '       id, userMobile, virtualArchId, organizationId ' .
            ' from ' . tablename('superdesk_core_tb_user') .
            ' where ' .
            '       userMobile=:userMobile ' .
            '       and virtualArchId=:virtualArchId ' .
            '       and organizationId=:organizationId ',
            array(
                ':userMobile'     => $value['mobile'],
                ':virtualArchId'  => $value['core_enterprise'],
                ':organizationId' => $value['core_organization']
            )
        );
        echo json_encode($tb_user) . PHP_EOL;

        if ($tb_user != false) {
            pdo_update('superdesk_shop_member',
                array(
                    'core_user' => $tb_user['id']
                ), array(
                    'id' => $value['id']
                )
            );
        }

    }
}

show_json(1, $memberList);