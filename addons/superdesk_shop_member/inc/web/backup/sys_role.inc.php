<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sys_role */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sys_role.class.php');
$sys_role = new sys_roleModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sys_role->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'role_id' => $_GPC['role_id'],// 
    'role_name' => $_GPC['role_name'],// 名称
    'role_orderTag' => $_GPC['role_orderTag'],// 排序
    'role_stat' => $_GPC['role_stat'],// 状态
    'role_inserttime' => $_GPC['role_inserttime'],// 创建时间

        );
        $sys_role->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sys_role', array('op' => 'list')), 'success');


    }
    include $this->template('sys_role_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sys_role->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sys_role', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sys_role->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sys_role_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sys_role->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sys_role->delete($id);

    message('删除成功！', referer(), 'success');
}

