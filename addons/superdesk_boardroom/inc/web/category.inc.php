<?php

/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 7/29/17
 * Time: 4:36 AM
 */

global $_GPC, $_W;
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update('superdesk_boardroom_s_category', array('displayorder' => $displayorder), array('id' => $id, 'uniacid' => $_W['uniacid']));
        }
        message('分类排序更新成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
    }
    $children = array();
    $category = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_s_category') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC");
    foreach ($category as $index => $row) {
        if (!empty($row['parentid'])) {
            $children[$row['parentid']][] = $row;
            unset($category[$index]);
        }
    }
    include $this->template('category');
} elseif ($operation == 'post') {
    $parentid = intval($_GPC['parentid']);
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $category = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_s_category') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $_W['uniacid']));
    } else {
        $category = array(
            'displayorder' => 0,
        );
    }
    if (!empty($parentid)) {
        $parent = pdo_fetch("SELECT id, name FROM " . tablename('superdesk_boardroom_s_category') . " WHERE id = '$parentid'");
        if (empty($parent)) {
            message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
        }
    }
    if (checksubmit('submit')) {
        if (empty($_GPC['catename'])) {
            message('抱歉，请输入分类名称！');
        }
        $data = array(
            'uniacid' => $_W['uniacid'],
            'name' => $_GPC['catename'],
            'enabled' => intval($_GPC['enabled']),
            'displayorder' => intval($_GPC['displayorder']),
            'isrecommand' => intval($_GPC['isrecommand']),
            'description' => $_GPC['description'],
            'parentid' => intval($parentid),
            'thumb' => $_GPC['thumb']
        );
        if (!empty($id)) {
            unset($data['parentid']);
            pdo_update('superdesk_boardroom_s_category', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
            load()->func('file');
            file_delete($_GPC['thumb_old']);
        } else {
            pdo_insert('superdesk_boardroom_s_category', $data);
            $id = pdo_insertid();
        }
        message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
    }
    include $this->template('category');
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $category = pdo_fetch("SELECT id, parentid FROM " . tablename('superdesk_boardroom_s_category') . " WHERE id = '$id'");
    if (empty($category)) {
        message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display')), 'error');
    }
    pdo_delete('superdesk_boardroom_s_category', array('id' => $id, 'parentid' => $id), 'OR');
    message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display')), 'success');
}