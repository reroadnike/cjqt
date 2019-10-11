<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 6/10/17
 * Time: 3:33 AM
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_core&do=dictionary_group
 */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/dictionary_group.class.php');
$dictionary_group = new dictionary_groupModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $dictionary_group->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'groupcode' => $_GPC['groupcode'],
            'groupname' => $_GPC['groupname'],
            'isenabled' => $_GPC['isenabled'],
            'oprateversion' => $_GPC['oprateversion'],
            'opratetype' => $_GPC['opratetype'],
            'createby' => $_GPC['createby'],
            'lastupdateby' => $_GPC['lastupdateby'],
            'lastupdatetime' => $_GPC['lastupdatetime'],
            'createtime_' => $_GPC['createtime_'],
            'enabled' => $_GPC['enabled'],

        );
        $dictionary_group->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('dictionary_group', array('op' => 'list')), 'success');


    }
    include $this->template('dictionary_group_edit');

} elseif ($op == 'list') {

    include_once(MODULE_ROOT . '/model/dictionary_item.class.php');
    $dictionary_item = new dictionary_itemModel();

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $dictionary_group->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('dictionary_group', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 10;

    $result = $dictionary_group->queryAll(array(), $page, $page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    foreach ($list as $index => &$_item){

        $item_where = array();
        $item_where['groupid'] = $_item['id'];

        $_item['group_items'] = $dictionary_item->queryAll($item_where);

    }

    unset($_item);

    $pager = pagination($total, $page, $page_size);

    include $this->template('dictionary_group_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $dictionary_group->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $dictionary_group->delete($id);

    message('删除成功！', referer(), 'success');
}

