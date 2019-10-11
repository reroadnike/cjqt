<?php
/**
 *
 * 修正商城订单merchshow的值
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/15/17
 * Time: 1:56 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_all_tables_change_core_user
 *
 * http://wxn.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_all_tables_change_core_user
 */


//if($entity['isEnabled'] == 0 && !empty($entity['newUserId'])){
//    //1.获取旧记录
//    $oldUser = User::where('core_user',$entity['id'])->first();
//
//    //2.更新订单表中的项目
//    Order::where('member_organization_id',$oldUser['organizationId'])->update(['member_organization_id'=>$entity['organizationId']]);
//
//    //3.更新订单表中的企业
//    $enterprise = Virtualarchitecture::where('id',$entity['virtualArchId'])->first();
//    Order::where('member_enterprise_id',$oldUser['virtualArchId'])
//        ->update([
//            'member_enterprise_id' => $entity['virtualArchId'],
//            'member_enterprise_name' => $enterprise['name']
//        ]);
//
//    //4.更新其它有core_user的表
//}

global $_GPC, $_W;

$page      = isset($_GPC['page']) && !empty($_GPC['page']) ? $_GPC['page'] : 1;
$page_size = isset($_GPC['page_size']) && !empty($_GPC['page_size']) ? $_GPC['page_size'] : 100;

//获取tb_user中core_user有更新的.即newUserId>0的. 获取到旧的和新的
$memberList = pdo_fetchall(
    ' select '.
    '       old_tb.id,old_tb.newUserId, ' .
    '       old_tb.virtualArchId as old_virtual,old_tb.organizationId as old_org, ' .
    '       new_tb.virtualArchId as new_virtual,new_tb.organizationId as new_org, ' .
    '       enterprise.name as enterprise_name ' .
    ' from ' . tablename('superdesk_core_tb_user') . ' as old_tb ' .
    ' left join ' . tablename('superdesk_core_tb_user') . ' as new_tb on new_tb.id = old_tb.newUserId ' .
    ' left join ' . tablename('superdesk_core_virtualarchitecture') . ' as enterprise on enterprise.id = new_tb.virtualArchId ' .
    ' where old_tb.newUserId > 0 and old_tb.isEnabled = 0 ' .
    ' limit ' . (($page - 1) * $page_size) . ',' . $page_size
);

$memberCount = pdo_fetchcolumn(
    ' select count(1) ' .
    ' from ' . tablename('superdesk_core_tb_user') .
    ' where newUserId > 0 and isEnabled = 0 '
);


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

if(count($table_array) > 0 and count($memberList) > 0){

    foreach ($memberList as $mv) {
        //1.更新shop_member表
        pdo_update('superdesk_shop_member',
            array(
                'core_user' => $mv['newUserId'],
                'core_organization' => $mv['new_org'],
                'core_enterprise' => $mv['new_virtual'],
            ),
            array(
                'core_user' => $mv['id']
            )
        );

        //2.更新订单表中的项目
        pdo_update('superdesk_shop_order',
            array(
                'member_organization_id' => $mv['new_org']   //更新新的
            ),
            array(
                'member_organization_id' => $mv['old_org']   //根据旧的
            )
        );

        //3.更新订单表中的企业
        pdo_update('superdesk_shop_order',
            array(
                'member_enterprise_id'   => $mv['new_virtual'],  //更新新的
                'member_enterprise_name' => $mv['enterprise_name']   //更新新的
            ),
            array(
                'member_enterprise_id'   => $mv['old_virtual']   //根据旧的
            )
        );

        //4.更新其他表的core_user
        foreach($table_array as $tv){

            $table_name = str_replace('ims_','',$tv['TABLE_NAME']);

            pdo_update($table_name,
                array(
                    'core_user' => $mv['newUserId'] //更新新的
                ),
                array(
                    'core_user' => $mv['id']    //根据旧的
                )
            );
        }
    }
}

show_json(1, compact('memberCount','memberList','table_array'));
die;