<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 6/10/17
 * Time: 3:33 AM
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_core&do=virtualarchitecture */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/virtualarchitecture.class.php');
$virtualarchitecture = new virtualarchitectureModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $virtualarchitecture->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'name' => $_GPC['name'],
            'organizationId' => $_GPC['organizationId'],
            'type' => $_GPC['type'],
            'code' => $_GPC['code'],
            'parentCode' => $_GPC['parentCode'],
            'remark' => $_GPC['remark'],
            'codeNumber' => $_GPC['codeNumber'],
            'customerNumber' => $_GPC['customerNumber'],
            'phone' => $_GPC['phone'],
            'address' => $_GPC['address'],
            'contacts' => $_GPC['contacts'],
            'employees' => $_GPC['employees'],
            'reserveBalance' => $_GPC['reserveBalance'],
            'customerType' => $_GPC['customerType'],
            'contractStatus' => $_GPC['contractStatus'],
            'status' => $_GPC['status'],
            'reviewRemark' => $_GPC['reviewRemark'],
            'wxUserId' => $_GPC['wxUserId'],
            'creator' => $_GPC['creator'],
            'createTime' => $_GPC['createTime'],
            'modifier' => $_GPC['modifier'],
            'modifyTime' => $_GPC['modifyTime'],
            'isEnabled' => $_GPC['isEnabled'],
            'createtime_' => $_GPC['createtime_'],
            'enabled' => $_GPC['enabled'],

        );
        $virtualarchitecture->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('virtualarchitecture', array('op' => 'list')), 'success');


    }
    include $this->template('virtualarchitecture_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $virtualarchitecture->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('virtualarchitecture', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $virtualarchitecture->queryAll(array(), $page, $page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('virtualarchitecture_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $virtualarchitecture->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $virtualarchitecture->delete($id);

    message('删除成功！', referer(), 'success');
}

