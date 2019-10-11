<?php
/**
 *
 * 修复openid变更的其他表数据
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 12/15/17
 * Time: 1:56 PM
 *
 * http://192.168.1.124/superdesk/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_all_tables_change_openid_by_core_user
 *
 * http://wxn.avic-s.com/app/index.php?i=16&c=entry&m=superdesk_jd_vop&do=patch_superdesk_shop_all_tables_change_openid_by_core_user
 */



global $_GPC, $_W;

//获取对应的会员表中的数据 根据core_user 即对的那个账号
$error_member = pdo_fetchall(
    ' SELECT DISTINCT m.core_user,o.openid as old_openid,m.openid as new_openid '.
    ' from ' . tablename('superdesk_shop_member') . ' as m ' .
    ' LEFT JOIN ' . tablename('superdesk_shop_order') . ' as o on o.core_user = m.core_user ' .
    ' where m.openid != o.openid '
);
//var_dump(123);
//var_dump($error_member);die;

$tables = pdo_fetchall('SHOW TABLES like \'%_superdesk_shop_%\'');

if(count($error_member) > 0){
    foreach($error_member as $mk => $mv){
        if(!empty($mv['core_user']) && !empty($mv['old_openid']) && !empty($mv['new_openid'])){
            foreach ($tables as $k => $v) {

                $v = array_values($v);

                $tablename = str_replace($_W['config']['db']['tablepre'], '', $v[0]);

                if (pdo_fieldexists($tablename, 'openid') && pdo_fieldexists($tablename, 'core_user') && pdo_fieldexists($tablename, 'uniacid')) {
                    pdo_update(
                        $tablename,
                        array(
                            'openid' => $mv['new_openid']
                        ),
                        array(
                            'uniacid' => $_W['uniacid'],
                            'core_user' => $mv['core_user'],
                            'openid'  => $mv['old_openid']
                        )
                    );
                }

            }
        }
    }
}

show_json(1, $error_member);
die;