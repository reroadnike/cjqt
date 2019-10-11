<?php
/**
 * Created by linjinyu.
 * User: linjinyu
 * Date: 2018/01/08 * Time: 17:08 * http://192.168.1.124/superdesk/web/index.php?c=site&a=entry&m=superdesk_shop_member&do=shop_jd_logs */

global $_GPC, $_W;

include_once(IA_ROOT . '/addons/superdesk_shop_member/model/out_db/shop_jd_logs.class.php');
$shop_jd_logs = new shop_jd_logsModel();

$op = !empty($_GPC['op']) ? $_GPC['op'] : 'list';

if ($op == 'edit') {

    $item = $shop_jd_logs->getOne($_GPC['id']);

    if (checksubmit('submit')) {


        $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";
        $params = array(
    'jd_logs_id' => $_GPC['jd_logs_id'],// 
    'jd_logs_event' => $_GPC['jd_logs_event'],// camp-on : 表示预占事件，put-order :确认下单 ，cancle-order:取消订单
    'jd_logs_ctime' => $_GPC['jd_logs_ctime'],// 操作时间
    'jd_logs_sendData' => $_GPC['jd_logs_sendData'],// 发送的报文
    'jd_logs_receiveData' => $_GPC['jd_logs_receiveData'],// 接收到的报文
    'jd_logs_url' => $_GPC['jd_logs_url'],// 发送的地址
    'jd_logs_jdorderid' => $_GPC['jd_logs_jdorderid'],// 京东订单号
    'jd_logs_orderid' => $_GPC['jd_logs_orderid'],// 本系统订单号

        );
        $shop_jd_logs->saveOrUpdate($params, $id);

        message('成功！', $this->createWebUrl('shop_jd_logs', array('op' => 'list')), 'success');


    }
    include $this->template('shop_jd_logs_edit');

} elseif ($op == 'list') {

    if (!empty($_GPC['displayorder'])) {
        foreach ($_GPC['displayorder'] as $id => $displayorder) {

            $params = array('displayorder' => $displayorder);
            $where = array('id' => $id);

            $shop_jd_logs->update($params,$where);
        }
        message('显示顺序更新成功！', $this->createWebUrl('shop_jd_logs', array('op' => 'list')), 'success');
    }

    $page = $_GPC['page'];
    $page_size = 20;

    $result = $shop_jd_logs->queryAll(array(),$page,$page_size);
    $total = $result['total'];
    $page = $result['page'];
    $page_size = $result['page_size'];
    $list = $result['data'];

    $pager = pagination($total, $page, $page_size);

    include $this->template('shop_jd_logs_list');

} elseif ($op == 'delete') {

    $id = isset($_GPC['id']) ? empty($_GPC['id']) ? "" : $_GPC['id'] : "";

    $item = $shop_jd_logs->getOne($_GPC['id']);

    if (empty($item)) {
        message('抱歉，该信息不存在或是已经被删除！');
    }

    $shop_jd_logs->delete($id);

    message('删除成功！', referer(), 'success');
}

