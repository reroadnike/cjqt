<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/26/17
 * Time: 4:52 PM
 *
 *
 */

//c:user
//a:permission
//do:module
//
//m:superdesk_boardroom_4school
//uid:23
//uniacid:16

global $_W, $_GPC;

$id = intval($_GPC['id']);
if ($id) {
    $core_users = pdo_fetch("SELECT * FROM" . tablename('superdesk_core_users') . "WHERE id=:id ", array(':id' => $id));
    $mmenus = explode(',', $core_users['menus']);
}

//var_dump($core_users);
//array(8) {
// ["id"]=> string(1) "5"
// ["uniacid"]=> string(2) "16"
// ["uid"]=> string(2) "23"
// ["organization_code"]=> string(9) "TJ-TJSWDX"
// ["virtual_code"]=> string(4) "1279" ["menus"]=> NULL
// ["balance"]=> NULL
// ["commission"]=> NULL
// }


$uniacid = intval($_W['uniacid']);
$uid = intval($core_users['uid']);
load()->model('user');
load()->model('module');
load()->model('frame');

//echo $uid;

$user = user_single(array('uid' => $uid));

//var_dump($user);

if (empty($user)) {
    message('您操作的用户不存在或是已经被删除！');
}
if (!pdo_fetchcolumn("SELECT id FROM ".tablename('uni_account_users')." WHERE uid = :uid AND uniacid = :uniacid", array(':uid' => $uid, ':uniacid' => $uniacid))) {
    message('此用户没有操作该统一公众号的权限，请选指派“管理者”权限！');
}

$system_permission = pdo_fetch('SELECT * FROM ' . tablename('users_permission') . ' WHERE uniacid = :aid AND uid = :uid AND type = :type', array(':aid' => $uniacid, ':uid' => $uid, ':type' => 'system'));
if(!empty($system_permission['permission'])) {
    $system_permission['permission'] = explode('|', $system_permission['permission']);
} else {
    $system_permission['permission'] = array();
}



$mods = pdo_fetchall('SELECT * FROM ' . tablename('users_permission') . ' WHERE uniacid = :aid AND uid = :uid AND type != :type', array(':aid' => $uniacid, ':uid' => $uid, ':type' => 'system'), 'type');
$mod_keys = array_keys($mods);


if (checksubmit('submit')) {
    $system_temp = array();
    if(!empty($_GPC['system'])) {
        foreach($_GPC['system'] as $li) {
            $li = trim($li);
            if(!empty($li)) {
                $system_temp[] = $li;
            }
        }
    }
    if(!empty($system_temp)) {
        if(empty($system_permission['id'])) {
            $insert = array(
                'uniacid' => $uniacid,
                'uid' => $uid,
                'type' => 'system',
            );
            $insert['permission'] = implode('|', $_GPC['system']);
            pdo_insert('users_permission', $insert);
        } else {
            $update = array(
                'permission' => implode('|', $_GPC['system'])
            );
            pdo_update('users_permission', $update, array('uniacid' => $uniacid, 'uid' => $uid));
        }
    } else {
        pdo_delete('users_permission', array('uniacid' => $uniacid, 'uid' => $uid));
    }
    pdo_query('DELETE FROM ' . tablename('users_permission') . ' WHERE uniacid = :uniacid AND uid = :uid AND type != :type', array(':uniacid' => $uniacid, ':uid' => $uid, ':type' => 'system'));
    if(!empty($_GPC['module'])) {
        $arr = array();
        foreach($_GPC['module'] as $li) {
            $insert = array(
                'uniacid' => $uniacid,
                'uid' => $uid,
                'type' => $li,
            );
            if(empty($_GPC['module_'. $li]) || $_GPC[$li . '_select'] == 1) {
                $insert['permission'] = 'all';
                pdo_insert('users_permission', $insert);
                continue;
            } else {
                $data = array();
                foreach($_GPC['module_'. $li] as $v) {
                    $data[] = $v;
                }
                if(!empty($data)) {
                    $insert['permission'] = implode('|', $data);
                    pdo_insert('users_permission', $insert);
                }
            }
        }
    }
    message('操作菜单权限成功！', $this->createWebUrl('users_permission',array('uid' => $core_users['uid'],'id' => $core_users['id'])), 'success');
}
/************ 系统 ************/
//$menus = frame_lists();
//foreach($menus as &$li) {
//    $li['childs'] = array();
//    if(!empty($li['child'])) {
//        foreach($li['child'] as $da) {
//            if(!empty($da['grandchild'])) {
//                foreach($da['grandchild'] as &$ca) {
//                    $li['childs'][] = $ca;
//                }
//            }
//        }
//        unset($li['child']);
//    }
//}
/************ 系统 ************/
//$_W['uniacid'] = $uniacid;
$modules = uni_modules();

//var_dump($module);

//echo json_encode($module);

include $this->template('web/users/users_permission');