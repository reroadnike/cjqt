<?php
/**
 * 商品管理
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 9/9/17
 * Time: 12:16 PM
 */

global $_GPC, $_W;
$GLOBALS['frames'] = $this->getNaveMenu();
$weid = $this->_weid;
$action = 'goods';
$title = $this->actions_titles[$action];
$storeid = intval($_GPC['storeid']);

if (empty($storeid)) {
    message('请选择门店!');
}

$category = pdo_fetchall("SELECT * FROM " . tablename($this->table_category) . " WHERE weid = :weid And storeid=:storeid ORDER BY parentid ASC, displayorder DESC", array(':weid' => $weid, ':storeid' => $storeid), 'id');
if (!empty($category)) {
    $children = '';
    foreach ($category as $cid => $cate) {
        if (!empty($cate['parentid'])) {
            $children[$cate['parentid']][$cate['id']] = array($cate['id'], $cate['name']);
        }
    }
}

$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';
if ($operation == 'post') {
    load()->func('tpl');
    $id = intval($_GPC['id']);
    if (!empty($id)) {
        $item = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE id = :id", array(':id' => $id));
        if (empty($item)) {
            message('抱歉，商品不存在或是已经删除！', '', 'error');
        } else {
            if (!empty($item['thumb_url'])) {
                $item['thumbArr'] = explode('|', $item['thumb_url']);
            }
        }
    }
    if (checksubmit('submit')) {
        $data = array(
            'weid' => intval($weid),
            'storeid' => $storeid,
            'title' => trim($_GPC['goodsname']),
            'pcate' => intval($_GPC['pcate']),
            'ccate' => intval($_GPC['ccate']),
            'thumb' => trim($_GPC['thumb']),
            'credit' => intval($_GPC['credit']),
            'unitname' => trim($_GPC['unitname']),
            'description' => trim($_GPC['description']),
            'taste' => trim($_GPC['taste']),
            'isspecial' => empty($_GPC['marketprice']) ? 1 : 2,
            'marketprice' => trim($_GPC['marketprice']),
            'productprice' => trim($_GPC['productprice']),
            'subcount' => intval($_GPC['subcount']),
            'status' => intval($_GPC['status']),
            'recommend' => intval($_GPC['recommend']),
            'displayorder' => intval($_GPC['displayorder']),
            'dateline' => TIMESTAMP,
        );

        if (empty($data['title'])) {
            message('请输入商品名称！');
        }
        if (empty($data['pcate'])) {
            message('请选择商品分类！');
        }

        if (!empty($_FILES['thumb']['tmp_name'])) {
            load()->func('file');
            file_delete($_GPC['thumb_old']);
            $upload = file_upload($_FILES['thumb']);
            if (is_error($upload)) {
                message($upload['message'], '', 'error');
            }
            $data['thumb'] = $upload['path'];
        }
        if (empty($id)) {
            pdo_insert($this->table_goods, $data);
        } else {
            unset($data['dateline']);
            pdo_update($this->table_goods, $data, array('id' => $id));
        }
        message('商品更新成功！', $this->createWebUrl('goods', array('op' => 'display', 'storeid' => $storeid)), 'success');
    }
} elseif ($operation == 'display') {
    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update($this->table_goods, array('displayorder' => $displayorder), array('id' => $id));
        }
        message('排序更新成功！', $this->createWebUrl('goods', array('op' => 'display', 'storeid' => $storeid)), 'success');
    }

    $pindex = max(1, intval($_GPC['page']));
    $psize = 8;
    $condition = '';
    if (!empty($_GPC['keyword'])) {
        $condition .= " AND title LIKE '%{$_GPC['keyword']}%'";
    }

    if (!empty($_GPC['category_id'])) {
        $cid = intval($_GPC['category_id']);
        $condition .= " AND pcate = '{$cid}'";
    }

    if (isset($_GPC['status'])) {
        $condition .= " AND status = '" . intval($_GPC['status']) . "'";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->table_goods) . " WHERE weid = '{$_W['uniacid']}' AND storeid ={$storeid} $condition ORDER BY status DESC, displayorder DESC, id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize);

    $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename($this->table_goods) . " WHERE weid = '{$_W['uniacid']}' AND storeid ={$storeid} $condition");

    $pager = pagination($total, $pindex, $psize);
} elseif ($operation == 'delete') {
    $id = intval($_GPC['id']);
    $row = pdo_fetch("SELECT id, thumb FROM " . tablename($this->table_goods) . " WHERE id = :id", array(':id' => $id));
    if (empty($row)) {
        message('抱歉，商品 不存在或是已经被删除！');
    }
    if (!empty($row['thumb'])) {
        load()->func('file');
        file_delete($row['thumb']);
    }
    pdo_delete($this->table_goods, array('id' => $id));
    message('删除成功！', referer(), 'success');
} elseif ($operation == 'deleteall') {
    $rowcount = 0;
    $notrowcount = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $goods = pdo_fetch("SELECT * FROM " . tablename($this->table_goods) . " WHERE id = :id", array(':id' => $id));
            if (empty($goods)) {
                $notrowcount++;
                continue;
            }
            pdo_delete($this->table_goods, array('id' => $id, 'weid' => $_W['uniacid']));
            $rowcount++;
        }
    }
    $this->message("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);
}
include $this->template('goods');