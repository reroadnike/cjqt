<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/29/17
 * Time: 1:56 PM
 */

global $_W, $_GPC;

if ($_W['isajax']) {

    load()->model('module');
    $module_name          = trim($_GPC['module_name']);
    $uniacid    = intval($_GPC['uniacid']);
    $uid        = intval($_GPC['uid']);
    $modules = pdo_fetch('SELECT * FROM ' . tablename('modules') . ' WHERE name = :m', array(':m' => $module_name));
    $purview = pdo_fetch('SELECT * FROM ' . tablename('users_permission') . ' WHERE uniacid = :aid AND uid = :uid AND type = :type', array(':aid' => $uniacid, ':uid' => $uid, ':type' => $module_name));
    if (!empty($purview['permission'])) {
        $purview['permission'] = explode('|', $purview['permission']);
    } else {
        $purview['permission'] = array();
    }

    $mineurl = array();
    $all = 0;
    if (!empty($mods)) {
        foreach ($mods as $mod) {
            if ($mod['url'] == 'all') {
                $all = 1;
                break;
            } else {
                $mineurl[] = $mod['url'];
            }
        }
    }
    $data = array();
    if ($modules['settings']) {
        $data[] = array('title' => '参数设置', 'permission' => $module_name . '_settings');
    }
    if ($modules['isrulefields']) {
        $data[] = array('title' => '回复规则列表', 'permission' => $module_name . '_rule');
    }
    $entries = module_entries($module_name);
    if (!empty($entries['home'])) {
        $data[] = array('title' => '微站首页导航', 'permission' => $module_name . '_home');
    }
    if (!empty($entries['profile'])) {
        $data[] = array('title' => '个人中心导航', 'permission' => $module_name . '_profile');
    }
    if (!empty($entries['shortcut'])) {
        $data[] = array('title' => '快捷菜单', 'permission' => $module_name . '_shortcut');
    }
    if (!empty($entries['cover'])) {
        foreach ($entries['cover'] as $cover) {
            $data[] = array('title' => $cover['title'], 'permission' => $module_name . '_cover_' . $cover['do']);
        }
    }
    if (!empty($entries['menu'])) {
        foreach ($entries['menu'] as $menu) {
            $data[] = array('title' => $menu['title'], 'permission' => $module_name . '_menu_' . $menu['do']);
        }
    }
    unset($entries);
    if (!empty($modules['permissions'])) {
        $modules['permissions'] = (array)iunserializer($modules['permissions']);
        $data = array_merge($data, $modules['permissions']);
    }
    foreach ($data as &$da) {
        $da['checked'] = 0;
        if (in_array($da['permission'], $purview['permission']) || in_array('all', $purview['permission'])) {
            $da['checked'] = 1;
        }
    }
    $out['errno'] = 0;
    $out['errmsg'] = '';
    if (empty($data)) {
        $out['errno'] = 1;
    } else {
        $out['errmsg'] = $data;
    }
    exit(json_encode($out));
}