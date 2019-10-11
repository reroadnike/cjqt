<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 6/10/17
 * Time: 3:33 AM
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_boardroom&do=boardroom */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/boardroom.class.php');
$boardroom = new boardroomModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $boardroom->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
            'name' => $_GPC['name'],
            'address' => $_GPC['address'],
            'floor' => $_GPC['floor'],
            'traffic' => $_GPC['traffic'],
//            'lat' => $_GPC['lat'],
//            'lng' => $_GPC['lng'],
            'lng' => $_GPC['baidumap']['lng'],
            'lat' => $_GPC['baidumap']['lat'],
            'thumb' => $_GPC['thumb'],
            'basic' => $_GPC['basic'],
//            'carousel' => $_GPC['carousel'],
            'price' => $_GPC['price'],
//            'equipment' => $_GPC['equipment'],
            'max_num' => $_GPC['max_num'],
            'appointment_num' => $_GPC['appointment_num'],
            'remark' => $_GPC['remark'],
            'cancle_rule' => $_GPC['cancle_rule'],

        );

        $boardroom->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('boardroom_manage', array('op' => 'list')), 'success');


    }
    include $this->template('boardroom_manage_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $boardroom->update($params, $where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('boardroom_manage', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 10;

    $result = $boardroom->queryAll(array(), $page, $page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    foreach ($list as $index => &$_item){


        $json_str = "{\"items\":[".iunserializer($_item['equipment'])."]}";
        $json = json_decode(htmlspecialchars_decode($json_str), true);
        $_item['equipment'] = $json['items'];
        $_item['thumb'] = tomedia($_item['thumb']);
//        var_dump($json);
//        echo "<br/>";
    }
    unset($_item);

    $pager = pagination($total, $page, $page_size);

    include $this->template('boardroom_manage_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $boardroom->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $boardroom->delete($id);

    message('删除成功！', referer(), 'success');

} elseif ($op == 'set_property') {

    $id = intval($_GPC['id']);
    $type = $_GPC['type'];
    $data = intval($_GPC['data']);

//    if (in_array($type, array('param_1', 'param_2', 'param_3', 'param_4'))) {
//        pdo_update("site_thinkinglib_goods_product", array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
//        die(json_encode(array("result" => 1, "data" => $data)));
//    }
//    if (in_array($type, array('status'))) {
//        $data = ($data == 1 ? '0' : '1');
//        pdo_update("site_thinkinglib_goods_product", array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
//        die(json_encode(array("result" => 1, "data" => $data)));
//    }
//    if (in_array($type, array('type'))) {
//        $data = ($data == 1 ? '2' : '1');
//        pdo_update("site_thinkinglib_goods_product", array($type => $data), array("id" => $id, "uniacid" => $_W['uniacid']));
//        die(json_encode(array("result" => 1, "data" => $data)));
//    }

    if (in_array($type, array('enabled'))) {
        $data = ($data == 1 ? '0' : '1');

        $params = array($type => $data);
        $boardroom->update($params,$id);
        die(json_encode(array("result" => 1, "data" => $data)));
    }

    die(json_encode(array("result" => 0)));
}


