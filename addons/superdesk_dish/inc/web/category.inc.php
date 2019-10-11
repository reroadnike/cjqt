<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:17 PM
 */

global $_GPC, $_W;
$GLOBALS['frames'] = $this->getNaveMenu();
$weid = $this->_weid;
$action = 'category';
$title = $this->actions_titles[$action];
$storeid = intval($_GPC['storeid']);

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'display') {
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update($this->table_category, array('displayorder' => $displayorder), array('id' => $id));
        }
        message('分类排序更新成功！', $this->createWebUrl('category', array('op' => 'display', 'storeid' => $storeid)), 'success');
    }
    $children = array();
    $category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = '$weid'  AND storeid ={$storeid} ORDER BY parentid ASC, displayorder DESC");
    foreach ($category as $index => $row) {
        if (!empty($row['parentid'])) {
            $children[$row['parentid']][] = $row;
            unset($category[$index]);
        }
    }
} elseif ($operation == 'post') {
    $parentid = intval($_GPC['parentid']);
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $category = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE id = '$id'");
    } else {
        $category = array(
            'displayorder' => 0,
        );
    }

    if (!empty($parentid)) {
        $parent = pdo_fetch("SELECT id, name FROM " . tablename($this->table_category) . " WHERE id = '$parentid'");
        if (empty($parent)) {
            message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
        }
    }
    if (checksubmit('submit')) {
        if (empty($_GPC['catename'])) {
            message('抱歉，请输入分类名称！');
        }

        $data = array(
            'weid' => $weid,
            'storeid' => $_GPC['storeid'],
            'name' => $_GPC['catename'],
            'displayorder' => intval($_GPC['displayorder']),
            'parentid' => intval($parentid),
        );

        if (empty($data['storeid'])) {
            message('非法参数');
        }

        if (!empty($id)) {
            unset($data['parentid']);
            pdo_update($this->table_category, $data, array('id' => $id));
        } else {
            pdo_insert($this->table_category, $data);
            $id = pdo_insertid();
        }
        message('更新分类成功！', $this->createWebUrl('category', array('op' => 'display', 'storeid' => $storeid)), 'success');
    }
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $category = pdo_fetch("SELECT id, parentid FROM " . tablename($this->table_category) . " WHERE id = '$id'");
    if (empty($category)) {
        message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category', array('op' => 'display', 'storeid' => $storeid)), 'error');
    }
    pdo_delete($this->table_category, array('id' => $id, 'parentid' => $id), 'OR');
    message('分类删除成功！', $this->createWebUrl('category', array('op' => 'display', 'storeid' => $storeid)), 'success');
} elseif ($operation == 'deleteall') {
    $rowcount = 0;
    $notrowcount = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $category = pdo_fetch("SELECT * FROM " . tablename($this->table_category) . " WHERE id = :id", array(':id' => $id));
            if (empty($category)) {
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_category, array('id' => $id, 'weid' => $weid));
            $rowcount++;
        }
    }
    $this->message("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!!", '', 0);
}
include $this->template('category');