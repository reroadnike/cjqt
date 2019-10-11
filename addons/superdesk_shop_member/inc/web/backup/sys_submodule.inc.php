<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sys_submodule */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sys_submodule.class.php');
$sys_submodule = new sys_submoduleModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sys_submodule->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'submodule_id' => $_GPC['submodule_id'],// 子模块ID
    'mid' => $_GPC['mid'],// 模块ID
    'submodule_name' => $_GPC['submodule_name'],// 子模块名称
    'submodule_info' => $_GPC['submodule_info'],// 子模块描述
    'submodule_url' => $_GPC['submodule_url'],// 子模块的连接地址
    'inserttime' => $_GPC['inserttime'],// 子模块创建时间
    'stat' => $_GPC['stat'],// 是否显示子模块；1，显示；0，禁用子模块；
    'orderTag' => $_GPC['orderTag'],// 排序I标识

        );
        $sys_submodule->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sys_submodule', array('op' => 'list')), 'success');


    }
    include $this->template('sys_submodule_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sys_submodule->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sys_submodule', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sys_submodule->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sys_submodule_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sys_submodule->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sys_submodule->delete($id);

    message('删除成功！', referer(), 'success');
}

