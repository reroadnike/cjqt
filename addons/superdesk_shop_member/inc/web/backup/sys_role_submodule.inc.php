<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sys_role_submodule */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sys_role_submodule.class.php');
$sys_role_submodule = new sys_role_submoduleModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sys_role_submodule->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'role_id' => $_GPC['role_id'],// 角色id
    'sub_id' => $_GPC['sub_id'],// 子模块id

        );
        $sys_role_submodule->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sys_role_submodule', array('op' => 'list')), 'success');


    }
    include $this->template('sys_role_submodule_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sys_role_submodule->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sys_role_submodule', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sys_role_submodule->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sys_role_submodule_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sys_role_submodule->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sys_role_submodule->delete($id);

    message('删除成功！', referer(), 'success');
}

