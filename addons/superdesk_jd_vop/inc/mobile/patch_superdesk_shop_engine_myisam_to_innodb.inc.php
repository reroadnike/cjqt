<?php
/**
 * Created by PhpStorm.
 * User: zjh
 * Date: 2018年9月8日 15:04:02
 *
 * 企业采购
 * view-source:http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_engine_myISAM_to_InnoDB
 * view-source:https://wxm.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_engine_myISAM_to_InnoDB
 * view-source:https://bmt.superdesk.cn/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_engine_myISAM_to_InnoDB
 */

global $_W;
global $_GPC;

/**
 * 补丁---- 查找所有表.找出所有MYISAM表
 */

//die();

//SSELECT * FROM information_schema.`TABLES`
//WHERE TABLE_SCHEMA='db_super_desk'
//AND `ENGINE` = 'MyISAM'
//AND TABLE_NAME like 'ims_superdesk_shop_%'
//ORDER BY TABLE_NAME;

$table_array = array();
$table_array = pdo_fetchall(
    ' SELECT * '.
    ' FROM information_schema.TABLES '.
    ' WHERE '.
    '       TABLE_SCHEMA=:table_schema '.
    '       AND TABLE_NAME LIKE "ims_superdesk_shop%" '.
    '       AND ENGINE="MyISAM" ',
    array(
        ':table_schema' => $_W['config']['db']['master']['database']
    )
);

if (count($table_array) > 0) {
    foreach ($table_array as $key => &$value) {
        echo ' ALTER TABLE `' . $value['TABLE_NAME'] . '` ENGINE=InnoDB; ';
//        echo '<br/>';
        echo PHP_EOL;

        //pdo_query(' ALTER TABLE `' . $value['TABLE_NAME'] . '` ENGINE=InnoDB; ');
    }
}

show_json(1, $table_array);