<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 6/10/17
 * Time: 3:33 AM
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_boardroom&do=boardroom_equipment */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/boardroom_equipment.class.php');
$boardroom_equipment = new boardroom_equipmentModel();

$core_user = $this->superdesk_core_user();
//var_dump($core_user);

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $boardroom_equipment->getOne($_GPC['id']);

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
        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'name' => $_GPC['name'],
            'images' => $_GPC['images'],

        );

        if($core_user){
            $params['organization_code']= $core_user['organization_code'];
            $params['virtual_code']     = $core_user['virtual_code'];
        } else{
            $data['organization_code']  = $_GPC['organization_code'];
            $data['virtual_code']       = $_GPC['virtual_code'];
        }

        $boardroom_equipment->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('boardroom_equipment', array('op' => 'list')), 'success');


    }
    include $this->template('boardroom_equipment_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $boardroom_equipment->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('boardroom_equipment', array('op' => 'list')), 'success');
    }

    $_where = array();
    $page = max(1, intval($_GPC['page']));
    $page_size = 20;

    if($core_user){
        $_where['organization_code'] = $core_user['organization_code'];
        $_where['virtual_code']= $core_user['virtual_code'];
    }

    $result = $boardroom_equipment->queryAllByCoreUser($_where,$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    

    $pager = pagination($total, $page, $page_size);

    include $this->template('boardroom_equipment_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $boardroom_equipment->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $boardroom_equipment->delete($id);

    message('删除成功！', referer(), 'success');
    
} elseif ($op == 'set_property') {

    $id = intval($_GPC['id']);
    $type = $_GPC['type'];
    $data = intval($_GPC['data']);

    if (in_array($type, array('enabled'))) {
        $data = ($data == 1 ? '0' : '1');

        $params = array($type => $data);
        $boardroom_equipment->update($params,$id);
        die(json_encode(array("result" => 1, "data" => $data)));
    }

    die(json_encode(array("result" => 0)));
}


