<?php
/**
 * Created by PhpStorm.
 * User: zjh
 * Date: 2018年9月8日 15:04:02
 *
 * 企业采购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_build_window_superdesk_shop_setup_04_insert_core_user
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_build_window_superdesk_shop_setup_04_insert_core_user
 * view-source:https://bmt.superdesk.cn/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_build_window_superdesk_shop_setup_04_insert_core_user
 */

global $_W;
global $_GPC;

/**
 * 补丁---- 查找所有表.找出所有表中有openid字段的表.查找所有用户..对应着用户的openid插入uuid的值
 */

//die();

//正在显示第 0 - 24 行 (共 6122 行, 查询花费 0.0010 秒。)
//SELECT * FROM ims_superdesk_shop_order WHERE member_enterprise_name =''

//获取所有有openid字段的表
$table_array = array();
$table_array = pdo_fetchall(
    ' SELECT ' .
    ' TABLE_SCHEMA,TABLE_NAME,COLUMN_NAME ' .
    ' FROM information_schema.columns ' .
    ' WHERE ' .
    '       TABLE_SCHEMA=:table_schema ' .
    '       AND TABLE_NAME LIKE "ims_superdesk_shop%" ' .
    '       and COLUMN_NAME="openid" ',
    array(
        ':table_schema' => $_W['config']['db']['master']['database']
    )
);


$page      = isset($_GPC['page']) && !empty($_GPC['page']) ? $_GPC['page'] : 1;
$page_size = isset($_GPC['page_size']) && !empty($_GPC['page_size']) ? $_GPC['page_size'] : 200;

//获取到所有用户的id,openid,shop_member_uuid
$memberList = pdo_fetchall(
    ' select ' .
    '       id,openid,core_user ' .
    ' from ' . tablename('superdesk_shop_member') .
    ' where ' .
    '   uniacid = :uniacid ' .
    '   and core_user >0 ' .
    ' limit ' . (($page - 1) * $page_size) . ',' . $page_size,
    array(
        ':uniacid' => $_W['uniacid']
    )
);

if (count($table_array) > 0 and count($memberList) > 0) {

    foreach ($memberList as $mv) {

        foreach ($table_array as &$tv) {

            $table_name = str_replace('ims_', '', $tv['TABLE_NAME']);

            pdo_update($table_name,
                array(
                    'core_user' => $mv['core_user']
                ),
                array(
                    'openid' => $mv['openid']
                )
            );
        }
    }
}

show_json(1, compact('memberList', 'table_array'));