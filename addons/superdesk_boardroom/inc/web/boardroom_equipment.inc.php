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



$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $boardroom_equipment->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'name' => $_GPC['name'],
            'images' => $_GPC['images'],

        );
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

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $boardroom_equipment->queryAll(array(),$page,$page_size);
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


