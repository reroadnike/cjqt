<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 3/6/18
 * Time: 2:41 PM
 */

global $_W, $_GPC;
checklogin();
load()->func('tpl');

$url       = $this->createWebUrl('feedback', array('op' => 'display'));
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';

$totalcount   = pdo_fetchcolumn("SELECT COUNT(1) as count FROM " . tablename($this->modulename . '_feedback') . "  WHERE uniacid = :uniacid", array(':uniacid' => $_W['uniacid']));
$nocheckcount = pdo_fetchcolumn("SELECT COUNT(1) as count FROM " . tablename($this->modulename . '_feedback') . "  WHERE uniacid = :uniacid AND status=0", array(':uniacid' => $_W['uniacid']));
$checkcount   = pdo_fetchcolumn("SELECT COUNT(1) as count FROM " . tablename($this->modulename . '_feedback') . "  WHERE uniacid = :uniacid AND status=1", array(':uniacid' => $_W['uniacid']));

if ($operation == 'post') {

    $id = intval($_GPC['id']);

    $item = pdo_fetch(
        " select * " .
        " from " . tablename($this->modulename . '_feedback') .
        " where id=:id " .
        "       AND uniacid =:uniacid",
        array(
            ':id'      => $id,
            ':uniacid' => $_W['uniacid']
        )
    );

    if (checksubmit('submit')) {
        $data = array(
            'uniacid'      => $_W['uniacid'],
            //'parentid' => intval($_GPC['parentid']),
            'nickname'     => trim($_GPC['username']),
            'username'     => trim($_GPC['username']),
            'headimgurl'   => trim($_GPC['headimgurl']),
            'content'      => trim($_GPC['content']),
            'displayorder' => intval($_GPC['displayorder']),
            'status'       => intval($_GPC['status']),
            'dateline'     => TIMESTAMP,
        );

        if (!empty($_GPC['headimgurl'])) {

            $data['headimgurl'] = $_GPC['headimgurl'];
            load()->func('file');
            file_delete($_GPC['headimgurl-old']);

        }

        if (!empty($item)) {
            unset($data['nickname']);
            unset($data['dateline']);
            pdo_update($this->modulename . '_feedback', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
        } else {
            pdo_insert($this->modulename . '_feedback', $data);
        }
        message('操作成功', $url, 'success');
    }

} elseif ($operation == 'reply') {

    $parentid = intval($_GPC['parentid']);

    $item = pdo_fetch(
        " select * " .
        " from " . tablename($this->modulename . '_feedback') .
        " where id=:id " .
        "       AND uniacid =:uniacid " .
        "       AND parentid=0 " .
        " LIMIT 1",
        array(
            ':id'      => $parentid,
            ':uniacid' => $_W['uniacid']
        )
    );
    if (empty($item)) {
        message('数据不存在!');
    }

    if (checksubmit('submit')) {
        $data = array(
            'uniacid'      => $_W['uniacid'],
            'parentid'     => $parentid,
            'nickname'     => '管理员',
            'username'     => '管理员',
            'headimgurl'   => '',
            'content'      => trim($_GPC['content']),
            'displayorder' => 0,
            'status'       => 1,
            'dateline'     => TIMESTAMP,
        );

        pdo_insert($this->modulename . '_feedback', $data);
        message('操作成功', $url, 'success');
    }
} elseif ($operation == 'display') {

    if (isset($_GPC['status'])) {
        $status = intval($_GPC['status']);
    } else {
        $status = -1;
    }

    if (checksubmit('submit')) { //排序
        if (is_array($_GPC['displayorder'])) {
            foreach ($_GPC['displayorder'] as $id => $val) {
                $data = array('displayorder' => intval($_GPC['displayorder'][$id]));
                pdo_update($this->modulename . '_feedback', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
            }
        }
        message('操作成功!', $url);
    }

    $pindex = max(1, intval($_GPC['page']));
    $psize  = 10;
    $where  = ' AND parentid=0 ';

    if ($status != -1) {
        if ($status == 0) {
            $where = '';
        }
        $where .= " AND status={$status} ";
    }

    $list = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_feedback') . " WHERE uniacid=" . $_W['uniacid'] . " {$where} ORDER BY displayorder DESC,id DESC LIMIT " . ($pindex - 1) * $psize . ",{$psize}", array(), 'id');

    $parentids = array_keys($list);
    $childlist = pdo_fetchall("SELECT * FROM " . tablename($this->modulename . '_feedback') . " WHERE parentid IN ('" . implode("','", is_array($parentids) ? $parentids : array($parentids)) . "') AND parentid!=0 AND uniacid=:uniacid ORDER BY displayorder DESC,id DESC", array(':uniacid' => $_W['uniacid']));
    foreach ($childlist as $index => $row) {
        if (!empty($row['parentid'])) {
            $children[$row['parentid']][] = $row;
        }
    }

    if (!empty($list)) {
        $total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename($this->modulename . '_feedback') . " WHERE uniacid=" . $_W['uniacid'] . " {$where}");
        $pager = pagination($total, $pindex, $psize);
    }

} elseif ($operation == 'delete') {
    $id   = intval($_GPC['id']);
    $data = pdo_fetch("SELECT id FROM " . tablename($this->modulename . '_feedback') . " WHERE id = :id", array(':id' => $id));
    if (empty($data)) {
        message('抱歉，不存在或是已经被删除！', $this->createWebUrl('_feedback', array('op' => 'display')), 'error');
    }
    if ($data['parentid'] == 0) {
        pdo_delete($this->modulename . '_feedback', array('parentid' => $id, 'uniacid' => $_W['uniacid']));
    }
    pdo_delete($this->modulename . '_feedback', array('id' => $id, 'uniacid' => $_W['uniacid']));
    message('删除成功！', $this->createWebUrl('feedback', array('op' => 'display')), 'success');
} elseif ($operation == 'deleteall') {
    $rowcount    = 0;
    $notrowcount = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $feedback = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_feedback') . " WHERE id = :id", array(':id' => $id));
            if (empty($feedback)) {
                $notrowcount++;
                continue;
            }
            if ($feedback['parentid'] == 0) {
                pdo_delete($this->modulename . '_feedback', array('parentid' => $id, 'uniacid' => $_W['uniacid']));
            }
            pdo_delete($this->modulename . '_feedback', array('id' => $id, 'uniacid' => $_W['uniacid']));
            $rowcount++;
        }
    }
    $this->message("操作成功！共删除{$rowcount}条数据,{$notrowcount}条数据不能删除!", '', 0);

} elseif ($operation == 'checkall') {
    $rowcount    = 0;
    $notrowcount = 0;
    foreach ($_GPC['idArr'] as $k => $id) {
        $id = intval($id);
        if (!empty($id)) {
            $feedback = pdo_fetch("SELECT * FROM " . tablename($this->modulename . '_feedback') . " WHERE id = :id", array(':id' => $id));
            if (empty($feedback)) {
                $notrowcount++;
                continue;
            }

            $data = empty($feedback['status']) ? 1 : 0;
            pdo_update($this->modulename . '_feedback', array('status' => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
            $rowcount++;
        }
    }
    $this->message("操作成功！共审核{$rowcount}条数据,{$notrowcount}条数据不能删除!!", '', 0);
}

include $this->template('feedback');