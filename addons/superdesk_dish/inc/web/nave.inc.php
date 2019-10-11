<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:23 PM
 */

global $_W, $_GPC;
checklogin();

$action = 'nave';
$title = '导航管理'; //$title = $this->actions_titles[$action];

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    if ($_GPC['type'] == 'default') {
        $this->insert_default_nave('我的菜单', 4, '');
        $this->insert_default_nave('智能点餐', 5, '');
        $this->insert_default_nave('商品列表', 3, '');
        $this->insert_default_nave('我的订单', 6, '');
        $this->insert_default_nave('门店列表', 2, '');
    }

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update($this->table_nave, array('displayorder' => $displayorder), array('id' => $id));
        }
        message('排序更新成功！', $this->createWebUrl('nave', array('op' => 'display')), 'success');
    }
    $children = array();
    $nave = pdo_fetchall("SELECT * FROM " . tablename($this->table_nave) . " WHERE weid = '{$_W['uniacid']}' ORDER BY displayorder DESC,id DESC");
    include $this->template('site_nave');
} elseif ($operation == 'post') {
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $nave = pdo_fetch("SELECT * FROM " . tablename($this->table_nave) . " WHERE id = '$id'");
    }

    if (checksubmit('submit')) {
        if (empty($_GPC['linkname'])) {
            message('抱歉，请输入导航名称！');
        }

        $data = array(
            'weid' => $_W['uniacid'],
            'type' => intval($_GPC['type']),
            'name' => trim($_GPC['linkname']),
            'link' => trim($_GPC['link']),
            'status' => intval($_GPC['status']),
            'displayorder' => intval($_GPC['displayorder']),
        );

        if (!empty($id)) {
            pdo_update($this->table_nave, $data, array('id' => $id));
        } else {
            pdo_insert($this->table_nave, $data);
            $id = pdo_insertid();
        }
        message('更新成功！', $this->createWebUrl('nave', array('op' => 'display')), 'success');
    }
    include $this->template('site_nave');
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $nave = pdo_fetch("SELECT id FROM " . tablename($this->table_nave) . " WHERE id = '$id'");
    if (empty($nave)) {
        message('抱歉，不存在或是已经被删除！', $this->createWebUrl('nave', array('op' => 'display')), 'error');
    }
    pdo_delete($this->table_nave, array('id' => $id));
    message('删除成功！', $this->createWebUrl('nave', array('op' => 'display')), 'success');
}