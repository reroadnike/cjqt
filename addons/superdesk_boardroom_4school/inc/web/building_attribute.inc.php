<?php
/**
 * Created by PhpStorm.
 * User: linjinyu
 * Date: 8/14/17
 * Time: 6:47 PM
 */

global $_GPC, $_W;
load()->func('tpl');
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'display';



$core_user = $this->superdesk_core_user();
//var_dump($core_user);

if ($operation == 'display') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {
            pdo_update('superdesk_boardroom_4school_building_attribute', array('displayorder' => $displayorder), array('id' => $id, 'uniacid' => $_W['uniacid']));
        }
        message('排序更新成功！', $this->createWebUrl('building_attribute', array('op' => 'display')), 'success');
    }

    $children = array();

    include_once(MODULE_ROOT . '/model/boardroom_4school_building_attribute.class.php');
    $building_attributeModel = new boardroom_4school_building_attributeModel();


    $_where = array();
    $page = intval($_GPC['page'],1);
    $page_size = 100;

    if($core_user){
        $_where['organization_code'] = $core_user['organization_code'];
        $_where['virtual_code']= $core_user['virtual_code'];
    }


    $result = $building_attributeModel->queryAllByCoreUser($_where,$page,$page_size);
    $building_attribute = $result['data'];

//    $building_attribute = pdo_fetchall("SELECT * FROM " . tablename('superdesk_boardroom_4school_building_attribute') . " WHERE uniacid = '{$_W['uniacid']}' ORDER BY parentid ASC, displayorder DESC");

    foreach ($building_attribute as $index => $row) {
        if (!empty($row['parentid'])) {
            $children[$row['parentid']][] = $row;
            unset($building_attribute[$index]);
        }
    }

    include $this->template('building_attribute');

} elseif ($operation == 'post') {

    $parentid   = intval($_GPC['parentid']);
    $id         = intval($_GPC['id']);

    if (!empty($id)) {
        $item = pdo_fetch("SELECT * FROM " . tablename('superdesk_boardroom_4school_building_attribute') . " WHERE id = :id AND uniacid = :uniacid", array(':id' => $id, ':uniacid' => $_W['uniacid']));
    } else {
        $item = array('displayorder' => 0,);
    }

    if (!empty($parentid)) {
        $parent = pdo_fetch("SELECT id, name FROM " . tablename('superdesk_boardroom_4school_building_attribute') . " WHERE id = '$parentid'");
        if (empty($parent)) {
            message('抱歉，上级不存在或是已经被删除！', $this->createWebUrl('post'), 'error');
        }
    }

    if($core_user){

    } else{
        /************ 初始化项目 ***********/
        include_once(MODULE_ROOT . '/../superdesk_core/model/organization.class.php');
        $organizationModel = new organizationModel();
        $result_organization = $organizationModel->querySelector(array(),1,999);
        $selector_organization = $result_organization['data'];

        include_once(MODULE_ROOT . '/../superdesk_core/model/virtualarchitecture.class.php');
        $virtualarchitectureModel = new virtualarchitectureModel();
        $result_virtuals = $virtualarchitectureModel->queryForUsersAjax(array("codeNumber" => $item['virtual_code']), 1, 999);
        $selector_virtuals = $result_virtuals['data'];
        /************ 初始化项目 ***********/
    }


    if (checksubmit('submit')) {

        if (empty($_GPC['catename'])) {
            message('抱歉，请输入名称！');
        }

        $data = array(
            'uniacid' => $_W['uniacid'],
            'name' => $_GPC['catename'],
            'enabled' => intval($_GPC['enabled']),
            'displayorder' => intval($_GPC['displayorder']),
            'isrecommand' => intval($_GPC['isrecommand']),
            'description' => $_GPC['description'],
            'parentid' => intval($parentid),
            'thumb' => $_GPC['thumb']
        );

        if($core_user){
            $data['organization_code']  = $core_user['organization_code'];
            $data['virtual_code']       = $core_user['virtual_code'];
        } else{
            $data['organization_code']  = $_GPC['organization_code'];
            $data['virtual_code']       = $_GPC['virtual_code'];
        }

        if (!empty($id)) {
            unset($data['parentid']);
            pdo_update('superdesk_boardroom_4school_building_attribute', $data, array('id' => $id, 'uniacid' => $_W['uniacid']));
            load()->func('file');
            file_delete($_GPC['thumb_old']);
        } else {
            pdo_insert('superdesk_boardroom_4school_building_attribute', $data);
            $id = pdo_insertid();
        }
        message('更新成功！', $this->createWebUrl('building_attribute', array('op' => 'display')), 'success');
    }

    include $this->template('building_attribute');

} elseif ($operation == 'delete') {

    $id = intval($_GPC['id']);
    $item = pdo_fetch("SELECT id, parentid FROM " . tablename('superdesk_boardroom_4school_building_attribute') . " WHERE id = '$id'");

    if (empty($item)) {
        message('抱歉，不存在或是已经被删除！', $this->createWebUrl('building_attribute', array('op' => 'display')), 'error');
    }

    pdo_delete('superdesk_boardroom_4school_building_attribute', array('id' => $id, 'parentid' => $id), 'OR');
    message('删除成功！', $this->createWebUrl('building_attribute', array('op' => 'display')), 'success');
}