<?php
/**
 *
 * 修正商城订单merchshow的值
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/15/17
 * Time: 1:56 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_all_tables_change_core_user_by_one_member&new_core_user=10042&old_core_user=4896
 *
 * http://wxn.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_all_tables_change_core_user_by_one_member&new_core_user=10042&old_core_user=4896
 */



global $_GPC, $_W;

$page      = isset($_GPC['page']) && !empty($_GPC['page']) ? $_GPC['page'] : 1;
$page_size = isset($_GPC['page_size']) && !empty($_GPC['page_size']) ? $_GPC['page_size'] : 100;
$old_core_user = $_GPC['old_core_user'];    //旧的core_user
$new_core_user = $_GPC['new_core_user'];    //新的core_user

if(empty($old_core_user)){
    die('$old_core_user is null');
}

if(empty($new_core_user)){
    die('$new_core_user is null');
}

//获取对应的会员表中的数据 根据core_user 即对的那个账号
$old_member = pdo_fetch(
    ' select * '.
    ' from ' . tablename('superdesk_shop_member') .
    ' where core_user = :core_user ',
    array(
        ':core_user' => $old_core_user
    )
);

if(empty($old_member)){
    die('没找到旧帐号');
}

//获取对应的会员表中的数据 根据core_user 即对的那个账号
$new_member = pdo_fetch(
    ' select * '.
    ' from ' . tablename('superdesk_shop_member') .
    ' where core_user = :core_user ',
    array(
        ':core_user' => $new_core_user
    )
);

if(empty($new_member)){
    die('没找到新账号');
}

$table_array = array();
$table_array = pdo_fetchall(
    ' SELECT '.
    ' TABLE_SCHEMA,TABLE_NAME,COLUMN_NAME ' .
    ' FROM information_schema.columns '.
    ' WHERE '.
    '       TABLE_SCHEMA=:table_schema '.
    '       AND TABLE_NAME LIKE "ims_superdesk_shop%" '.
    '       AND COLUMN_NAME="core_user" ',
    array(
        ':table_schema' => $_W['config']['db']['master']['database']
    )
);

if(count($table_array) > 0){

    //4.更新所有表的core_user
    foreach($table_array as $tv){

        if($tv['TABLE_NAME'] == 'ims_superdesk_shop_member'){
            continue 1;
        }

        $table_name = str_replace('ims_','',$tv['TABLE_NAME']);

        pdo_update($table_name,
            array(
                'core_user' => $new_member['core_user'] //更新新的
            ),
            array(
                'core_user' => $old_member['core_user']    //根据旧的
            )
        );
    }
}

show_json(1, compact('old_member','new_member','table_array'));
die;