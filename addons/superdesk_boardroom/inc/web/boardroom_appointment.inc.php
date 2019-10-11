<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 6/10/17
 * Time: 3:33 AM
 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_boardroom&do=boardroom_appointment */

global $_GPC, $_W;

include_once(MODULE_ROOT . '/model/boardroom_appointment.class.php');
$boardroom_appointment = new boardroom_appointmentModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $boardroom_appointment->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'boardroom_id' => $_GPC['boardroom_id'],
    'openid' => $_GPC['openid'],
    'client_name' => $_GPC['client_name'],
    'client_telphone' => $_GPC['client_telphone'],
    'deleted' => $_GPC['deleted'],
    'state' => $_GPC['state'],
    'relate_id' => $_GPC['relate_id'],
    'people_num' => $_GPC['people_num'],
    'starttime' => $_GPC['starttime'],
    'endtime' => $_GPC['endtime'],

        );
        $boardroom_appointment->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('boardroom_appointment', array('op' => 'list')), 'success');


    }
    include $this->template('boardroom_appointment_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $boardroom_appointment->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('boardroom_appointment', array('op' => 'list')), 'success');
    }

    $paytype = array (
        '0' => array('css' => 'default', 'name' => '未支付'),
        '1' => array('css' => 'danger','name' => '余额支付'),
        '2' => array('css' => 'info', 'name' => '在线支付'),
        '3' => array('css' => 'warning', 'name' => '货到付款'),
        '4' => array('css' => 'info', 'name' => '无需支付')
    );
    $orderstatus = array (
        '-1' => array('css' => 'default', 'name' => '已取消'),
        '0' => array('css' => 'danger', 'name' => '待付款'),
        '1' => array('css' => 'info', 'name' => '待发货'),
        '2' => array('css' => 'warning', 'name' => '待收货'),
        '3' => array('css' => 'success', 'name' => '已完成')
    );

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $boardroom_appointment->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    foreach ($list as &$value){
        $s = $value['status'];
        $value['statuscss'] = $orderstatus[$value['status']]['css'];
        $value['status'] = $orderstatus[$value['status']]['name'];

        if ($s < 1) {
            $value['css'] = $paytype[$s]['css'];
            $value['paytype'] = $paytype[$s]['name'];
            continue;
        }
        $value['css'] = $paytype[$value['paytype']]['css'];
        if ($value['paytype'] == 2) {
            if (empty($value['transaction_id'])) {
                $value['paytype'] = '支付宝支付';
            } else {
                $value['paytype'] = '微信支付';
            }
        } else {
            $value['paytype'] = $paytype[$value['paytype']]['name'];
        }
    }

    $pager = pagination($total, $page, $page_size);

    include $this->template('boardroom_appointment_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $boardroom_appointment->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $boardroom_appointment->delete($id);

    message('删除成功！', referer(), 'success');
}

