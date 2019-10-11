<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 6/10/17
 * Time: 3:33 AM
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_core&do=build */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/build.class.php');
$build = new buildModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $build->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'name' => $_GPC['name'],
            'organizationId' => $_GPC['organizationId'],
            'vip' => $_GPC['vip'],
            'remark' => $_GPC['remark'],
            'address' => $_GPC['address'],
            'lng' => $_GPC['lng'],
            'lat' => $_GPC['lat'],
            'createTime' => $_GPC['createTime'],
            'creator' => $_GPC['creator'],
            'modifier' => $_GPC['modifier'],
            'modifyTime' => $_GPC['modifyTime'],
            'isEnabled' => $_GPC['isEnabled'],
            'createtime_' => $_GPC['createtime_'],
            'enabled' => $_GPC['enabled'],

        );
        $build->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('build', array('op' => 'list')), 'success');


    }
    include $this->template('build_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $build->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('build', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $build->queryAll(array(), $page, $page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    include_once(MODULE_ROOT . '/model/organization.class.php');
    $organization = new organizationModel();

    foreach ($list as $index => &$_item){

        $item_where = array();
        $item_where['id'] = $_item['organizationId'];

        $_item['organization'] = $organization->getOneByColumn($item_where);

    }

    $pager = pagination($total, $page, $page_size);

    include $this->template('build_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $build->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $build->delete($id);

    message('删除成功！', referer(), 'success');
}

