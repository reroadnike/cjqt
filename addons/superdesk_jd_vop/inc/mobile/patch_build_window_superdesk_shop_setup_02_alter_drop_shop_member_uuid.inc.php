<?php
/**
 * Created by PhpStorm.
 * User: zjh
 * Date: 2018年9月8日 15:04:02
 *
 * 企业采购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_build_window_superdesk_shop_setup_02_alter_drop_shop_member_uuid
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_build_window_superdesk_shop_setup_02_alter_drop_shop_member_uuid
 * view-source:https://bmt.superdesk.cn/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_build_window_superdesk_shop_setup_02_alter_drop_shop_member_uuid
 */

global $_W;
global $_GPC;

/**
 * 补丁---- 查找所有表.找出所有表中有openid字段的表.加入一个uuid
 */

//die();

//正在显示第 0 - 24 行 (共 6122 行, 查询花费 0.0010 秒。)
//SELECT * FROM ims_superdesk_shop_order WHERE member_enterprise_name =''

$table_array = array();
$table_array = pdo_fetchall(
    ' SELECT '.
    ' TABLE_SCHEMA,TABLE_NAME,COLUMN_NAME ' .
    ' FROM information_schema.columns '.
    ' WHERE '.
    '       TABLE_SCHEMA=:table_schema '.
    '       AND TABLE_NAME LIKE "ims_superdesk_shop%" '.
    '       AND COLUMN_NAME="openid" ',
    array(
        ':table_schema' => $_W['config']['db']['master']['database']
    )
);

if (count($table_array) > 0) {
    foreach ($table_array as $key => &$value) {
        $this_table_field  = pdo_fetchallfields($value['TABLE_NAME']);
        $value['fieldsss'] = $this_table_field;

        if (in_array('shop_member_uuid', $this_table_field)) {

//            您真的要执行“ALTER TABLE `ims_superdesk_shop_member` DROP `shop_member_uuid`;”吗？
            echo ' ALTER TABLE `' . $value['TABLE_NAME'] . '` DROP `shop_member_uuid`; '.PHP_EOL;
            pdo_query(' ALTER TABLE `' . $value['TABLE_NAME'] . '` DROP `shop_member_uuid`; ');
        }
    }
}

show_json(1, $table_array);