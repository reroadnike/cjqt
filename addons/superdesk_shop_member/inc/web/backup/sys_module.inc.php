<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=sys_module */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/sys_module.class.php');
$sys_module = new sys_moduleModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $sys_module->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'module_id' => $_GPC['module_id'],// 模块ID
    'module_name' => $_GPC['module_name'],// 模块名称
    'module_info' => $_GPC['module_info'],// 模块描述
    'module_image' => $_GPC['module_image'],// 模块图片
    'module_url' => $_GPC['module_url'],// 模块地址
    'stat' => $_GPC['stat'],// 是否显示模块；1，显示；0，禁用；
    'orderTag' => $_GPC['orderTag'],// 版块排序
    'inserttime' => $_GPC['inserttime'],// 模块创建时间

        );
        $sys_module->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('sys_module', array('op' => 'list')), 'success');


    }
    include $this->template('sys_module_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $sys_module->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('sys_module', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $sys_module->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('sys_module_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $sys_module->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $sys_module->delete($id);

    message('删除成功！', referer(), 'success');
}

